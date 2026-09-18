<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\InternshipUnlockRequest;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MentorUnlockRequestController extends Controller
{
    public function index(Request $request)
    {
        $mentorId = auth()->id();
        $user = auth()->user();
        $companyId = $user?->companyProfile?->id;

        $selectedBatch = $request->query('batch');
        $batches = \App\Models\InternshipBatch::getActiveBatches($companyId);

        $query = InternshipUnlockRequest::with(['intern.candidateProfile', 'intern.internshipPeriod', 'intern.applications.job', 'resolver'])
            ->where('mentor_id', $mentorId);

        if ($selectedBatch) {
            $query->whereHas('intern', function($q) use ($selectedBatch) {
                $q->whereHas('internshipPeriods', function($p) use ($selectedBatch) {
                    $p->where('period_name', $selectedBatch);
                })->orWhereHas('applications.job', function($j) use ($selectedBatch) {
                    $j->where('batch', $selectedBatch);
                });
            });
        }

        $requests = $query->latest()->paginate(15)->withQueryString();

        return view('mentor.unlock_requests.index', compact('requests', 'batches', 'selectedBatch'));
    }

    public function create()
    {
        $user = auth()->user();
        $companyId = $user?->companyProfile?->id;

        $batches = \App\Models\InternshipBatch::getActiveBatches($companyId);

        // Get all active interns under mentor / candidates with their batch details
        $interns = User::role('Candidate')
            ->whereDoesntHave('internshipResignations', function($q) {
                $q->where('status', 'approved');
            })
            ->whereDoesntHave('employeeTerminations')
            ->with(['candidateProfile', 'internshipPeriod', 'applications.job'])
            ->orderBy('name')
            ->get();

        return view('mentor.unlock_requests.create', compact('interns', 'batches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'intern_id' => 'required|exists:users,id',
            'target_date' => 'required|date|before_or_equal:today',
            'category' => 'required|in:medical_emergency,academic_urgent,personal_urgent,cuti_bersama,dinas_luar,libur_nasional_agenda,platform_outage,partner_issue,force_majeure',
            'description' => 'required|string|min:20',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'integrity_declaration' => 'accepted',
        ], [
            'description.min' => 'Uraian kronologi kejadian harus minimal 20 karakter.',
            'category.in' => 'Kategori kendala tidak valid.',
            'integrity_declaration.accepted' => 'Anda wajib menyetujui pernyataan integritas bahwa permohonan ini bukan karena kelalaian/lupa absen.',
        ]);

        $mentor = auth()->user();
        $attachmentPath = null;

        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('unlock_attachments', 'public');
        }

        $unlockRequest = InternshipUnlockRequest::create([
            'mentor_id' => $mentor->id,
            'intern_id' => $request->intern_id,
            'company_id' => $mentor->companyProfile->id ?? null,
            'target_date' => $request->target_date,
            'category' => $request->category,
            'description' => $request->description,
            'attachment_path' => $attachmentPath,
            'status' => 'pending',
        ]);

        $internUser = User::find($request->intern_id);

        AuditLog::record(
            'MENTOR_SUBMIT_UNLOCK_REQUEST',
            "Mentor {$mentor->name} mengajukan tiket dispensasi buka kunci tanggal {$request->target_date} untuk anak magang {$internUser->name}. Kategori: {$request->category}",
            $mentor
        );

        return redirect()->route('mentor.unlock-requests.index')
            ->with('success', 'Permohonan buka kunci tanggal presensi berhasil dikirim ke Super Admin untuk ditinjau.');
    }

    public function downloadPdf($id)
    {
        $mentor = auth()->user();
        $unlockRequest = InternshipUnlockRequest::with(['mentor', 'intern.candidateProfile', 'intern.applications.job', 'company', 'resolver'])
            ->where('mentor_id', $mentor->id)
            ->findOrFail($id);

        if ($unlockRequest->status !== 'approved') {
            return redirect()->back()->with('error', 'Dokumen Surat Resmi Dispensasi hanya dapat diunduh untuk permohonan yang telah disetujui (Approved).');
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.internship_unlock_dispensation', compact('unlockRequest'))
            ->setPaper('a4', 'portrait');

        $filename = 'Surat_Dispensasi_Presensi_' . \Illuminate\Support\Str::slug($unlockRequest->intern->name) . '_' . $unlockRequest->target_date->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }
}
