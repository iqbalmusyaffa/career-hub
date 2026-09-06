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

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('body_content', 'like', "%{$search}%");
            });
        }

        // Category / Stage Filter
        if ($request->filled('type') && $request->type !== 'all') {
            if ($request->type === 'interview') {
                $query->whereIn('type', ['interview', 'interview_hr', 'interview_user']);
            } else {
                $query->where('type', $request->type);
            }
        }

        $perPage = $request->input('per_page', 12);
        $templates = $query->latest()->paginate($perPage)->withQueryString();

        // Stats calculation
        $allTemplates = EmailTemplate::all();
        $totalTemplates = $allTemplates->count();
        $countsByType = [
            'all' => $totalTemplates,
            'screening' => $allTemplates->where('type', 'screening')->count(),
            'test_invitation' => $allTemplates->where('type', 'test_invitation')->count(),
            'interview' => $allTemplates->whereIn('type', ['interview', 'interview_hr', 'interview_user'])->count(),
            'offering' => $allTemplates->where('type', 'offering')->count(),
            'background_check' => $allTemplates->where('type', 'background_check')->count(),
            'reminder' => $allTemplates->where('type', 'reminder')->count(),
            'rejection' => $allTemplates->where('type', 'rejection')->count(),
        ];

        // Candidate Applications Query (Scoped by company for non-superadmin)
        $user = auth()->user();
        $companyName = null;
        if ($user && !$user->hasRole('Super Admin')) {
            $profile = $user->currentCompanyProfile();
            $companyName = $profile ? $profile->company_name : null;
        }

        $appQuery = Application::with(['user', 'job'])->latest();
        if ($companyName) {
            $appQuery->whereHas('job', function($j) use ($companyName) {
                $j->where('company_name', 'LIKE', '%' . $companyName . '%');
            });
        }
        $applications = $appQuery->take(100)->get();

        return view('admin.email_templates.index', compact('templates', 'applications', 'countsByType', 'totalTemplates'));
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
            'application_id' => 'required',
            'subject' => 'required|string|max:255',
            'body_content' => 'required|string',
        ]);

        $realAppId = \App\Helpers\IdHasher::decode($request->application_id) ?? $request->application_id;
        $application = Application::with(['user', 'job'])->findOrFail($realAppId);
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
                route('jobs.show', $application->job),
                'info'
            );

            \App\Models\AuditLog::record('email_broadcast_sent', "HR " . auth()->user()->name . " mengirim email template ke pelamar " . $application->user->name);

            return back()->with('success', 'Email berhasil dikirim ke ' . $candidateEmail . '!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim email: ' . $e->getMessage() . '. Pastikan pengaturan SMTP diatur dengan benar.');
        }
    }
}
