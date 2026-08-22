<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Blacklist;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckBlacklist
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $clientIp = $request->ip();

        // Check if IP is blacklisted
        if (Blacklist::isBlocked($clientIp, 'ip')) {
            if (Auth::check()) {
                Auth::logout();
            }
            return redirect()->route('login')->with('error', 'Akses ditolak: Alamat IP Anda (' . $clientIp . ') telah diblokir demi keamanan platform.');
        }

        // Check if logged in user email or phone is blacklisted
        if (Auth::check()) {
            $user = Auth::user();
            if (Blacklist::isBlocked($user->email, 'email')) {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Akses ditolak: Alamat Email Anda (' . $user->email . ') telah masuk dalam daftar hitam platform.');
            }
        }

        return $next($request);
    }
}
