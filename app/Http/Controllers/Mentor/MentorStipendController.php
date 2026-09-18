<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\InternshipStipendDisbursement;
use App\Models\User;
use App\Models\InternshipBatch;
use App\Models\InternshipLogbook;
use App\Models\CandidateOnboarding;
use App\Models\ApplicationAgreement;
use App\Models\AuditLog;
use App\Models\UserNotification;
use App\Mail\StipendBankReminderMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MentorStipendController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $companyId = $user->companyProfile ? $user->companyProfile->id : null;

        $selectedBatch = $request->query('batch');
        $selectedMonth = $request->query('month', date('Y-m'));
        $selectedStatus = $request->query('status');
        $selectedBankStatus = $request->query('bank_status');
        $selectedAttendance = $request->query('attendance');
        $search = $request->query('search');

        // Automatically sync presence and calculation for active mentees
        $this->syncMonthlyDisbursements($selectedMonth, $companyId);

        $query = InternshipStipendDisbursement::with(['user.candidateProfile', 'user.internshipPeriod', 'mentor', 'verifier'])
            ->where('period_month', $selectedMonth);

        if ($companyId) {
            $query->where(function ($q) use ($companyId) {
                $q->where('company_id', $companyId)->orWhereNull('company_id');
            });
        }

        if ($selectedBatch) {
            $query->where('batch_name', $selectedBatch);
        }

        if ($selectedStatus) {
            if ($selectedStatus === 'pending_proposal') {
                $query->whereNull('mentor_submitted_at')->where('status', '!=', 'transferred');
            } elseif ($selectedStatus === 'submitted') {
                $query->whereNotNull('mentor_submitted_at');
            } else {
                $query->where('status', $selectedStatus);
            }
        }

        if ($selectedBankStatus) {
            if ($selectedBankStatus === 'unfilled') {
                $query->where(function($q) {
                    $q->whereNull('bank_account_number')->orWhere('bank_account_number', '');
                });
            } elseif ($selectedBankStatus === 'ktp_match') {
                $query->whereNotNull('bank_account_number')->where('bank_account_number', '!=', '')->where('is_ktp_matched', true);
            } elseif ($selectedBankStatus === 'ktp_unmatch') {
                $query->whereNotNull('bank_account_number')->where('bank_account_number', '!=', '')->where('is_ktp_matched', false);
            }
        }

        if ($selectedAttendance) {
            if ($selectedAttendance === 'unexcused' || $selectedAttendance === 'alpha') {
                $query->where('unexcused_days', '>', 0);
            } elseif ($selectedAttendance === 'excused' || $selectedAttendance === 'izin_sakit') {
                $query->where('excused_days', '>', 0);
            } elseif ($selectedAttendance === 'excess_excused') {
                $query->where('excused_days', '>', 4);
            } elseif ($selectedAttendance === 'has_deduction') {
                $query->where('deduction_amount', '>', 0);
            } elseif ($selectedAttendance === 'full_attendance' || $selectedAttendance === 'perfect') {
                $query->where('deduction_amount', 0)->where('unexcused_days', 0);
            }
        }

        if ($search) {
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('bank_account_number', 'like', "%{$search}%")
              ->orWhere('bank_account_holder', 'like', "%{$search}%");
        }

        $stipends = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Summary Statistics for Current View
        $statsBaseQuery = InternshipStipendDisbursement::where('period_month', $selectedMonth);
        if ($companyId) {
            $statsBaseQuery->where(function ($q) use ($companyId) {
                $q->where('company_id', $companyId)->orWhereNull('company_id');
            });
        }
        if ($selectedBatch) {
            $statsBaseQuery->where('batch_name', $selectedBatch);
        }

        $totalRecipients = (clone $statsBaseQuery)->count();
        $pendingProposalCount = (clone $statsBaseQuery)->whereNull('mentor_submitted_at')->where('status', '!=', 'transferred')->count();
        $submittedProposalCount = (clone $statsBaseQuery)->whereNotNull('mentor_submitted_at')->count();
        $totalEstimatedStipend = (clone $statsBaseQuery)->sum('net_amount');
        $transferredCount = (clone $statsBaseQuery)->where('status', 'transferred')->count();

        $batches = InternshipBatch::getActiveBatches($companyId);

        // Available Months Selector
        $availableMonths = [];
        $cursorMonth = Carbon::now()->subMonths(3)->startOfMonth();
        for ($i = 0; $i < 8; $i++) {
            $mKey = $cursorMonth->format('Y-m');
            $availableMonths[$mKey] = $cursorMonth->translatedFormat('F Y');
            $cursorMonth->addMonth();
        }

        return view('mentor.stipends.index', compact(
            'stipends',
            'batches',
            'selectedBatch',
            'selectedMonth',
            'selectedStatus',
            'selectedBankStatus',
            'selectedAttendance',
            'search',
            'totalRecipients',
            'pendingProposalCount',
            'submittedProposalCount',
            'totalEstimatedStipend',
            'transferredCount',
            'availableMonths'
        ));
    }

    public function submitRecommendation(Request $request, $id)
    {
        $request->validate([
            'mentor_notes' => 'nullable|string|max:1000',
        ]);

        $stipend = InternshipStipendDisbursement::with('user')->findOrFail($id);
        $mentor = auth()->user();

        $stipend->update([
            'mentor_id' => $mentor->id,
            'mentor_notes' => $request->mentor_notes ?? 'Presensi dan kinerja bulanan telah direview dan direkomendasikan pencairannya oleh Mentor.',
            'mentor_submitted_at' => now(),
            'status' => in_array($stipend->status, ['ready', 'transferred']) ? $stipend->status : 'in_review',
        ]);

        // Audit Log
        AuditLog::record(
            'MENTOR_SUBMIT_STIPEND',
            "Mentor {$mentor->name} mengajukan rekomendasi uang saku periode {$stipend->period_label} untuk peserta {$stipend->user?->name} sebesar {$stipend->formatted_net_amount}.",
            $mentor
        );

        // Notify Candidate
        UserNotification::send(
            $stipend->user_id,
            '💼 Rekomendasi Uang Saku Diajukan!',
            "Mentor {$mentor->name} telah mereview presensi dan mengajukan pencairan uang saku Anda untuk periode {$stipend->period_label} ({$stipend->formatted_net_amount}) ke HR/Finance.",
            route('candidate.logbook.progress'),
            'info'
        );

        // Notify HR & Super Admin
        $hrUsers = User::role(['HR', 'Super Admin'])->get();
        foreach ($hrUsers as $hr) {
            UserNotification::send(
                $hr->id,
                '📥 Rekomendasi Uang Saku Masuk',
                "Mentor {$mentor->name} telah mengajukan rekomendasi uang saku untuk {$stipend->user?->name} periode {$stipend->period_label} ({$stipend->formatted_net_amount}). Silakan verifikasi pencairan.",
                route('admin.internship-stipends.index', ['month' => $stipend->period_month]),
                'info'
            );
        }

        return redirect()->back()->with('success', "Rekomendasi pencairan uang saku untuk {$stipend->user?->name} ({$stipend->period_label}) berhasil diajukan ke HR / Finance.");
    }

    public function bulkSubmitRecommendation(Request $request)
    {
        $request->validate([
            'stipend_ids' => 'required|array|min:1',
            'stipend_ids.*' => 'exists:internship_stipend_disbursements,id',
            'default_notes' => 'nullable|string|max:1000',
        ]);

        $mentor = auth()->user();
        $stipends = InternshipStipendDisbursement::with('user')->whereIn('id', $request->stipend_ids)->get();

        $count = 0;
        foreach ($stipends as $stipend) {
            $notes = $request->default_notes ?: ($stipend->mentor_notes ?: 'Presensi dan laporan bulanan telah direview dan disetujui untuk diproses payroll oleh Mentor.');
            
            $stipend->update([
                'mentor_id' => $mentor->id,
                'mentor_notes' => $notes,
                'mentor_submitted_at' => now(),
                'status' => in_array($stipend->status, ['ready', 'transferred']) ? $stipend->status : 'in_review',
            ]);

            UserNotification::send(
                $stipend->user_id,
                '💼 Rekomendasi Uang Saku Diajukan!',
                "Mentor {$mentor->name} telah mereview presensi dan mengajukan pencairan uang saku Anda untuk periode {$stipend->period_label} ({$stipend->formatted_net_amount}) ke HR/Finance.",
                route('candidate.logbook.progress'),
                'info'
            );

            $count++;
        }

        // Notify HR & Super Admin
        $hrUsers = User::role(['HR', 'Super Admin'])->get();
        foreach ($hrUsers as $hr) {
            UserNotification::send(
                $hr->id,
                '📥 Pengajuan Uang Saku Massal Masuk',
                "Mentor {$mentor->name} telah mengajukan rekomendasi uang saku magang untuk {$count} peserta. Silakan verifikasi dan proses transfer.",
                route('admin.internship-stipends.index'),
                'info'
            );
        }

        AuditLog::record(
            'MENTOR_BULK_SUBMIT_STIPEND',
            "Mentor {$mentor->name} mengajukan rekomendasi uang saku massal untuk {$count} peserta magang.",
            $mentor
        );

        return redirect()->back()->with('success', "Berhasil mengajukan rekomendasi uang saku untuk {$count} peserta magang ke HR / Finance.");
    }

    /**
     * Send reminder notification to candidate who hasn't filled bank details.
     */
    public function sendBankReminder($id)
    {
        $stipend = InternshipStipendDisbursement::with('user')->findOrFail($id);
        $mentor = auth()->user();

        // 1. Send In-App Bell Notification
        UserNotification::send(
            $stipend->user_id,
            '⚠️ Pengingat: Lengkapi Nomor Rekening Bank',
            "Halo {$stipend->user?->name}, uang saku magang periode {$stipend->period_label} sedang diproses. Mohon segera lengkapi nomor rekening bank & buku tabungan Anda di formulir Onboarding agar pencairan uang saku tidak tertunda.",
            route('candidate.logbook.progress'),
            'warning'
        );

        // 2. Dispatch Email Notification to Candidate
        if ($stipend->user && $stipend->user->email) {
            try {
                Mail::to($stipend->user->email)->send(new StipendBankReminderMail($stipend, $mentor->name, 'Mentor Bimbingan'));
            } catch (\Throwable $e) {
                \Log::warning("Gagal mengirim email pengingat rekening ke {$stipend->user->email}: " . $e->getMessage());
            }
        }

        AuditLog::record(
            'STIPEND_BANK_REMINDER_SENT',
            "Mentor {$mentor->name} mengirimkan pengingat notifikasi & email kelengkapan rekening bank ke peserta {$stipend->user?->name}.",
            $mentor
        );

        return redirect()->back()->with('success', "Pengingat notifikasi & email pengisian rekening berhasil dikirimkan ke {$stipend->user?->name}.");
    }

    public function downloadStipendSlip($id)
    {
        $user = auth()->user();
        $companyId = $user->companyProfile ? $user->companyProfile->id : null;

        $stipendQuery = InternshipStipendDisbursement::with(['user.candidateProfile', 'company', 'verifier']);
        if ($companyId) {
            $stipendQuery->where(function ($q) use ($companyId) {
                $q->where('company_id', $companyId)->orWhereNull('company_id');
            });
        }

        $stipend = $stipendQuery->findOrFail($id);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.internship_stipend_slip', [
            'stipend' => $stipend,
            'user' => $stipend->user
        ])->setPaper('a4', 'portrait');

        $filename = 'Slip_Uang_Saku_' . \Illuminate\Support\Str::slug($stipend->user?->name ?? 'Peserta') . '_' . str_replace('-', '_', $stipend->period_month) . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Auto-sync presence, deduction & bank details for active interns.
     */
    protected function syncMonthlyDisbursements(string $periodMonth, ?int $companyId = null): void
    {
        $monthDate = Carbon::parse($periodMonth . '-01');
        $startOfMonth = $monthDate->copy()->startOfMonth();
        $endOfMonth = $monthDate->copy()->endOfMonth();
        $periodLabel = $monthDate->translatedFormat('F Y');

        $interns = User::role('Candidate')
            ->whereDoesntHave('internshipResignations', function($q) {
                $q->where('status', 'approved');
            })
            ->whereDoesntHave('employeeTerminations')
            ->with(['internshipPeriod', 'candidateProfile', 'applications.job', 'applications.agreements'])
            ->get();

        foreach ($interns as $intern) {
            $latestApp = $intern->applications()->latest()->first();
            $jobCompanyId = $latestApp?->job?->company_profile_id ?? 1;

            if ($companyId && $jobCompanyId != $companyId) {
                continue;
            }

            $batchName = $intern->internshipPeriod?->period_name ?? $latestApp?->job?->batch ?? 'Batch 1 - 2026';

            // Get bank onboarding data (null if not yet filled by candidate)
            $onboarding = CandidateOnboarding::where('user_id', $intern->id)->first();
            $bankName = $onboarding?->bank_name ?: null;
            $accountNumber = $onboarding?->bank_account_number ?: null;
            $accountHolder = $onboarding?->bank_account_holder ?: null;
            $bankBookDoc = $onboarding?->bank_book_doc_path ?: null;

            // Check KTP Name match only when bank account is filled
            $isKtpMatched = false;
            if ($accountHolder && $intern->name) {
                $cleanHolder = strtolower(preg_replace('/[^a-z0-9]/', '', $accountHolder));
                $cleanName = strtolower(preg_replace('/[^a-z0-9]/', '', $intern->name));
                $isKtpMatched = str_contains($cleanHolder, $cleanName) || str_contains($cleanName, $cleanHolder) || levenshtein($cleanHolder, $cleanName) <= 3;
            }

            // Agreement Stipend Base
            $agreement = ApplicationAgreement::where('user_id', $intern->id)->latest()->first();
            $baseNominal = 2800000;
            if ($agreement && preg_match('/[\d\.]+/', str_replace(['Rp', '.', ' '], '', $agreement->stipend_or_salary), $matches)) {
                $parsed = (int) str_replace('.', '', $matches[0]);
                if ($parsed > 100000) {
                    $baseNominal = $parsed;
                }
            }

            // Calculate attendance & deduction metrics via AttendanceReminderService
            $metrics = \App\Services\AttendanceReminderService::calculateMonthlyAttendanceMetrics($intern->id, $periodMonth, $jobCompanyId, $baseNominal);

            // Do not override if already marked as transferred
            $existing = InternshipStipendDisbursement::where('user_id', $intern->id)
                ->where('period_month', $periodMonth)
                ->first();

            if ($existing && $existing->status === 'transferred') {
                continue;
            }

            $status = $existing?->status ?? 'in_review';

            InternshipStipendDisbursement::updateOrCreate(
                [
                    'user_id' => $intern->id,
                    'period_month' => $periodMonth,
                ],
                [
                    'company_id' => $jobCompanyId,
                    'period_label' => $periodLabel,
                    'batch_name' => $batchName,
                    'bank_name' => $bankName,
                    'bank_account_number' => $accountNumber,
                    'bank_account_holder' => $accountHolder,
                    'bank_book_doc_path' => $bankBookDoc,
                    'is_ktp_matched' => $isKtpMatched,
                    'present_days' => $metrics['present_days'],
                    'excused_days' => $metrics['excused_days'],
                    'unexcused_days' => $metrics['unexcused_days'],
                    'total_working_days' => $metrics['total_working_days'],
                    'base_nominal' => $metrics['base_nominal'],
                    'deduction_amount' => $metrics['deduction_amount'],
                    'net_amount' => $metrics['net_amount'],
                    'status' => $status,
                ]
            );
        }
    }
}
