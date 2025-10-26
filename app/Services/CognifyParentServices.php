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
    // static public function ParentRegister(array $array)
    // {
    //     $otp = rand(100000, 999999);
    //     $array['otp'] = $otp;
    //     Cache::put('parent_data_' . $array['email'], $array, now()->addMinutes(10));
    //     Mail::send('mails.parent-otp', ['otp' => $otp], function ($message) use ($array) {
    //         $message->to($array['email'])
    //                 ->subject('Your OTP Verification Code');
    //     });
    //     return true;
    // }

    // static public function verifyOTP(string $email, string $otp)
    // {
    //     $cachedData = cache()->get('parent_data_' . $email);

    //     if (!$cachedData || $cachedData['otp'] != $otp) {
    //         return false;
    //     }

    //     $parent = CognifyParent::create([
    //         'name' => $cachedData['name'] ?? null,
    //         'email' => $cachedData['email'],
    //         'phone' => $cachedData['phone'] ?? null,
    //         'address' => $cachedData['address'] ?? null,
    //         'governorate' => $cachedData['governorate'] ?? null,
    //         'password' => $cachedData['password'],
    //         'role'        => $cachedData['role'] ?? 'parent',
    //         'step' => 1
    //     ]);

    //     cache()->forget('parent_data_' . $email);

    //     return $parent;
    // }

    public function ParentRegister(array $array)
    {
        $otp = rand(100000, 999999);
        $cacheKey = 'parent_register_' . $array['phone'];
        Cache::put($cacheKey, [
            'data' => $array,
            'otp' => $otp,
        ], now()->addMinutes(10));

        $key=app(SMSService::class)->sendSMS($array['phone'], "Your Cognify verification code is: {$otp}");
        if (!$key) {
            return false;
        }
        return  true;

    }

    public function verifyOTP(array $data)
    {
        $phone = $data['phone'];
        $enteredOtp = $data['otp'];

        $cacheKey = 'parent_register_' . $phone;
        $cached = Cache::get($cacheKey);

        if (!$cached) {
            return apiResponse(false, [], __('messages.otp_expired'));
        }

        if ($cached['otp'] != $enteredOtp) {
            return apiResponse(false, [], __('messages.invalid_otp'));
        }

        $array = $cached['data'];
        $parent = CognifyParent::create([
            'name'        => $array['name'] ?? null,
            'email'       => $array['email'],
            'phone'       => $array['phone'] ?? null,
            'address'     => $array['address'] ?? null,
            'governorate' => $array['governorate'] ?? null,
            'password'    => $array['password'],
            'role'        => $array['role'] ?? 'parent',
            'step'        => 2
        ]);

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
