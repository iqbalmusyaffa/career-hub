<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Interview;
use App\Models\Job;
use App\Models\OfferLetter;

class RecruitmentCalendarController extends Controller
{
    /**
     * Display Recruitment Calendar page.
     */
    public function index()
    {
        return view('admin.calendar.index');
    }

    /**
     * JSON API Endpoint returning FullCalendar events.
     */
    public function events()
    {
        $events = [];

        // 1. Interview Events
        $interviews = Interview::with(['application.user', 'application.job'])->get();
        foreach ($interviews as $interview) {
            if ($interview->scheduled_at) {
                $candidateName = $interview->application->user->name ?? 'Kandidat';
                $jobTitle = $interview->application->job->title ?? 'Pekerjaan';
                
                $events[] = [
                    'id' => 'interview_' . $interview->id,
                    'title' => "📌 Wawancara: {$candidateName} ({$jobTitle})",
                    'start' => $interview->scheduled_at->toIso8601String(),
                    'backgroundColor' => '#8b5cf6', // Purple
                    'borderColor' => '#7c3aed',
                    'textColor' => '#ffffff',
                    'url' => route('admin.applications.show', $interview->application_id),
                ];
            }
        }

        // 2. Job Closing Deadline Events
        $jobs = Job::whereNotNull('deadline')->get();
        foreach ($jobs as $job) {
            if ($job->deadline) {
                $events[] = [
                    'id' => 'job_' . $job->id,
                    'title' => "⏳ Closing Lowongan: {$job->title}",
                    'start' => $job->deadline->toIso8601String(),
                    'backgroundColor' => '#ef4444', // Red
                    'borderColor' => '#dc2626',
                    'textColor' => '#ffffff',
                    'url' => route('admin.jobs.show', $job->id),
                ];
            }
        }

        // 3. Offer Letter Expiration Events
        $offerLetters = OfferLetter::with('user')->whereNotNull('expiration_date')->get();
        foreach ($offerLetters as $offer) {
            if ($offer->expiration_date) {
                $candidateName = $offer->user->name ?? 'Kandidat';
                $events[] = [
                    'id' => 'offer_' . $offer->id,
                    'title' => "📄 Expired Offer: {$candidateName} ({$offer->position_title})",
                    'start' => $offer->expiration_date->toIso8601String(),
                    'backgroundColor' => '#f59e0b', // Amber
                    'borderColor' => '#d97706',
                    'textColor' => '#ffffff',
                    'url' => route('admin.applications.show', $offer->application_id),
                ];
            }
        }

        return response()->json($events);
    }
}
