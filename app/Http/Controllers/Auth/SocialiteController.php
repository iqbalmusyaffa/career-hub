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

        if ($request->query('source') === 'mobile') {
            session(['oauth_source' => 'mobile']);
            return $driver->with(['state' => 'mobile'])->redirect();
        }

        return $driver->redirect();
    }

    public function callback(Request $request)
    {
        try {
            $isMobile = session('oauth_source') === 'mobile'
                || $request->query('source') === 'mobile'
                || $request->query('state') === 'mobile';

            $driver = Socialite::driver('google');
            // Jika request berasal dari mobile in-app browser, gunakan stateless() agar tidak terbentur session cookie cross-origin
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
                Auth::login($user);
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

                Auth::login($user);
            }

            // Jika login berasal dari aplikasi mobile React Native, buat Sanctum Token dan redirect ke app
            if ($isMobile) {
                session()->forget('oauth_source');
                $token = $user->createToken('mobile-google-auth')->plainTextToken;
                return redirect()->to("mahasiswakuapp://auth/callback?token=" . urlencode($token));
            }

            return redirect()->intended('/dashboard');

        } catch (\Exception $e) {
            $isMobile = session('oauth_source') === 'mobile'
                || $request->query('source') === 'mobile'
                || $request->query('state') === 'mobile';

            if ($isMobile) {
                session()->forget('oauth_source');
                return redirect()->to("mahasiswakuapp://auth/callback?error=" . urlencode($e->getMessage()));
            }

            return redirect('/login')->withErrors(['email' => 'Gagal login menggunakan Google. Silakan coba lagi.']);
        }
    }
}
