<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class OtpService
{
    /**
     * Generate a 6-digit OTP code valid for 5 minutes.
     */
    public static function generateOtp(int $userId, string $action = 'agreement_signing'): string
    {
        $otpCode = (string) rand(100000, 999999);
        $cacheKey = "otp_{$action}_{$userId}";

        // Store OTP code in cache for 5 minutes (300 seconds)
        Cache::put($cacheKey, $otpCode, 300);

        return $otpCode;
    }

    /**
     * Verify 6-digit OTP code.
     */
    public static function verifyOtp(int $userId, string $action, string $inputOtp): bool
    {
        $cacheKey = "otp_{$action}_{$userId}";
        $storedOtp = Cache::get($cacheKey);

        if ($storedOtp && $storedOtp === trim($inputOtp)) {
            Cache::forget($cacheKey); // Forget once verified
            return true;
        }

        return false;
    }
}
