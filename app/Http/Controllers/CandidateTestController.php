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
            return redirect()->route('jobs.show', $job->id)->with('error', 'Lowongan ini tidak memerlukan tes online saat ini.');
        }

        if ($test->test_mode === 'internal' && $test->questions->count() === 0) {
            return redirect()->route('jobs.show', $job->id)->with('error', 'Lowongan ini belum memiliki bank soal tes aktif.');
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

        if ($test->test_mode === 'external') {
            return view('candidate.tests.external', compact('job', 'test'));
        }

        return view('candidate.tests.take', compact('job', 'test'));
    }

    /**
     * Process test submission for internal multiple choice exam.
     */
    public function submit(Request $request, Job $job)
    {
        $test = $job->test()->with('questions')->firstOrFail();
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

        // Send In-App Notifications
        if ($passed) {
            \App\Models\UserNotification::send(
                $user->id,
                "🎉 Selamat! Lolos Tes Online",
                "Anda berhasil menyelesaikan tes online untuk {$job->title} dengan skor {$score}% (KKM: {$test->passing_score}%).",
                route('candidate.tests.show', $job->id),
                'success'
            );
        } else {
            \App\Models\UserNotification::send(
                $user->id,
                "❌ Hasil Tes Online Belum Memenuhi KKM",
                "Tes online untuk {$job->title} telah selesai. Skor Anda {$score}% (KKM minimal: {$test->passing_score}%).",
                route('candidate.tests.show', $job->id),
                'warning'
            );
        }

        \App\Models\AuditLog::record('test_completed', "Kandidat {$user->name} menyelesaikan tes online {$job->title} (Skor: {$score}%, Status: " . ($passed ? 'Lolos' : 'Gagal') . ")");

        return redirect()->route('candidate.tests.show', $job->id)
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
            route('candidate.tests.show', $job->id),
            'success'
        );

        return redirect()->route('candidate.tests.show', $job->id)
            ->with('success', 'Konfirmasi Pengerjaan Tes Psikotes Eksternal Berhasil Disimpan!');
    }
}
