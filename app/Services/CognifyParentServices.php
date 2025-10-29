<?php
namespace App\Services;
use Illuminate\Support\Str;
use App\Services\SMSService;
use App\Models\CognifyParent;
use Filament\Facades\Filament;
use App\Mail\ResetPasswordCode;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Filament\Notifications\Notification;

class CognifyParentServices
{
    public function ParentRegister(array $array)
    {
        // $emailOtp = rand(100000, 999999);
        $emailOtp = 123456; // للاختبار فقط
        $smsOtp   = rand(100000, 999999);
        $cacheKey = 'parent_register_' . $array['phone'];

        Cache::put($cacheKey, [
            'data' => $array,
            'email_otp' => $emailOtp,
            'sms_otp' => $smsOtp,
            'verified' => false,
        ], now()->addMinutes(10));


        Mail::send('mails.parent-otp', ['otp' => $emailOtp], function ($message) use ($array) {
            $message->to($array['email'])
                    ->subject('Your OTP Verification Code');
        });

        app(SMSService::class)->sendSMS(
            $array['phone'],
            "Your Cognify verification code is: {$smsOtp}"
        );

        return apiResponse(true, [], __('messages.otp_sent'));
    }
    public function verifyOTP(array $data)
    {
        $phone = $data['phone'];
        $enteredEmailOtp = $data['email_otp'] ?? null;
        $enteredSmsOtp   = $data['sms_otp'] ?? null;

        $cacheKey = 'parent_register_' . $phone;
        $cached = Cache::get($cacheKey);

        if (!$cached) {
            return apiResponse(false, [], __('messages.otp_expired'));
        }

        // منع الاستخدام المتكرر
        if ($cached['verified'] === true) {
            return apiResponse(false, [], __('messages.otp_already_verified'));
        }

        // التحقق من الكودين معًا
        if (
            $cached['email_otp'] != $enteredEmailOtp ||
            $cached['sms_otp'] != $enteredSmsOtp
        ) {
            return apiResponse(false, [], __('messages.invalid_otp'));
        }

        $array = $cached['data'];

        // إنشاء الحساب بعد تحقق الكودين
        $parent = CognifyParent::create([
            'name'        => $array['name'] ?? null,
            'email'       => $array['email'],
            'phone'       => $array['phone'] ?? null,
            'address'     => $array['address'] ?? null,
            'governorate' => $array['governorate'] ?? null,
            'password'    => $array['password'],
            'role'        => $array['role'] ?? 'parent',
            'step'        => 2,
        ]);

        // حذف الكاش بعد أول تحقق ناجح
        Cache::forget($cacheKey);

        $token = $parent->createToken('parent_token')->plainTextToken;

        return apiResponse(true, [
            'token' => $token,
            'parent' => $parent,
        ], __('messages.register_success'));
    }

    public function loginParent(array $array)
    {
        $parent = CognifyParent::where('email', $array['email'])->first();
        if (!$parent) {
            return false;
        }
        if (!auth('parent')->attempt(['email' => $array['email'], 'password' => $array['password']])) {
            return false;
        }
        return  $parent->load('review');

    }

    // public function ForgetPassword(array $array)
    // {
    //     $query = CognifyParent::query();
    //     if (!empty($array['email'])) {
    //         $query->where('email', $array['email']);
    //     } elseif (!empty($array['phone'])) {
    //         $query->where('phone', $array['phone']);
    //     }

    //     $user = $query->first();
    //     if (!$user) {
    //         return null;
    //     }
    //     // $emailOtp = rand(100000, 999999);
    //     $emailOtp = 123456;
    //     $smsOtp   = rand(100000, 999999);
    //     $cacheKey = 'parent_forget_' . ($user->phone ?? $user->email);
    //     Cache::put($cacheKey, [
    //         'user_id'   => $user->id,
    //         'email_otp' => $emailOtp,
    //         'sms_otp'   => $smsOtp,
    //         'verified'  => false,
    //     ], now()->addMinutes(10));
    //     if (!empty($user->email)) {
    //         Mail::send('mails.parent-otp', ['otp' => $emailOtp], function ($message) use ($user) {
    //             $message->to($user->email)
    //                     ->subject('Your Password Reset Code');
    //         });
    //     }
    //     if (!empty($user->phone)) {
    //         app(SMSService::class)->sendSMS(
    //             $user->phone,
    //             "Your Cognify password reset code is: {$smsOtp}"
    //         );
    //     }

    //     return $user;
    // }


    // public function ValidateOTP(array $array)
    // {
    //     $user = CognifyParent::where([
    //         "otp" => $otp,
    //     ])->first();
    //     if ($user) {
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

    // public function ResetPassword(array $array)
    // {
    //     $parent = CognifyParent::where('otp', $array['otp'])->first();
    //     // dd($parent);
    //     if (!$parent)
    //     {
    //         return apiResponse(false, [], __('messages.parent_otp_failed'));
    //     }
    //     $parent->password = $array['password'];
    //     $parent->save();
    //     return $parent;
    // }


}
