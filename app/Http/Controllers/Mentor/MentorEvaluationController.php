<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\InternshipEvaluation;
use App\Models\User;
use Illuminate\Http\Request;

class MentorEvaluationController extends Controller
{
    public function create($internId)
    {
        $intern = User::with('candidateProfile')->findOrFail($internId);
        
        $evaluation = InternshipEvaluation::firstOrNew([
            'user_id' => $intern->id,
        ]);

        return view('mentor.evaluations.create', compact('intern', 'evaluation'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'discipline_score' => 'required|integer|min:0|max:100',
            'initiative_score' => 'required|integer|min:0|max:100',
            'work_quality_score' => 'required|integer|min:0|max:100',
            'teamwork_score' => 'required|integer|min:0|max:100',
            'problem_solving_score' => 'required|integer|min:0|max:100',
            'feedback_summary' => 'required|string|min:10',
            'recommendation' => 'required|string',
        ]);

        $finalScore = InternshipEvaluation::computeFinalScore(
            $request->discipline_score,
            $request->initiative_score,
            $request->work_quality_score,
            $request->teamwork_score,
            $request->problem_solving_score
        );

        $finalGrade = InternshipEvaluation::computeGrade($finalScore);

        $evaluation = InternshipEvaluation::updateOrCreate(
            [
                'user_id' => $request->user_id,
            ],
            [
                'mentor_id' => auth()->id(),
                'discipline_score' => $request->discipline_score,
                'initiative_score' => $request->initiative_score,
                'work_quality_score' => $request->work_quality_score,
                'teamwork_score' => $request->teamwork_score,
                'problem_solving_score' => $request->problem_solving_score,
                'final_score' => $finalScore,
                'final_grade' => $finalGrade,
                'feedback_summary' => $request->feedback_summary,
                'recommendation' => $request->recommendation,
                'evaluated_at' => now(),
            ]
        );

        // Auto-generate & synchronize official digital Certificate and Academic Transcript with QR code
        \App\Services\CertificateGenerationService::generateOrUpdateForIntern($evaluation->intern, $evaluation);

        return redirect()->route('mentor.dashboard')
            ->with('success', 'Evaluasi Kinerja Akhir milik ' . $evaluation->intern->name . ' berhasil disimpan dan E-Sertifikat serta Transkrip Nilai resmi telah otomatis diterbitkan!');
    }
}
