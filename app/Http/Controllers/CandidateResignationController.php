<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\InternshipPeriod;
use App\Models\InternshipResignation;
use App\Models\AuditLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CandidateResignationController extends Controller
{
    /**
     * Show resignation page / form for intern.
     */
    public function index()
    {
        $user = Auth::user();

        // Cari periode magang aktif atau lamaran magang yang diterima
        $period = InternshipPeriod::where('user_id', $user->id)->with('company')->first();
        $application = Application::where('user_id', $user->id)
            ->whereIn('status', ['accepted', 'hired'])
            ->with(['job.companyProfile', 'onboarding'])
            ->latest()
            ->first();

        // Cari riwayat pengajuan pengunduran diri
        $resignations = InternshipResignation::where('user_id', $user->id)
            ->with(['company', 'reviewer'])
            ->latest()
            ->get();

        $activeResignation = $resignations->first();

        $companyName = $period && $period->company ? $period->company->company_name : ($application && $application->job ? $application->job->company_name : 'Perusahaan Magang');
        $jobTitle = $application && $application->job ? $application->job->title : ($period ? ($period->period_name ?? 'Peserta Magang') : 'Peserta Magang');
        $location = $application && $application->job ? $application->job->location : ($period && $period->company ? $period->company->city : 'Lokasi Magang');

        if ($period && $period->start_date && $period->end_date) {
            $startDate = $period->start_date;
            $endDate = $period->end_date;
        } elseif ($application && $application->job) {
            $startDate = $application->job->start_date ? Carbon::parse($application->job->start_date) : Carbon::create(2026, 8, 10);
            $durationStr = $application->job->duration ?? '6 Bulan';
            preg_match('/(\d+)/', $durationStr, $matches);
            $months = isset($matches[1]) ? (int)$matches[1] : 6;
            $endDate = $startDate->copy()->addMonths($months)->subDay();
        } else {
            $startDate = Carbon::create(2026, 8, 10);
            $endDate = Carbon::create(2027, 2, 9);
        }

        return view('candidate.resignations.index', compact(
            'user',
            'period',
            'application',
            'companyName',
            'jobTitle',
            'location',
            'startDate',
            'endDate',
            'resignations',
            'activeResignation'
        ));
    }

    /**
     * Store self-resignation submission.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Check if there is already a pending request
        $existing = InternshipResignation::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'Anda masih memiliki pengajuan pengunduran diri yang sedang menunggu verifikasi.');
        }

        $request->validate([
            'reason_category' => 'required|string|in:academic,health,relocation,personal,other',
            'reason_details' => 'required|string|min:10|max:2000',
            'effective_date' => 'required|date|after_or_equal:today',
            'handover_notes' => 'nullable|string|max:1000',
            'resignation_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'reason_category.required' => 'Pilih kategori alasan pengunduran diri.',
            'reason_details.required' => 'Jelaskan alasan pengunduran diri secara rinci.',
            'effective_date.required' => 'Tentukan tanggal efektif pengunduran diri.',
            'effective_date.after_or_equal' => 'Tanggal efektif tidak boleh di masa lalu.',
            'resignation_document.required' => 'Unggah dokumen surat pengunduran diri bertanda tangan.',
            'resignation_document.max' => 'Ukuran dokumen maksimal 5MB.',
        ]);

        $period = InternshipPeriod::where('user_id', $user->id)->first();
        $application = Application::where('user_id', $user->id)
            ->whereIn('status', ['accepted', 'hired'])
            ->latest()
            ->first();

        $companyId = $period ? $period->company_id : ($application && $application->job ? $application->job->company_profile_id : null);

        $docPath = null;
        if ($request->hasFile('resignation_document')) {
            $docPath = $request->file('resignation_document')->store('resignation_docs', 'public');
        }

        $resignation = InternshipResignation::create([
            'user_id' => $user->id,
            'application_id' => $application ? $application->id : null,
            'company_id' => $companyId,
            'reason_category' => $request->reason_category,
            'reason_details' => $request->reason_details,
            'effective_date' => $request->effective_date,
            'handover_notes' => $request->handover_notes,
            'document_path' => $docPath,
            'status' => 'pending',
        ]);

        // Audit Log
        if (class_exists(AuditLog::class)) {
            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'Candidate Submitted Resignation Request',
                'description' => "Peserta {$user->name} mengajukan pengunduran diri mandiri efektif per " . Carbon::parse($request->effective_date)->format('d/m/Y'),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return redirect()->route('candidate.resignations.index')->with('success', 'Pengajuan pengunduran diri berhasil dikirimkan. Anda dapat mengunduh surat resmi bertanda tangan digital dengan QR Code di bawah.');
    }

    /**
     * Download Official PDF with QR Code.
     */
    public function downloadPdf($id)
    {
        $resignation = InternshipResignation::with(['user', 'company', 'application.job', 'reviewer'])->findOrFail($id);

        if (Auth::check()) {
            $user = Auth::user();
            if (!$user->hasRole('Super Admin') && !$user->hasRole('HR') && !$user->hasRole('Mentor') && $user->id !== $resignation->user_id) {
                abort(403, 'Akses tidak diizinkan.');
            }
        }

        $pdf = Pdf::loadView('pdf.internship_resignation', compact('resignation'))
            ->setPaper('a4', 'portrait');

        $fileName = 'Surat_Pengunduran_Diri_' . Str::slug($resignation->user->name) . '_' . date('Ymd') . '.pdf';

        return $pdf->download($fileName);
    }

    /**
     * Public QR Code Verification endpoint.
     */
    public function verify($id)
    {
        $resignation = InternshipResignation::with(['user', 'company', 'application.job', 'reviewer'])->find($id);
        return view('pages.verify_resignation', compact('resignation'));
    }

    /**
     * Cancel pending resignation request.
     */
    public function cancel($id)
    {
        $resignation = InternshipResignation::where('user_id', Auth::id())
            ->where('id', $id)
            ->where('status', 'pending')
            ->firstOrFail();

        $resignation->update([
            'status' => 'cancelled',
            'review_notes' => 'Dibatalkan sendiri oleh peserta magang.',
        ]);

        return redirect()->back()->with('success', 'Pengajuan pengunduran diri telah dibatalkan.');
    }
}
