<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\CompanyTeamMember;
use Illuminate\Http\Request;

class CompanyActivityAuditController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $teamUserIds = [$user->id];

        $companyProfile = $user->currentCompanyProfile();
        if ($companyProfile) {
            $teamMembers = CompanyTeamMember::where('company_profile_id', $companyProfile->id)->pluck('user_id');
            $teamUserIds = array_values(array_unique(array_merge($teamUserIds, [$companyProfile->user_id], $teamMembers->toArray())));
        }

        $query = AuditLog::with('user')->whereIn('user_id', $teamUserIds);

        if ($request->filled('action')) {
            $query->where('action', 'LIKE', "%{$request->action}%");
        }

        $logs = $query->latest()->paginate(25);

        return view('admin.company_team.audit_logs', compact('logs'));
    }
}
