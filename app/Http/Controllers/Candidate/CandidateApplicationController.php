<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CandidateApplicationController extends Controller
{
    /**
     * Display a listing of the candidate's applications.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Application::with([
            'job.companyProfile',
            'interview',
            'offerLetter',
            'testResult',
            'agreements',
            'messages',
        ])
        ->where('user_id', $user->id);

        // Search filter
        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->whereHas('job', function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('company_name', 'LIKE', "%{$search}%")
                  ->orWhere('location', 'LIKE', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status') && $request->status !== 'all') {
            $filterStatus = $request->status;
            if ($filterStatus === 'interview') {
                $query->whereIn('status', ['interview', 'interview_hr', 'interview_user']);
            } elseif ($filterStatus === 'review') {
                $query->whereIn('status', ['pending', 'screening', 'test', 'background_check']);
            } elseif ($filterStatus === 'accepted') {
                $query->whereIn('status', ['accepted', 'hired', 'offered']);
            } elseif ($filterStatus === 'rejected') {
                $query->where('status', 'rejected');
            } else {
                $query->where('status', $filterStatus);
            }
        }

        $allApplications = Application::where('user_id', $user->id)->get();
        $totalCount = $allApplications->count();
        $reviewCount = $allApplications->whereIn('status', ['pending', 'screening', 'test', 'background_check'])->count();
        $interviewCount = $allApplications->whereIn('status', ['interview', 'interview_hr', 'interview_user'])->count();
        $acceptedCount = $allApplications->whereIn('status', ['accepted', 'hired', 'offered'])->count();
        $rejectedCount = $allApplications->where('status', 'rejected')->count();

        $applications = $query->latest()->paginate(10)->withQueryString();

        return view('candidate.applications.index', compact(
            'applications',
            'totalCount',
            'reviewCount',
            'interviewCount',
            'acceptedCount',
            'rejectedCount'
        ));
    }

    /**
     * Display the specified application detail.
     */
    public function show($id)
    {
        $user = Auth::user();
        $application = Application::with([
            'job.companyProfile',
            'interview',
            'offerLetter',
            'testResult',
            'agreements',
            'messages.sender',
            'evaluations',
            'onboarding',
        ])
        ->where('user_id', $user->id)
        ->findOrFail($id);

        return view('candidate.applications.show', compact('application'));
    }
}
