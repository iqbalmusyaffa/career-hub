<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\CandidateEvaluation;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CandidateEvaluationController extends Controller
{
    /**
     * Store HR Evaluation and Rating for candidate application.
     */
    public function store(Request $request, $applicationId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'technical_score' => 'required|integer|min:0|max:100',
            'attitude_score' => 'required|integer|min:0|max:100',
            'communication_score' => 'required|integer|min:0|max:100',
            'recommendation' => 'required|string|in:hire,consider,reject',
            'comments' => 'nullable|string',
        ]);

        $application = Application::with('user')->findOrFail($applicationId);
        $user = Auth::user();

        CandidateEvaluation::create([
            'application_id' => $application->id,
            'evaluator_user_id' => $user->id,
            'rating' => $request->rating,
            'technical_score' => $request->technical_score,
            'attitude_score' => $request->attitude_score,
            'communication_score' => $request->communication_score,
            'comments' => $request->comments,
            'recommendation' => $request->recommendation,
        ]);

        AuditLog::record('evaluation_added', "HR {$user->name} memberikan evaluasi nilai kandidat {$application->user->name} (Rating: {$request->rating}/5 stars)");

        return back()->with('success', 'Hasil evaluasi & scoring kandidat berhasil disimpan!');
    }

    /**
     * Delete an evaluation record.
     */
    public function destroy($applicationId, $evaluationId)
    {
        $evaluation = CandidateEvaluation::where('application_id', $applicationId)->findOrFail($evaluationId);
        $evaluation->delete();

        return back()->with('success', 'Catatan evaluasi berhasil dihapus.');
    }
}
