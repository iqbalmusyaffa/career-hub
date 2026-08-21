<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Interview;
use App\Mail\InterviewScheduledMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class InterviewController extends Controller
{
    public function store(Request $request, $applicationId)
    {
        $request->validate([
            'scheduled_at' => 'required|date|after:now',
            'type' => 'required|in:online,offline',
            'location_or_link' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $application = Application::with(['user', 'job'])->findOrFail($applicationId);

        $interview = Interview::updateOrCreate(
            ['application_id' => $application->id],
            [
                'scheduled_at' => $request->scheduled_at,
                'type' => $request->type,
                'location_or_link' => $request->location_or_link,
                'notes' => $request->notes,
                'status' => 'scheduled',
            ]
        );

        // Update application status to interview
        $application->update(['status' => 'interview']);

        // Send In-App Bell Notification to Candidate
        \App\Models\UserNotification::send(
            $application->user_id,
            "📌 Undangan Wawancara Kerja!",
            "Anda mendapatkan undangan wawancara untuk posisi " . ($application->job->title ?? 'Pekerjaan') . " pada " . \Carbon\Carbon::parse($request->scheduled_at)->format('d M Y, H:i WIB'),
            route('dashboard'),
            'warning'
        );

        // Dispatch Email Notification to Candidate
        try {
            if ($application->user && $application->user->email) {
                Mail::to($application->user->email)->send(new InterviewScheduledMail($interview));
            }
        } catch (\Exception $e) {
            // Ignore email errors gracefully if SMTP is not configured
        }

        return back()->with('success', 'Jadwal wawancara berhasil disimpan, status diperbarui ke Wawancara, dan Email Notifikasi dikirim ke kandidat!');
    }
}
