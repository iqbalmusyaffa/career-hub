<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blacklist;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlacklistController extends Controller
{
    public function index()
    {
        $query = Blacklist::with('blocker')->latest();

        if (request()->filled('type')) {
            $query->where('type', request('type'));
        }

        if (request()->filled('search')) {
            $search = request('search');
            $query->where('value', 'like', "%{$search}%")
                  ->orWhere('reason', 'like', "%{$search}%");
        }

        $perPage = (int) request('per_page', 10);
        $blacklists = $query->paginate($perPage)->withQueryString();

        return view('admin.blacklists.index', compact('blacklists'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:email,ip,phone,company_name',
            'value' => 'required|string|max:255',
            'reason' => 'required|string|min:5',
        ]);

        $val = strtolower(trim($request->value));

        if (Blacklist::isBlocked($val, $request->type)) {
            return back()->with('error', "Nilai '{$val}' sudah ada dalam daftar blacklist!");
        }

        Blacklist::create([
            'type' => $request->type,
            'value' => $val,
            'reason' => $request->reason,
            'blocked_by' => Auth::id(),
        ]);

        // If email is blacklisted, suspend user account automatically
        if ($request->type === 'email') {
            $user = User::where('email', $val)->first();
            if ($user) {
                $user->is_suspended = true;
                $user->status_reason = 'Masuk daftar hitam anti-fraud: ' . $request->reason;
                $user->save();
            }
        }

        AuditLog::record('blacklist_added', "Super Admin menambahkan '{$val}' ({$request->type}) ke dalam Blacklist Anti-Fraud");

        return back()->with('success', "Item '{$val}' berhasil dimasukkan ke dalam daftar blacklist anti-fraud!");
    }

    public function destroy(Blacklist $blacklist)
    {
        $val = $blacklist->value;
        $blacklist->delete();

        AuditLog::record('blacklist_removed', "Super Admin menghapus '{$val}' dari Blacklist Anti-Fraud");

        return back()->with('success', "Item '{$val}' berhasil dihapus dari daftar blacklist.");
    }
}
