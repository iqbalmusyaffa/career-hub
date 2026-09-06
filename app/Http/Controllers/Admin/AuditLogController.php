<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AuditLogController extends Controller
{
    /**
     * Display Audit Logs list.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $actionFilter = $request->input('action');
        $categoryFilter = $request->input('category');
        $dateFilter = $request->input('date_range');

        $query = AuditLog::with('user')->latest();

        // Search Filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Action Specific Filter
        if ($actionFilter) {
            $query->where('action', $actionFilter);
        }

        // Category Filter
        if ($categoryFilter) {
            switch ($categoryFilter) {
                case 'auth':
                    $query->whereIn('action', ['login', 'logout', 'password_reset', 'impersonate_user', 'impersonation_started', 'impersonation_ended', 'email_verification_toggled']);
                    break;
                case 'users':
                    $query->where(function($q) {
                        $q->where('action', 'like', '%user%')
                          ->orWhere('action', 'like', '%role%')
                          ->orWhere('action', 'like', '%suspend%');
                    });
                    break;
                case 'companies':
                    $query->where(function($q) {
                        $q->where('action', 'like', '%company%')
                          ->orWhere('action', 'like', '%legal%');
                    });
                    break;
                case 'jobs':
                    $query->where(function($q) {
                        $q->where('action', 'like', '%job%')
                          ->orWhere('action', 'like', '%application%')
                          ->orWhere('action', 'like', '%candidate%');
                    });
                    break;
                case 'settings':
                    $query->whereIn('action', ['smtp_updated', 'seo_branding_updated', 'system_setting_updated']);
                    break;
            }
        }

        // Date Filter
        if ($dateFilter) {
            if ($dateFilter === 'today') {
                $query->whereDate('created_at', today());
            } elseif ($dateFilter === '7days') {
                $query->where('created_at', '>=', now()->subDays(7));
            } elseif ($dateFilter === '30days') {
                $query->where('created_at', '>=', now()->subDays(30));
            }
        }

        $perPage = (int) $request->input('per_page', 15);
        $logs = $query->paginate($perPage)->withQueryString();

        // Metrics for summary cards
        $totalLogs = AuditLog::count();
        $todayLogs = AuditLog::whereDate('created_at', today())->count();
        $securityLogs = AuditLog::whereIn('action', ['impersonate_user', 'role_changed', 'user_suspended', 'smtp_updated'])->count();
        $uniqueActors = AuditLog::whereNotNull('user_id')->distinct('user_id')->count('user_id');

        $actionTypes = AuditLog::select('action')->distinct()->pluck('action');

        return view('admin.audit_logs.index', compact(
            'logs', 
            'actionTypes', 
            'totalLogs', 
            'todayLogs', 
            'securityLogs', 
            'uniqueActors'
        ));
    }

    /**
     * Export Audit Trail to CSV.
     */
    public function export(Request $request)
    {
        $logs = AuditLog::with('user')->latest()->limit(5000)->get();

        $response = new StreamedResponse(function () use ($logs) {
            $handle = fopen('php://output', 'w');
            
            // Add UTF-8 BOM
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'ID Log',
                'Waktu Kejadian (WIB)',
                'Aktor / User',
                'Email Aktor',
                'Role Aktor',
                'Tipe Aksi',
                'Deskripsi Aktivitas',
                'IP Address',
                'User Agent / Device'
            ]);

            foreach ($logs as $log) {
                fputcsv($handle, [
                    $log->id,
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->user ? $log->user->name : 'Sistem / Tamu',
                    $log->user ? $log->user->email : '-',
                    $log->user ? $log->user->role : 'System',
                    strtoupper($log->action),
                    $log->description,
                    $log->ip_address ?? '127.0.0.1',
                    $log->user_agent ?? '-'
                ]);
            }

            fclose($handle);
        });

        $filename = 'Audit_Logs_TalentFlow_' . date('Y-m-d_His') . '.csv';

        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }
}
