<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InternshipStipendDisbursement;
use App\Models\User;
use App\Models\InternshipBatch;
use App\Models\InternshipLogbook;
use App\Models\CandidateOnboarding;
use App\Models\ApplicationAgreement;
use App\Exports\InternshipStipendsExport;
use App\Mail\StipendBankReminderMail;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InternshipStipendController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('Super Admin');
        $companyProfile = $user->currentCompanyProfile();
        $companyId = $companyProfile ? $companyProfile->id : null;

        $selectedBatch = $request->query('batch');
        $selectedMonth = $request->query('month', date('Y-m'));
        $selectedStatus = $request->query('status');
        $selectedBankStatus = $request->query('bank_status');
        $selectedAttendance = $request->query('attendance');
        $search = $request->query('search');

        // Automatically ensure monthly stipend records exist for active interns
        $this->syncMonthlyDisbursements($selectedMonth, $companyId);

        $query = InternshipStipendDisbursement::with(['user.candidateProfile', 'user.internshipPeriod', 'company', 'verifier', 'mentor'])
            ->where('period_month', $selectedMonth);

        if (!$isSuperAdmin && $companyId) {
            $query->where('company_id', $companyId);
        }

        if ($selectedBatch) {
            $query->where('batch_name', $selectedBatch);
        }

        if ($selectedStatus) {
            if ($selectedStatus === 'pending_mentor') {
                $query->whereNull('mentor_submitted_at')->where('status', '!=', 'transferred');
            } elseif ($selectedStatus === 'submitted_mentor') {
                $query->whereNotNull('mentor_submitted_at');
            } else {
                $query->where('status', $selectedStatus);
            }
        }

        if ($selectedBankStatus) {
            if ($selectedBankStatus === 'unfilled') {
                $query->where(function($q) {
                    $q->whereNull('bank_account_number')->orWhere('bank_account_number', '');
                });
            } elseif ($selectedBankStatus === 'ktp_match') {
                $query->whereNotNull('bank_account_number')->where('bank_account_number', '!=', '')->where('is_ktp_matched', true);
            } elseif ($selectedBankStatus === 'ktp_unmatch') {
                $query->whereNotNull('bank_account_number')->where('bank_account_number', '!=', '')->where('is_ktp_matched', false);
            }
        }

        if ($selectedAttendance) {
            if ($selectedAttendance === 'unexcused' || $selectedAttendance === 'alpha') {
                $query->where('unexcused_days', '>', 0);
            } elseif ($selectedAttendance === 'excused' || $selectedAttendance === 'izin_sakit') {
                $query->where('excused_days', '>', 0);
            } elseif ($selectedAttendance === 'excess_excused') {
                $query->where('excused_days', '>', 4);
            } elseif ($selectedAttendance === 'has_deduction') {
                $query->where('deduction_amount', '>', 0);
            } elseif ($selectedAttendance === 'full_attendance' || $selectedAttendance === 'perfect') {
                $query->where('deduction_amount', 0)->where('unexcused_days', 0);
            }
        }

        if ($search) {
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('bank_account_number', 'like', "%{$search}%")
              ->orWhere('bank_account_holder', 'like', "%{$search}%");
        }

        $stipends = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Summary Stats for Current View
        $statsBaseQuery = InternshipStipendDisbursement::where('period_month', $selectedMonth);
        if (!$isSuperAdmin && $companyId) {
            $statsBaseQuery->where('company_id', $companyId);
        }
        if ($selectedBatch) {
            $statsBaseQuery->where('batch_name', $selectedBatch);
        }

        $totalRecipients = (clone $statsBaseQuery)->count();
        $totalDisbursed = (clone $statsBaseQuery)->where('status', 'transferred')->sum('net_amount');
        $totalPending = (clone $statsBaseQuery)->whereIn('status', ['in_review', 'ready'])->sum('net_amount');
        $transferredCount = (clone $statsBaseQuery)->where('status', 'transferred')->count();

        $batches = InternshipBatch::getActiveBatches($companyId);

        // Generate list of available months (last 6 months and next 6 months)
        $availableMonths = [];
        $cursorMonth = Carbon::now()->subMonths(3)->startOfMonth();
        for ($i = 0; $i < 8; $i++) {
            $mKey = $cursorMonth->format('Y-m');
            $availableMonths[$mKey] = $cursorMonth->translatedFormat('F Y');
            $cursorMonth->addMonth();
        }

        return view('admin.internship_stipends.index', compact(
            'stipends',
            'batches',
            'selectedBatch',
            'selectedMonth',
            'selectedStatus',
            'selectedBankStatus',
            'selectedAttendance',
            'search',
            'totalRecipients',
            'totalDisbursed',
            'totalPending',
            'transferredCount',
            'availableMonths'
        ));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:in_review,ready,transferred',
            'notes' => 'nullable|string',
            'proof_file' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:5120',
        ]);

        $stipend = InternshipStipendDisbursement::with('user')->findOrFail($id);
        $user = auth()->user();

        $proofPath = $stipend->proof_path;
        if ($request->hasFile('proof_file')) {
            $proofPath = $request->file('proof_file')->store('stipend_proofs', 'public');
        }

        $transferredAt = $request->status === 'transferred' ? ($stipend->transferred_at ?? now()) : null;

        $stipend->update([
            'status' => $request->status,
            'notes' => $request->notes ?? $stipend->notes,
            'proof_path' => $proofPath,
            'transferred_at' => $transferredAt,
            'verified_by' => $user->id,
        ]);

        if ($request->status === 'transferred') {
            \App\Models\UserNotification::send(
                $stipend->user_id,
                '💸 Uang Saku Telah Ditransfer!',
                "Uang saku magang periode {$stipend->period_label} sebesar {$stipend->formatted_net_amount} telah berhasil ditransfer ke rekening {$stipend->bank_name} ({$stipend->bank_account_number} a.n {$stipend->bank_account_holder}).",
                route('candidate.logbook.progress'),
                'success'
            );
        } elseif ($request->status === 'in_review' && $request->notes) {
            \App\Models\UserNotification::send(
                $stipend->user_id,
                '⚠️ Catatan Verifikasi Uang Saku HR',
                "Tim HR memberikan catatan pada uang saku periode {$stipend->period_label}: \"{$request->notes}\". Silakan periksa status dan kelengkapan rekening Anda.",
                route('candidate.logbook.progress'),
                'warning'
            );
        }

        return redirect()->back()->with('success', "Status uang saku untuk {$stipend->user?->name} ({$stipend->period_label}) berhasil diperbarui.");
    }

    /**
     * Send reminder notification to candidate who hasn't filled bank details.
     */
    public function sendBankReminder($id)
    {
        $stipend = InternshipStipendDisbursement::with('user')->findOrFail($id);
        $hr = auth()->user();

        // 1. Send In-App Bell Notification
        \App\Models\UserNotification::send(
            $stipend->user_id,
            '⚠️ Pengingat: Lengkapi Rekening Bank Anda',
            "Halo {$stipend->user?->name}, pencairan uang saku magang periode {$stipend->period_label} segera diproses. Mohon segera lengkapi nomor rekening bank & foto buku tabungan Anda di menu Onboarding / Profil.",
            route('candidate.logbook.progress'),
            'warning'
        );

        // 2. Dispatch Email Notification to Candidate
        if ($stipend->user && $stipend->user->email) {
            try {
                Mail::to($stipend->user->email)->send(new StipendBankReminderMail($stipend, $hr->name, 'Tim HR & Finance'));
            } catch (\Throwable $e) {
                \Log::warning("Gagal mengirim email pengingat rekening ke {$stipend->user->email}: " . $e->getMessage());
            }
        }

        \App\Models\AuditLog::record(
            'STIPEND_BANK_REMINDER_SENT',
            "HR {$hr->name} mengirimkan pengingat notifikasi & email kelengkapan rekening bank ke peserta {$stipend->user?->name}.",
            $hr
        );

        return redirect()->back()->with('success', "Pengingat notifikasi & email pengisian rekening berhasil dikirimkan ke {$stipend->user?->name}.");
    }

    public function bulkTransfer(Request $request)
    {
        $request->validate([
            'stipend_ids' => 'required|array|min:1',
            'stipend_ids.*' => 'exists:internship_stipend_disbursements,id',
        ]);

        $user = auth()->user();
        $stipends = InternshipStipendDisbursement::with('user')->whereIn('id', $request->stipend_ids)->get();

        $count = 0;
        foreach ($stipends as $stipend) {
            $stipend->update([
                'status' => 'transferred',
                'transferred_at' => now(),
                'verified_by' => $user->id,
            ]);

            \App\Models\UserNotification::send(
                $stipend->user_id,
                '💸 Uang Saku Telah Ditransfer!',
                "Uang saku magang periode {$stipend->period_label} sebesar {$stipend->formatted_net_amount} telah berhasil ditransfer ke rekening {$stipend->bank_name} ({$stipend->bank_account_number} a.n {$stipend->bank_account_holder}).",
                route('candidate.logbook.progress'),
                'success'
            );
            $count++;
        }

        return redirect()->back()->with('success', "Berhasil menandai {$count} penerima uang saku sebagai TELAH DITRANSFER.");
    }

    public function exportExcel(Request $request)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('Super Admin');
        $companyProfile = $user->currentCompanyProfile();
        $companyId = $companyProfile ? $companyProfile->id : null;

        $selectedBatch = $request->query('batch');
        $selectedMonth = $request->query('month', date('Y-m'));
        $selectedStatus = $request->query('status');
        $selectedAttendance = $request->query('attendance');

        $query = InternshipStipendDisbursement::with(['user.candidateProfile', 'user.internshipPeriod', 'company', 'verifier', 'mentor'])
            ->where('period_month', $selectedMonth);

        if (!$isSuperAdmin && $companyId) {
            $query->where('company_id', $companyId);
        }

        if ($selectedBatch) {
            $query->where('batch_name', $selectedBatch);
        }

        if ($selectedStatus) {
            if ($selectedStatus === 'pending_mentor') {
                $query->whereNull('mentor_submitted_at')->where('status', '!=', 'transferred');
            } elseif ($selectedStatus === 'submitted_mentor') {
                $query->whereNotNull('mentor_submitted_at');
            } else {
                $query->where('status', $selectedStatus);
            }
        }

        if ($selectedAttendance) {
            if ($selectedAttendance === 'unexcused' || $selectedAttendance === 'alpha') {
                $query->where('unexcused_days', '>', 0);
            } elseif ($selectedAttendance === 'excused' || $selectedAttendance === 'izin_sakit') {
                $query->where('excused_days', '>', 0);
            } elseif ($selectedAttendance === 'excess_excused') {
                $query->where('excused_days', '>', 4);
            } elseif ($selectedAttendance === 'has_deduction') {
                $query->where('deduction_amount', '>', 0);
            } elseif ($selectedAttendance === 'full_attendance' || $selectedAttendance === 'perfect') {
                $query->where('deduction_amount', 0)->where('unexcused_days', 0);
            }
        }

        $stipends = $query->orderBy('created_at', 'desc')->get();
        $fileName = 'Rekap_Payroll_Uang_Saku_Magang_' . str_replace('-', '_', $selectedMonth) . '.xlsx';

        return Excel::download(new InternshipStipendsExport($stipends), $fileName);
    }

    public function downloadStipendSlip($id)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('Super Admin');
        $companyProfile = $user->currentCompanyProfile();
        $companyId = $companyProfile ? $companyProfile->id : null;

        $stipendQuery = InternshipStipendDisbursement::with(['user.candidateProfile', 'company', 'verifier']);
        if (!$isSuperAdmin && $companyId) {
            $stipendQuery->where('company_id', $companyId);
        }

        $stipend = $stipendQuery->findOrFail($id);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.internship_stipend_slip', [
            'stipend' => $stipend,
            'user' => $stipend->user
        ])->setPaper('a4', 'portrait');

        $filename = 'Slip_Uang_Saku_' . \Illuminate\Support\Str::slug($stipend->user?->name ?? 'Peserta') . '_' . str_replace('-', '_', $stipend->period_month) . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Auto-sync presence, deduction & bank details for all active interns.
     */
    protected function syncMonthlyDisbursements(string $periodMonth, ?int $companyId = null): void
    {
        $monthDate = Carbon::parse($periodMonth . '-01');
        $startOfMonth = $monthDate->copy()->startOfMonth();
        $endOfMonth = $monthDate->copy()->endOfMonth();
        $periodLabel = $monthDate->translatedFormat('F Y');

        $interns = User::role('Candidate')
            ->whereDoesntHave('internshipResignations', function($q) {
                $q->where('status', 'approved');
            })
            ->whereDoesntHave('employeeTerminations')
            ->with(['internshipPeriod', 'candidateProfile', 'applications.job', 'applications.agreements'])
            ->get();

        foreach ($interns as $intern) {
            $latestApp = $intern->applications()->latest()->first();
            $jobCompanyId = $latestApp?->job?->company_profile_id ?? 1;

            if ($companyId && $jobCompanyId != $companyId) {
                continue;
            }

            $batchName = $intern->internshipPeriod?->period_name ?? $latestApp?->job?->batch ?? 'Batch 1 - 2026';

            // Get bank onboarding data (null if not yet filled by candidate)
            $onboarding = CandidateOnboarding::where('user_id', $intern->id)->first();
            $bankName = $onboarding?->bank_name ?: null;
            $accountNumber = $onboarding?->bank_account_number ?: null;
            $accountHolder = $onboarding?->bank_account_holder ?: null;
            $bankBookDoc = $onboarding?->bank_book_doc_path ?: null;

            // Check KTP Name match only when bank account is filled
            $isKtpMatched = false;
            if ($accountHolder && $intern->name) {
                $cleanHolder = strtolower(preg_replace('/[^a-z0-9]/', '', $accountHolder));
                $cleanName = strtolower(preg_replace('/[^a-z0-9]/', '', $intern->name));
                $isKtpMatched = str_contains($cleanHolder, $cleanName) || str_contains($cleanName, $cleanHolder) || levenshtein($cleanHolder, $cleanName) <= 3;
            }

            // Agreement Stipend Base
            $agreement = ApplicationAgreement::where('user_id', $intern->id)->latest()->first();
            $baseNominal = 2800000;
            if ($agreement && preg_match('/[\d\.]+/', str_replace(['Rp', '.', ' '], '', $agreement->stipend_or_salary), $matches)) {
                $parsed = (int) str_replace('.', '', $matches[0]);
                if ($parsed > 100000) {
                    $baseNominal = $parsed;
                }
            }

            // Calculate attendance & deduction metrics via AttendanceReminderService
            $metrics = \App\Services\AttendanceReminderService::calculateMonthlyAttendanceMetrics($intern->id, $periodMonth, $jobCompanyId, $baseNominal);

            // Do not override if already marked as transferred
            $existing = InternshipStipendDisbursement::where('user_id', $intern->id)
                ->where('period_month', $periodMonth)
                ->first();

            if ($existing && $existing->status === 'transferred') {
                continue;
            }

            $status = $existing?->status ?? 'in_review';

            InternshipStipendDisbursement::updateOrCreate(
                [
                    'user_id' => $intern->id,
                    'period_month' => $periodMonth,
                ],
                [
                    'company_id' => $jobCompanyId,
                    'period_label' => $periodLabel,
                    'batch_name' => $batchName,
                    'bank_name' => $bankName,
                    'bank_account_number' => $accountNumber,
                    'bank_account_holder' => $accountHolder,
                    'bank_book_doc_path' => $bankBookDoc,
                    'is_ktp_matched' => $isKtpMatched,
                    'present_days' => $metrics['present_days'],
                    'excused_days' => $metrics['excused_days'],
                    'unexcused_days' => $metrics['unexcused_days'],
                    'total_working_days' => $metrics['total_working_days'],
                    'base_nominal' => $metrics['base_nominal'],
                    'deduction_amount' => $metrics['deduction_amount'],
                    'net_amount' => $metrics['net_amount'],
                    'status' => $status,
                ]
            );
        }
    }
}
