<?php

namespace App\Services;

use App\Models\CognifyParent;
use App\Services\SMSService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

class PasswordService
{
    public static function ForgetPassword(array $array)
    {
        $input = $array['email_or_phone'] ?? null;

        $user = CognifyParent::where('email', $input)
            ->orWhere('phone', $input)
            ->first();

        if (!$user) {
            return [
                'status' => false,
                'message' => __('messages.user_not_found'),
            ];
        }

        $otp = 123456; // للاختبار فقط
        $cacheKey = 'parent_forget_' . $input;

        Cache::put($cacheKey, [
            'user_id' => $user->id,
            'otp' => $otp,
            'verified' => false,
        ], now()->addMinutes(10));

        if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
            Mail::send('mails.parent-otp', ['otp' => $otp], function ($message) use ($user) {
                $message->to($user->email)->subject(__('messages.password_reset_subject'));
            });
        } else {
            app(SMSService::class)->sendSMS(
                $user->phone,
                __('messages.password_reset_sms', ['otp' => $otp])
            );
        }

        return [
            'status' => true,
            'message' => __('messages.otp_sent'),
        ];
    }

    public static function VerifyForgetOtp(array $array)
    {
        $input = $array['email_or_phone'] ?? null;
        $cacheKey = 'parent_forget_' . $input;

        $cachedData = Cache::get($cacheKey);

        if (!$cachedData) {
            return [
                'status' => false,
                'message' => __('messages.otp_expired'),
            ];
        }

        if ($cachedData['otp'] != $array['otp']) {
            return [
                'status' => false,
                'message' => __('messages.invalid_otp'),
            ];
        }

        $cachedData['verified'] = true;
        Cache::put($cacheKey, $cachedData, now()->addMinutes(10));

        return [
            'status' => true,
            'message' => __('messages.otp_verified'),
        ];
    }

    public static function ResetPassword(array $array)
    {
        $input = $array['email_or_phone'] ?? null;
        $cacheKey = 'parent_forget_' . $input;

        $cachedData = Cache::get($cacheKey);

        if (!$cachedData) {
            return [
                'status' => false,
                'message' => __('messages.otp_expired_or_not_verified'),
            ];
        }

        if (empty($cachedData['verified'])) {
            return [
                'status' => false,
                'message' => __('messages.verify_otp_first'),
            ];
        }

        $user = CognifyParent::find($cachedData['user_id']);

        if (!$user) {
            return [
                'status' => false,
                'message' => __('messages.user_not_found'),
            ];
        }

        $user->update([
            'password' => bcrypt($array['password']),
        ]);

        Cache::forget($cacheKey);

        return [
            'status' => true,
            'message' => __('messages.password_reset_success'),
        ];
    }
}
