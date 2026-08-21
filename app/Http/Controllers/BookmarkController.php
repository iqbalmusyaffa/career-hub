<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\SavedJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    public function index()
    {
        $savedJobs = Auth::user()->bookmarkedJobs()->latest('saved_jobs.created_at')->paginate(10);

        return view('candidate.saved_jobs', compact('savedJobs'));
    }

    public function toggle(Job $job)
    {
        $user = Auth::user();
        
        $existing = SavedJob::where('user_id', $user->id)
            ->where('job_id', $job->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $status = 'removed';
            $message = 'Lowongan berhasil dihapus dari simpanan.';
        } else {
            SavedJob::create([
                'user_id' => $user->id,
                'job_id' => $job->id,
            ]);
            $status = 'added';
            $message = 'Lowongan berhasil disimpan!';
        }

        if (request()->wantsJson()) {
            return response()->json([
                'status' => $status,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }
}
