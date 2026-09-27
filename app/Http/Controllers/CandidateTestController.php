<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobTest;
use App\Models\CandidateTestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CandidateTestController extends Controller
{
    /**
     * Display the test taking interface for candidate.
     */
    public function show(Job $job)
    {
        $test = $job->test()->with('questions')->first();

        if (!$test || !$test->is_active) {
            return redirect()->route('jobs.show', $job)->with('error', 'Lowongan ini tidak memerlukan tes online saat ini.');
        }

        if ($test->test_mode === 'internal' && $test->questions->count() === 0) {
            return redirect()->route('jobs.show', $job)->with('error', 'Lowongan ini belum memiliki bank soal tes aktif.');
        }

        $user = Auth::user();

        // Check if candidate already completed this test
        $existingResult = CandidateTestResult::where('user_id', $user->id)
            ->where('job_id', $job->id)
            ->where('job_test_id', $test->id)
            ->first();

        if ($existingResult) {
            return view('candidate.tests.result', compact('job', 'test', 'existingResult'));
        }

        // Check Schedule Access Window (Starts At & Deadline At)
        if ($test->isUpcoming()) {
            return view('candidate.tests.schedule_locked', ['job' => $job, 'test' => $test, 'status' => 'upcoming']);
        }

        if ($test->isExpired()) {
            return view('candidate.tests.schedule_locked', ['job' => $job, 'test' => $test, 'status' => 'expired']);
        }

        // Check Access Token Verification (if application has test_token assigned)
        $application = \App\Models\Application::where('user_id', $user->id)->where('job_id', $job->id)->first();
        if ($application && $application->test_token) {
            $verifiedToken = session('verified_test_token_' . $job->id);
            if ($verifiedToken !== $application->test_token) {
                return view('candidate.tests.verify_token', compact('job', 'test', 'application'));
            }
        }

        if ($test->test_mode === 'external') {
            return view('candidate.tests.external', compact('job', 'test'));
        }

        // Randomize questions order per candidate session to ensure fairness & anti-cheat without reshuffling on accidental refresh
        $sessionKey = 'test_question_order_' . $user->id . '_' . $test->id;
        if (!session()->has($sessionKey)) {
            $questionIds = $test->questions->pluck('id')->shuffle()->toArray();
            session([$sessionKey => $questionIds]);
        } else {
            $questionIds = session($sessionKey);
        }

        $questions = $test->questions->whereIn('id', $questionIds)->sortBy(function ($q) use ($questionIds) {
            return array_search($q->id, $questionIds);
        })->values();

        return view('candidate.tests.take', compact('job', 'test', 'questions'));
    }

    /**
     * Verify candidate test access token.
     */
    public function verifyToken(Request $request, Job $job)
    {
        $request->validate([
            'token' => 'required|string|max:30',
        ]);

        $user = Auth::user();
        $application = \App\Models\Application::where('user_id', $user->id)->where('job_id', $job->id)->firstOrFail();

        $inputToken = strtoupper(trim($request->token));
        $expectedToken = strtoupper(trim($application->test_token));

        if ($inputToken !== $expectedToken) {
            return back()->with('error', 'Kode Token Akses tidak valid. Silakan periksa kembali email atau notifikasi akun Anda.');
        }

        session(['verified_test_token_' . $job->id => $application->test_token]);

        return redirect()->route('candidate.tests.show', $job)->with('success', 'Token berhasil diverifikasi! Selamat mengerjakan ujian.');
    }

    /**
     * Process test submission for internal multiple choice exam.
     */
    public function submit(Request $request, Job $job)
    {
        $test = $job->test()->with('questions')->firstOrFail();

        // Prevent submission outside schedule window
        if ($test->isUpcoming()) {
            return redirect()->route('candidate.tests.show', $job)->with('error', 'Sesi ujian belum dibuka.');
        }

        if ($test->isExpired()) {
            return redirect()->route('candidate.tests.show', $job)->with('error', 'Batas waktu pengerjaan ujian telah berakhir.');
        }

        $user = Auth::user();

        $answers = $request->input('answers', []);
        $totalQuestions = $test->questions->count();
        $correctCount = 0;

        foreach ($test->questions as $question) {
            $userAns = strtolower($answers[$question->id] ?? '');
            if ($userAns === strtolower($question->correct_option)) {
                $correctCount++;
            }
        }

        $score = $totalQuestions > 0 ? (int) round(($correctCount / $totalQuestions) * 100) : 0;
        $passed = $score >= $test->passing_score;

        $answerFilePath = null;
        if ($request->hasFile('answer_pdf')) {
            $request->validate([
                'answer_pdf' => 'nullable|file|mimes:pdf|max:15360',
            ]);
            $userFolder = 'test_answers/' . \Illuminate\Support\Str::slug($user->name) . '_' . $user->id;
            $answerFilePath = $request->file('answer_pdf')->store($userFolder, 'public');
        }

        $result = CandidateTestResult::updateOrCreate(
            [
                'user_id' => $user->id,
                'job_id' => $job->id,
                'job_test_id' => $test->id,
            ],
            [
                'score' => $score,
                'passed' => $passed,
                'answer_file_path' => $answerFilePath,
                'project_url' => $request->input('project_url'),
                'answers' => $answers,
                'completed_at' => now(),
            ]
        );

        // Clear session randomized question order
        session()->forget('test_question_order_' . $user->id . '_' . $test->id);

        // Auto-advance application status if candidate passes and currently in 'test' stage
        $application = \App\Models\Application::where('user_id', $user->id)->where('job_id', $job->id)->first();
        if ($application) {
            $currentStatusStr = is_object($application->status) ? $application->status->value : (string) $application->status;
            if ($currentStatusStr === 'test' && $passed) {
                $application->update(['status' => \App\Enums\ApplicationStatus::INTERVIEW]);
            }
        }

        // Send In-App Notifications to Candidate
        if ($passed) {
            \App\Models\UserNotification::send(
                $user->id,
                "🎉 Selamat! Lolos Tes Online",
                "Anda berhasil menyelesaikan tes online untuk {$job->title} dengan skor {$score}% (KKM: {$test->passing_score}%). Status lamaran Anda telah otomatis ditingkatkan ke tahap Wawancara (Interview).",
                route('candidate.tests.show', $job),
                'success'
            );
        } else {
            \App\Models\UserNotification::send(
                $user->id,
                "❌ Hasil Tes Online Belum Memenuhi KKM",
                "Tes online untuk {$job->title} telah selesai. Skor Anda {$score}% (KKM minimal: {$test->passing_score}%).",
                route('candidate.tests.show', $job),
                'warning'
            );
        }

        // Notify Job Owner / HR Recruiter if available
        if ($job->company_user_id) {
            \App\Models\UserNotification::send(
                $job->company_user_id,
                "📊 Hasil Tes Kandidat Masuk: {$user->name}",
                "Kandidat {$user->name} telah menyelesaikan tes {$test->title} untuk lowongan {$job->title} dengan skor {$score}% (" . ($passed ? 'LULUS' : 'TIDAK LULUS') . ").",
                route('admin.jobs.show', $job->id),
                'info'
            );
        }

        \App\Models\AuditLog::record('test_completed', "Kandidat {$user->name} menyelesaikan tes online {$job->title} (Skor: {$score}%, Status: " . ($passed ? 'Lolos' : 'Gagal') . ")");

        return redirect()->route('candidate.tests.show', $job)
            ->with('success', 'Tes Online Berhasil Diselesaikan!');
    }

    /**
     * Submit external test completion confirmation.
     */
    public function submitExternal(Request $request, Job $job)
    {
        $test = $job->test()->firstOrFail();
        $user = Auth::user();

        $answerFilePath = null;
        if ($request->hasFile('answer_pdf')) {
            $request->validate([
                'answer_pdf' => 'nullable|file|mimes:pdf|max:15360',
            ]);
            $userFolder = 'test_answers/' . \Illuminate\Support\Str::slug($user->name) . '_' . $user->id;
            $answerFilePath = $request->file('answer_pdf')->store($userFolder, 'public');
        }

        $result = CandidateTestResult::updateOrCreate(
            [
                'user_id' => $user->id,
                'job_id' => $job->id,
                'job_test_id' => $test->id,
            ],
            [
                'score' => 100,
                'passed' => true,
                'answer_file_path' => $answerFilePath,
                'project_url' => $request->input('project_url'),
                'answers' => ['external_confirmation' => true, 'submitted_at' => now()->toDateTimeString()],
                'completed_at' => now(),
            ]
        );

        \App\Models\UserNotification::send(
            $user->id,
            "✅ Tes Psikotes Eksternal Dikonfirmasi",
            "Terima kasih telah mengerjakan Tes Psikotes Eksternal untuk {$job->title}. Tim HR akan memeriksa hasil pengerjaan Anda.",
            route('candidate.tests.show', $job),
            'success'
        );

        return redirect()->route('candidate.tests.show', $job)
            ->with('success', 'Konfirmasi Pengerjaan Tes Psikotes Eksternal Berhasil Disimpan!');
    }
}
