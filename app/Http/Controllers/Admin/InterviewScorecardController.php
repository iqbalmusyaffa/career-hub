<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\InterviewScorecard;
use Illuminate\Http\Request;

class InterviewScorecardController extends Controller
{
    public function store(Request $request, Application $application)
    {
        $request->validate([
            'technical_score' => 'required|integer|min:1|max:5',
            'communication_score' => 'required|integer|min:1|max:5',
            'problem_solving_score' => 'required|integer|min:1|max:5',
            'culture_score' => 'required|integer|min:1|max:5',
            'recommendation' => 'required|in:strong_hire,hire,hold,no_hire',
            'notes' => 'nullable|string',
        ]);

        $avg = round((
            $request->technical_score +
            $request->communication_score +
            $request->problem_solving_score +
            $request->culture_score
        ) / 4, 1);

        InterviewScorecard::updateOrCreate(
            [
                'application_id' => $application->id,
                'interviewer_id' => auth()->id(),
            ],
            [
                'technical_score' => $request->technical_score,
                'communication_score' => $request->communication_score,
                'problem_solving_score' => $request->problem_solving_score,
                'culture_score' => $request->culture_score,
                'average_score' => $avg,
                'recommendation' => $request->recommendation,
                'notes' => $request->notes,
            ]
        );

        return back()->with('success', 'Skor Penilaian Interview Scorecard berhasil disimpan!');
    }

    public function destroy(InterviewScorecard $scorecard)
    {
        $scorecard->delete();
        return back()->with('success', 'Penilaian interview berhasil dihapus.');
    }
}
