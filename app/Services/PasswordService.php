<?php

namespace App\Services;

use App\Models\CognifyParent;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordCode;

class PasswordService
{
    public function forgetPassword(array $data)
    {
        $user = CognifyParent::where('email', $data['email'])->first();

        $expiresAt = now()->addMinutes(10); // صلاحية 10 دقائق

        Mail::to($user->email)->send(
            new ResetPasswordCode($data['otp'], $user->name, $user->email)
        );

        $user->update([
            'otp' => $data['otp'],
            'otp_expires_at' => $expiresAt
        ]);

        return $user;
    }

    public function validateOtp(array $data)
    {
        // dd($data);
        $user = CognifyParent::where('email', $data['email'])
            ->where('otp', $data['otp'])
            ->where('otp_expires_at', '>=', now())
            ->first();

        return $user;
    }

    public function resetPassword(array $data)
    {
        $user = $this->validateOtp($data);

        if (!$user) {
            return false;
        }

        $user->update([
            'password' => bcrypt($data['password']),
            'otp' => null,
            'otp_expires_at' => null
        ]);

        return $user;
    }
}
