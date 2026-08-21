<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    public function sendTemplate(Request $request, Application $application)
    {
        $request->validate([
            'template_type' => 'required|in:interview_invitation,friendly_rejection,custom_notification',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $candidateEmail = $application->user->email;
        $candidateName = $application->user->name;
        $jobTitle = $application->job->title;

        try {
            \Illuminate\Support\Facades\Mail::raw($request->body, function ($message) use ($candidateEmail, $request) {
                $message->to($candidateEmail)
                    ->subject($request->subject);
            });

            // Send in-app notification backup
            \App\Models\UserNotification::send(
                $application->user_id,
                $request->subject,
                "Email dari HR: " . substr(strip_tags($request->body), 0, 150) . "...",
                route('jobs.show', $application->job_id),
                'info'
            );

            \App\Models\AuditLog::record('email_template_sent', "HR " . auth()->user()->name . " mengirim email template (" . $request->template_type . ") ke " . $candidateName);

            return back()->with('success', 'Email ' . $request->subject . ' berhasil dikirim ke ' . $candidateEmail . '!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim email: ' . $e->getMessage() . '. (Pastikan konfigurasi SMTP diatur di menu Setting SMTP).');
        }
    }
}
