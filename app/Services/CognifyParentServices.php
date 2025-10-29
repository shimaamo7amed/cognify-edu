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

    static function loginParent(array $array)
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

    // static public function ForgetPassword(array $array)
    // {
    //     $user = CognifyParent::where('email', $array['email'])->first();
    //     if (!$user)
    //     {
    //         return null;
    //     }
    //     $otp = $array['otp'];
    //     Mail::to($user->email)->send(new ResetPasswordCode($otp, $user->name, $user->email));
    //     $user->otp = $otp;
    //     $user->save();

    //     return $user;
    // }

    // static public function ResetPassword(array $array)
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
