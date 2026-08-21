<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Display Audit Logs list.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $actionFilter = $request->input('action');

        $query = AuditLog::with('user')->latest();

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

        if ($actionFilter) {
            $query->where('action', $actionFilter);
        }

        $perPage = (int) $request->input('per_page', 10);
        $logs = $query->paginate($perPage)->withQueryString();

        $actionTypes = AuditLog::select('action')->distinct()->pluck('action');

        return view('admin.audit_logs.index', compact('logs', 'actionTypes'));
    }
}
