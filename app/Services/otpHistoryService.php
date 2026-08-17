<?php

namespace App\Services;

use App\Models\OtpHistory;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;

class OtpHistoryService
{
    /**
     * Generate and save OTP
     */
    public function generate(User $user, string $type = 'login'): int
    {
        // Expire previous OTPs
        OtpHistory::where('user_id', $user->id)
            ->where('type', $type)
            ->whereNull('verified_at')
            ->update([
                'expires_at' => now(),
            ]);

        $otp = random_int(100000, 999999);

        OtpHistory::create([
            'user_id'    => $user->id,
            'email'      => $user->email,
            'otp'        => Hash::make($otp),
            'type'       => $type,
            'expires_at' => now()->addMinutes(10),
        ]);
        Mail::to($user->email)->queue(new OtpMail((string) $otp));
        return $otp;
    }

    /**
     * Verify OTP
     */
    public function checkOtp(User $user, string $otp, string $type = 'login'): bool
    {
        $history = OtpHistory::where('user_id', $user->id)
            ->where('type', $type)
            ->whereNull('verified_at')
            ->latest()
            ->first();

        if (!$history) {
            return false;
        }

        if (now()->greaterThan($history->expires_at)) {
            return false;
        }

        if (!Hash::check($otp, $history->otp)) {
            $history->increment('attempts');
            return false;
        }

        $history->update([
            'verified_at' => now(),
        ]);

        return true;
    }
}
