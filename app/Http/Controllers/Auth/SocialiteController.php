<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect(Request $request)
    {
        $driver = Socialite::driver('google');

        $isMobile = $request->query('source') === 'mobile' || $request->has('redirect_to');

        if ($isMobile) {
            $redirectTo = $request->query('redirect_to', 'mahasiswakuapp://auth/callback');

            // Encode payload ke URL-safe base64 state parameter
            $payload = [
                'source' => 'mobile',
                'redirect_to' => $redirectTo,
                'rand' => bin2hex(random_bytes(8)),
            ];
            $stateData = rtrim(strtr(base64_encode(json_encode($payload)), '+/', '-_'), '=');

            session(['oauth_source' => 'mobile', 'mobile_redirect_to' => $redirectTo]);

            return $driver->stateless()->with(['state' => $stateData])->redirect();
        }

        return $driver->redirect();
    }

    public function callback(Request $request)
    {
        // Decode state parameter untuk mendeteksi apakah request berasal dari aplikasi mobile
        $stateRaw = $request->query('state');
        $isMobile = false;
        $targetRedirect = session('mobile_redirect_to', 'mahasiswakuapp://auth/callback');

        if ($stateRaw) {
            // URL-safe base64 decoding
            $normalizedState = strtr($stateRaw, '-_', '+/');
            $padding = strlen($normalizedState) % 4;
            if ($padding > 0) {
                $normalizedState .= str_repeat('=', 4 - $padding);
            }
            $decoded = base64_decode($normalizedState, true);
            if ($decoded) {
                $json = json_decode($decoded, true);
                if (is_array($json) && isset($json['source']) && $json['source'] === 'mobile') {
                    $isMobile = true;
                    if (!empty($json['redirect_to'])) {
                        $targetRedirect = $json['redirect_to'];
                    }
                }
            }

            if (!$isMobile && ($stateRaw === 'mobile' || str_contains($stateRaw, 'mobile'))) {
                $isMobile = true;
            }
        }

        if (!$isMobile && session('oauth_source') === 'mobile') {
            $isMobile = true;
        }

        try {
            $driver = Socialite::driver('google');
            // Jika request berasal dari mobile in-app browser, gunakan stateless() agar tidak terbentur masalah cookie session
            $googleUser = $isMobile ? $driver->stateless()->user() : $driver->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // If user exists but doesn't have google_id, update it
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                    ]);
                }
                if (!$isMobile) {
                    Auth::login($user);
                }
            } else {
                // Create a new user
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);

                // Assign default role 'Candidate'
                \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Candidate', 'guard_name' => 'web']);
                $user->assignRole('Candidate');

                // Create CandidateProfile
                \App\Models\CandidateProfile::create([
                    'user_id' => $user->id,
                    'phone' => null,
                    'resume_path' => null,
                ]);

                if (!$isMobile) {
                    Auth::login($user);
                }
            }

            // Jika login berasal dari aplikasi mobile React Native, buat Sanctum Token dan redirect ke aplikasi
            if ($isMobile) {
                session()->forget(['oauth_source', 'mobile_redirect_to']);
                $token = $user->createToken('mobile-google-auth')->plainTextToken;

                $separator = str_contains($targetRedirect, '?') ? '&' : '?';
                $appUrl = $targetRedirect . $separator . 'token=' . urlencode($token);

                // Kirim response 302 Found dengan Location header & HTML auto-redirect ultra cepat
                return response(
                    '<!DOCTYPE html>
                    <html lang="id">
                    <head>
                        <meta charset="utf-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1">
                        <meta http-equiv="refresh" content="0;url=' . htmlspecialchars($appUrl, ENT_QUOTES, 'UTF-8') . '">
                        <title>Membuka MahasiswaKu...</title>
                        <script>
                            window.location.replace(' . json_encode($appUrl) . ');
                        </script>
                    </head>
                    <body style="font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, sans-serif; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 85vh; margin: 0; background-color: #F8FAFC; color: #0F172A; text-align: center; padding: 20px;">
                        <div style="background: white; border-radius: 24px; padding: 36px 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.06); max-width: 360px; width: 100%; border: 1px solid #E2E8F0;">
                            <div style="width: 58px; height: 58px; border-radius: 50%; background: #DCFCE7; color: #16A34A; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto; font-size: 28px; font-weight: bold;">
                                ✓
                            </div>
                            <h2 style="margin: 0 0 8px 0; font-size: 20px; font-weight: 800; color: #0F172A;">Login Berhasil!</h2>
                            <p style="margin: 0 0 24px 0; font-size: 14px; color: #64748B;">Membuka kembali aplikasi MahasiswaKu...</p>
                            <a id="btn-open" href="' . htmlspecialchars($appUrl, ENT_QUOTES, 'UTF-8') . '" style="display: block; background: #9A1314; color: white; padding: 14px 20px; border-radius: 14px; text-decoration: none; font-weight: bold; font-size: 15px; box-shadow: 0 4px 12px rgba(154,19,20,0.3);">
                                Buka Aplikasi Sekarang
                            </a>
                        </div>
                        <script>
                            setTimeout(function() {
                                try {
                                    var btn = document.getElementById("btn-open");
                                    if (btn) btn.click();
                                } catch(e) {}
                                window.location.href = ' . json_encode($appUrl) . ';
                            }, 150);
                        </script>
                    </body>
                    </html>',
                    302,
                    [
                        'Content-Type' => 'text/html; charset=utf-8',
                        'Location' => $appUrl,
                        'Cache-Control' => 'no-cache, no-store, must-revalidate',
                        'Pragma' => 'no-cache',
                        'Expires' => '0',
                    ]
                );
            }

            return redirect()->intended('/dashboard');

        } catch (\Exception $e) {
            if ($isMobile) {
                session()->forget(['oauth_source', 'mobile_redirect_to']);
                $separator = str_contains($targetRedirect, '?') ? '&' : '?';
                $appUrl = $targetRedirect . $separator . 'error=' . urlencode($e->getMessage());

                return response(
                    '<!DOCTYPE html>
                    <html lang="id">
                    <head>
                        <meta charset="utf-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1">
                        <meta http-equiv="refresh" content="0;url=' . htmlspecialchars($appUrl, ENT_QUOTES, 'UTF-8') . '">
                        <title>Gagal Login</title>
                        <script>
                            window.location.replace(' . json_encode($appUrl) . ');
                        </script>
                    </head>
                    <body style="font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, sans-serif; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 85vh; margin: 0; background-color: #F8FAFC; color: #0F172A; text-align: center; padding: 20px;">
                        <div style="background: white; border-radius: 24px; padding: 36px 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.06); max-width: 360px; width: 100%; border: 1px solid #E2E8F0;">
                            <div style="width: 58px; height: 58px; border-radius: 50%; background: #FEE2E2; color: #DC2626; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto; font-size: 28px; font-weight: bold;">
                                ✕
                            </div>
                            <h2 style="margin: 0 0 8px 0; font-size: 20px; font-weight: 800; color: #0F172A;">Gagal Login</h2>
                            <p style="margin: 0 0 24px 0; font-size: 14px; color: #64748B;">' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>
                            <a id="btn-open" href="' . htmlspecialchars($appUrl, ENT_QUOTES, 'UTF-8') . '" style="display: block; background: #9A1314; color: white; padding: 14px 20px; border-radius: 14px; text-decoration: none; font-weight: bold; font-size: 15px;">
                                Kembali ke Aplikasi
                            </a>
                        </div>
                    </body>
                    </html>',
                    302,
                    [
                        'Content-Type' => 'text/html; charset=utf-8',
                        'Location' => $appUrl,
                        'Cache-Control' => 'no-cache, no-store, must-revalidate',
                    ]
                );
            }

            return redirect('/login')->withErrors(['email' => 'Gagal login menggunakan Google. Silakan coba lagi.']);
        }
    }
}
