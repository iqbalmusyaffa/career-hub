<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailTemplateManagementController extends Controller
{
    public function index(Request $request)
    {
        EmailTemplate::seedDefaultTemplates();

        $query = EmailTemplate::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('body_content', 'like', "%{$search}%");
            });
        }

        $perPage = $request->input('per_page', 10);
        $templates = $query->latest()->paginate($perPage)->withQueryString();

        $applications = Application::with(['user', 'job'])->latest()->get();

        return view('admin.email_templates.index', compact('templates', 'applications'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'subject' => 'required|string|max:255',
            'body_content' => 'required|string',
        ]);

        EmailTemplate::create([
            'name' => $request->name,
            'type' => $request->type,
            'subject' => $request->subject,
            'body_content' => $request->body_content,
            'is_active' => true,
        ]);

        return back()->with('success', 'Template Email HR baru berhasil dibuat!');
    }

    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'subject' => 'required|string|max:255',
            'body_content' => 'required|string',
        ]);

        $emailTemplate->update([
            'name' => $request->name,
            'type' => $request->type,
            'subject' => $request->subject,
            'body_content' => $request->body_content,
        ]);

        return back()->with('success', 'Template Email HR berhasil diperbarui!');
    }

    public function destroy(EmailTemplate $emailTemplate)
    {
        $emailTemplate->delete();

        return back()->with('success', 'Template Email HR berhasil dihapus.');
    }

    public function broadcast(Request $request)
    {
        $request->validate([
            'application_id' => 'required|exists:applications,id',
            'subject' => 'required|string|max:255',
            'body_content' => 'required|string',
        ]);

        $application = Application::with(['user', 'job'])->findOrFail($request->application_id);
        $candidateEmail = $application->user->email;

        try {
            Mail::raw($request->body_content, function ($message) use ($candidateEmail, $request) {
                $message->to($candidateEmail)
                    ->subject($request->subject);
            });

            // Send in-app notification backup
            \App\Models\UserNotification::send(
                $application->user_id,
                $request->subject,
                "Email dari HR: " . substr(strip_tags($request->body_content), 0, 150) . "...",
                route('jobs.show', $application->job_id),
                'info'
            );

            \App\Models\AuditLog::record('email_broadcast_sent', "HR " . auth()->user()->name . " mengirim email template ke pelamar " . $application->user->name);

            return back()->with('success', 'Email berhasil dikirim ke ' . $candidateEmail . '!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim email: ' . $e->getMessage() . '. Pastikan pengaturan SMTP diatur dengan benar.');
        }
    }
}
