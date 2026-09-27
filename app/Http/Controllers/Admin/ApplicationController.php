<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ApplicationService;
use App\Http\Requests\UpdateApplicationStatusRequest;
use App\Exports\ApplicationsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    protected $applicationService;

    public function __construct(ApplicationService $applicationService)
    {
        $this->applicationService = $applicationService;
    }

    public function index(Request $request)
    {
        $applications = $this->applicationService->getAllApplications();
        $statusCounts = $this->applicationService->getStatusCounts();
        return view('admin.applications.index', compact('applications', 'statusCounts'));
    }

    public function show($id)
    {
        $application = $this->applicationService->getApplicationById($id);
        return view('admin.applications.show', compact('application'));
    }

    public function updateStatus(UpdateApplicationStatusRequest $request, $id)
    {
        $this->applicationService->updateApplicationStatus($id, $request->status);
        return redirect()->back()->with('success', 'Application status updated successfully.');
    }

    public function exportCsv()
    {
        $filename = 'Laporan_Pelamar_' . date('Y-m-d_H-i') . '.csv';
        return Excel::download(new ApplicationsExport, $filename, \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportPdf()
    {
        $query = \App\Models\Application::with(['user.candidateProfile', 'job'])->latest();
        $user = auth()->user();

        if ($user && !$user->hasRole('Super Admin')) {
            $companyProfile = $user->currentCompanyProfile();
            $companyName = $companyProfile ? $companyProfile->company_name : null;

            if ($companyName) {
                $query->whereHas('job', function($j) use ($companyName) {
                    $j->where('company_name', 'LIKE', '%' . $companyName . '%');
                });
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        $applications = $query->get();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.applications_report', compact('applications'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('Laporan_Pelamar_' . date('Y-m-d') . '.pdf');
    }

    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'application_ids' => 'required|array|min:1',
            'status' => 'required|string',
        ]);

        $count = 0;
        foreach ($request->application_ids as $id) {
            $realId = \App\Helpers\IdHasher::decode($id) ?? $id;
            $this->applicationService->updateApplicationStatus($realId, $request->status);
            $count++;
        }

        return redirect()->back()->with('success', "Status {$count} pelamar berhasil diperbarui menjadi " . strtoupper($request->status) . '!');
    }

    /**
     * Reset candidate test result and allow 1x retake in case of technical issues.
     */
    public function resetTest($id)
    {
        $realId = \App\Helpers\IdHasher::decode($id) ?? $id;
        $application = \App\Models\Application::with(['user', 'job.test'])->findOrFail($realId);

        \App\Models\CandidateTestResult::where('user_id', $application->user_id)
            ->where('job_id', $application->job_id)
            ->delete();

        // Generate fresh test token for retake session
        $newToken = 'TK-' . strtoupper(\Illuminate\Support\Str::random(6));
        $application->update([
            'status' => \App\Enums\ApplicationStatus::TEST,
            'test_token' => $newToken,
        ]);

        $jobTest = $application->job ? $application->job->test : null;
        if (!$jobTest && $application->job) {
            $jobTest = \App\Models\JobTest::firstOrCreate(
                ['job_id' => $application->job_id],
                [
                    'title' => 'Tes Psikotes & Seleksi: ' . ($application->job->title ?? 'Pekerjaan'),
                    'category' => 'psikotes',
                    'duration_minutes' => 60,
                    'passing_score' => 70,
                    'is_active' => true,
                ]
            );
        }

        // In-app Notification
        \App\Models\UserNotification::send(
            $application->user_id,
            "🔄 Kesempatan Ujian Online Direset",
            "HR telah memberikan kesempatan ujian ulang untuk posisi {$application->job->title}. Token Baru Anda: {$newToken}. Silakan periksa email Anda.",
            route('candidate.tests.show', $application->job_id),
            'info'
        );

        // Dispatch Retake Email with New Token & Summary
        if ($application->user && $application->user->email) {
            try {
                \Illuminate\Support\Facades\Mail::to($application->user->email)
                    ->send(new \App\Mail\CandidateTestRetakeMail($application, $jobTest, $newToken));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error("Failed to send retake email to candidate {$application->user->email}: " . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Kesempatan ujian online berhasil direset! Token baru (' . $newToken . ') dan email undangan ujian ulang telah otomatis dikirimkan ke kandidat.');
    }

    /**
     * Resend candidate test token and schedule details via email & in-app notification.
     */
    public function resendTestToken($id)
    {
        $realId = \App\Helpers\IdHasher::decode($id) ?? $id;
        $application = \App\Models\Application::with(['user', 'job.test'])->findOrFail($realId);

        if (!$application->test_token) {
            $application->test_token = 'TK-' . strtoupper(\Illuminate\Support\Str::random(6));
            $application->save();
        }

        $jobTest = $application->job ? $application->job->test : null;
        if (!$jobTest && $application->job) {
            $jobTest = \App\Models\JobTest::firstOrCreate(
                ['job_id' => $application->job_id],
                [
                    'title' => 'Tes Psikotes & Seleksi: ' . ($application->job->title ?? 'Pekerjaan'),
                    'category' => 'psikotes',
                    'duration_minutes' => 60,
                    'passing_score' => 70,
                    'is_active' => true,
                ]
            );
        }

        if ($application->user && $application->user->email) {
            try {
                \Illuminate\Support\Facades\Mail::to($application->user->email)
                    ->send(new \App\Mail\CandidateTestInvitationMail($application, $jobTest, $application->test_token));

                $scheduleInfo = "Token Akses Ujian Anda: {$application->test_token}.";
                if ($jobTest->starts_at) {
                    $scheduleInfo .= " Mulai: " . $jobTest->starts_at->format('d/m/Y H:i') . " WIB.";
                }
                if ($jobTest->deadline_at) {
                    $scheduleInfo .= " Batas Akhir: " . $jobTest->deadline_at->format('d/m/Y H:i') . " WIB.";
                }

                \App\Models\UserNotification::send(
                    $application->user_id,
                    '📝 Token & Jadwal Ujian Online: ' . ($application->job->title ?? 'Pekerjaan'),
                    "{$scheduleInfo} Silakan cek email Anda untuk informasi lengkap ujian.",
                    route('candidate.tests.show', $application->job_id),
                    'info'
                );

                return redirect()->back()->with('success', 'Kode token dan rangkuman jadwal ujian berhasil dikirimkan ke email ' . $application->user->email . '!');
            } catch (\Throwable $e) {
                return redirect()->back()->with('error', 'Gagal mengirim email: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('error', 'Kandidat tidak memiliki alamat email yang valid.');
    }
}
