<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobTest;
use App\Models\TestQuestion;
use App\Exports\QuestionsExport;
use App\Imports\QuestionsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class JobTestController extends Controller
{
    /**
     * Show test configuration and question editor for a job.
     */
    public function edit(Job $job)
    {
        $test = JobTest::with('questions')->firstOrCreate(
            ['job_id' => $job->id],
            [
                'title' => 'Tes Psikotes & Potensi Kerja: ' . $job->title,
                'category' => 'psikotes',
                'description' => 'Petunjuk: Pilihlah satu jawaban yang paling tepat untuk setiap soal penalaran logika, deret angka, dan analogi verbal berikut.',
                'duration_minutes' => 30,
                'passing_score' => 70,
                'is_active' => true,
            ]
        );

        // Auto-seed standard Psikotes questions if empty
        if ($test->questions->count() === 0) {
            $this->seedStandardPsikotesQuestions($test);
            $test->load('questions');
        }

        // Calculate live test analytics for HR / Owner
        $participants = \App\Models\CandidateTestResult::where('job_test_id', $test->id)->get();
        $totalParticipants = $participants->count();
        $passedCount = $participants->where('passed', true)->count();
        $failedCount = $participants->where('passed', false)->count();
        $avgScore = $totalParticipants > 0 ? (int) round($participants->avg('score')) : 0;
        $passRate = $totalParticipants > 0 ? (int) round(($passedCount / $totalParticipants) * 100) : 0;

        $analytics = [
            'total_participants' => $totalParticipants,
            'passed_count' => $passedCount,
            'failed_count' => $failedCount,
            'avg_score' => $avgScore,
            'pass_rate' => $passRate,
        ];

        // Count candidates currently in test stage
        $candidatesInTestCount = $job->applications()
            ->whereIn('status', ['test', \App\Enums\ApplicationStatus::TEST->value])
            ->count();

        return view('admin.jobs.test_editor', compact('job', 'test', 'analytics', 'candidatesInTestCount'));
    }

    /**
     * Save/update test settings and questions.
     */
    public function update(Request $request, Job $job)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'session_name' => 'nullable|string|max:100',
            'test_mode' => 'required|in:internal,external',
            'external_url' => 'nullable|url|required_if:test_mode,external',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1|max:180',
            'passing_score' => 'required|integer|min:10|max:100',
            'starts_at' => 'nullable|date',
            'deadline_at' => 'nullable|date|after_or_equal:starts_at',
            'questions' => 'nullable|array',
            'questions.*.question_text' => 'nullable|string',
            'questions.*.option_a' => 'nullable|string',
            'questions.*.option_b' => 'nullable|string',
            'questions.*.option_c' => 'nullable|string',
            'questions.*.option_d' => 'nullable|string',
            'questions.*.option_e' => 'nullable|string',
            'questions.*.correct_option' => 'nullable|in:a,b,c,d,e',
        ]);

        $test = JobTest::firstOrCreate(['job_id' => $job->id]);
        
        $filePath = $test->file_path;
        if ($request->hasFile('pdf_file')) {
            $request->validate([
                'pdf_file' => 'nullable|file|mimes:pdf|max:10240',
            ]);
            if ($filePath && \Illuminate\Support\Facades\Storage::disk('public')->exists($filePath)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($filePath);
            }
            $companyFolder = 'company_tests/' . \Illuminate\Support\Str::slug($job->company_name ?: 'company');
            $filePath = $request->file('pdf_file')->store($companyFolder, 'public');
        }

        $test->update([
            'title' => $request->title,
            'category' => $request->category,
            'session_name' => $request->filled('session_name') ? $request->session_name : null,
            'test_mode' => $request->test_mode ?? 'internal',
            'external_url' => $request->external_url,
            'file_path' => $filePath,
            'description' => $request->description,
            'duration_minutes' => $request->duration_minutes,
            'passing_score' => $request->passing_score,
            'starts_at' => $request->filled('starts_at') ? $request->starts_at : null,
            'deadline_at' => $request->filled('deadline_at') ? $request->deadline_at : null,
            'is_active' => $request->has('is_active'),
        ]);

        // Sync Questions
        $test->questions()->delete();

        if ($request->has('questions') && is_array($request->questions)) {
            foreach ($request->questions as $q) {
                if (!empty($q['question_text'])) {
                    $test->questions()->create([
                        'question_text' => $q['question_text'],
                        'option_a' => $q['option_a'],
                        'option_b' => $q['option_b'],
                        'option_c' => $q['option_c'],
                        'option_d' => $q['option_d'],
                        'option_e' => !empty($q['option_e']) ? $q['option_e'] : null,
                        'correct_option' => strtolower($q['correct_option'] ?? 'a'),
                        'points' => 10,
                    ]);
                }
            }
        }

        // Auto-notify all candidates in 'test' stage if test is active and notify checkbox is enabled
        $notifiedCount = 0;
        $shouldNotify = $request->input('notify_candidates', '1') === '1' && $test->is_active;

        if ($shouldNotify) {
            $testApplications = \App\Models\Application::with(['user', 'job'])
                ->where('job_id', $job->id)
                ->whereIn('status', ['test', \App\Enums\ApplicationStatus::TEST->value])
                ->get();

            foreach ($testApplications as $app) {
                $hasCompleted = \App\Models\CandidateTestResult::where('user_id', $app->user_id)
                    ->where('job_id', $job->id)
                    ->where('job_test_id', $test->id)
                    ->exists();

                if (!$hasCompleted) {
                    // Generate token if not yet assigned
                    if (empty($app->test_token)) {
                        $app->test_token = 'TK-' . strtoupper(\Illuminate\Support\Str::random(6));
                        $app->save();
                    }

                    // Format message
                    $scheduleSummary = "Token Akses Ujian Anda: {$app->test_token}.";
                    if ($test->starts_at) {
                        $scheduleSummary .= " Mulai: " . $test->starts_at->format('d/m/Y H:i') . " WIB.";
                    }
                    if ($test->deadline_at) {
                        $scheduleSummary .= " Batas Akhir: " . $test->deadline_at->format('d/m/Y H:i') . " WIB.";
                    }
                    $scheduleSummary .= " Silakan periksa email atau klik notifikasi ini untuk memulai ujian.";

                    // In-app Notification
                    \App\Models\UserNotification::send(
                        $app->user_id,
                        '📝 Jadwal & Token Ujian Online: ' . ($job->title ?? 'Pekerjaan'),
                        $scheduleSummary,
                        route('candidate.tests.show', $job->id),
                        'info'
                    );

                    // Email dispatch
                    if ($app->user && $app->user->email) {
                        try {
                            \Illuminate\Support\Facades\Mail::to($app->user->email)
                                ->send(new \App\Mail\CandidateTestInvitationMail($app, $test, $app->test_token));
                            $notifiedCount++;
                        } catch (\Throwable $e) {
                            \Illuminate\Support\Facades\Log::error("Failed to send test invitation to {$app->user->email}: " . $e->getMessage());
                        }
                    }
                }
            }
        }

        $message = 'Pengaturan Tes & Bank Soal berhasil disimpan!';
        if ($notifiedCount > 0) {
            $message .= " Kode token dan rangkuman jadwal ujian otomatis dikirimkan ke {$notifiedCount} email kandidat di tahap tes.";
        }

        return back()->with('success', $message);
    }

    /**
     * Export existing questions or template using Maatwebsite Excel (Supports Excel .xlsx & .csv).
     */
    public function export(Job $job, Request $request)
    {
        $format = strtolower($request->query('format', 'xlsx'));
        $cleanTitle = \Illuminate\Support\Str::slug($job->title) ?: 'soal';

        if ($format === 'csv') {
            $fileName = 'bank_soal_' . $cleanTitle . '.csv';
            return Excel::download(new QuestionsExport($job->id), $fileName, \Maatwebsite\Excel\Excel::CSV);
        }

        $fileName = 'bank_soal_' . $cleanTitle . '.xlsx';
        return Excel::download(new QuestionsExport($job->id), $fileName, \Maatwebsite\Excel\Excel::XLSX);
    }

    /**
     * Import questions from uploaded Excel or CSV file using Maatwebsite Excel.
     */
    public function import(Request $request, Job $job)
    {
        $request->validate([
            'file' => 'nullable|file|mimes:xlsx,xls,csv,txt|max:10240',
            'csv_file' => 'nullable|file|max:10240',
        ]);

        $uploadedFile = $request->file('file') ?? $request->file('csv_file');

        if (!$uploadedFile) {
            return back()->with('error', 'Silakan pilih berkas Excel (.xlsx/.xls) atau CSV yang valid.');
        }

        $test = JobTest::firstOrCreate(['job_id' => $job->id]);

        try {
            // If replace mode checked, remove old questions first
            if ($request->has('replace_existing') && $request->replace_existing) {
                $test->questions()->delete();
            }

            Excel::import(new QuestionsImport($test->id), $uploadedFile);
            $newCount = $test->questions()->count();
            return back()->with('success', "Berhasil mengimpor soal! Saat ini terdapat total {$newCount} butir soal terdaftar.");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimpor file: ' . $e->getMessage());
        }
    }

    /**
     * Seed standard Psikotes questions.
     */
    private function seedStandardPsikotesQuestions(JobTest $test)
    {
        $questions = [
            [
                'question_text' => 'Deret Angka: 2, 4, 8, 16, 32, ... Berapakah angka selanjutnya?',
                'option_a' => '48',
                'option_b' => '64',
                'option_c' => '50',
                'option_d' => '60',
                'correct_option' => 'b',
            ],
            [
                'question_text' => 'Analogi Kata: KUCING : MEOONG = ANJING : ...',
                'option_a' => 'GONGGONG',
                'option_b' => 'RINGKIK',
                'option_c' => 'LENGUH',
                'option_d' => 'KICAU',
                'correct_option' => 'a',
            ],
            [
                'question_text' => 'Deret Angka: 100, 95, 85, 70, 50, ... Berapakah angka selanjutnya?',
                'option_a' => '30',
                'option_b' => '25',
                'option_c' => '20',
                'option_d' => '35',
                'correct_option' => 'b',
            ],
            [
                'question_text' => 'Penalaran Logika: Semua karyawan PT Merdeka hadir tepat waktu. Budi adalah karyawan PT Merdeka. Kesimpulan yang tepat adalah...',
                'option_a' => 'Budi mungkin terlambat',
                'option_b' => 'Budi hadir tepat waktu',
                'option_c' => 'Budi adalah manajer',
                'option_d' => 'Budi tidak hadir',
                'correct_option' => 'b',
            ],
            [
                'question_text' => 'Sinonim Kata: KREDIBEL sama artinya dengan...',
                'option_a' => 'Dapat dipercaya',
                'option_b' => 'Penuh kecurangan',
                'option_c' => 'Sangat meragukan',
                'option_d' => 'Sangat mahal',
                'correct_option' => 'a',
            ],
        ];

        foreach ($questions as $q) {
            $test->questions()->create($q);
        }
    }

    /**
     * Manually send test reminder email & notifications to candidates who haven't completed the test.
     */
    public function sendReminders(Job $job)
    {
        $test = $job->test;
        if (!$test || !$test->is_active) {
            return back()->with('error', 'Ujian pada lowongan ini tidak aktif.');
        }

        $applications = \App\Models\Application::with(['user', 'job'])
            ->where('job_id', $job->id)
            ->where('status', \App\Enums\ApplicationStatus::TEST)
            ->get();

        if ($applications->isEmpty()) {
            return back()->with('error', 'Tidak ada pelamar dalam status tes untuk lowongan ini.');
        }

        $sentCount = 0;
        foreach ($applications as $app) {
            $hasCompleted = \App\Models\CandidateTestResult::where('user_id', $app->user_id)
                ->where('job_id', $job->id)
                ->where('job_test_id', $test->id)
                ->exists();

            if (!$hasCompleted && $app->user && $app->user->email) {
                try {
                    \Illuminate\Support\Facades\Mail::to($app->user->email)->send(
                        new \App\Mail\CandidateTestReminderMail($app, $test)
                    );

                    \App\Models\UserNotification::send(
                        $app->user_id,
                        "⏰ Pengingat: Segera Selesaikan Ujian Online",
                        "Anda belum menyelesaikan tes online {$test->title} untuk posisi {$job->title}. Silakan segera kerjakan sebelum batas waktu berakhir.",
                        route('candidate.tests.show', $job->id),
                        'warning'
                    );

                    $sentCount++;
                } catch (\Exception $e) {
                    // Log error if needed
                }
            }
        }

        \App\Models\AuditLog::record('test_reminders_manual_sent', "HR mengirim {$sentCount} pengingat manual untuk ujian lowongan {$job->title}.");

        return back()->with('success', "Berhasil mengirim {$sentCount} email & notifikasi pengingat ujian kepada kandidat yang belum menyelesaikan tes.");
    }
}
