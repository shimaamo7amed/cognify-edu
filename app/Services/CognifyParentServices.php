<?php
namespace App\Services;
use App\Models\CognifyParent;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordCode;
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
        $parent = CognifyParent::create([
            'name' => $array['name'] ?? null,
            'email' => $array['email'],
            'phone' => $array['phone'] ?? null,
            'address' => $array['address'] ?? null,
            'governorate' => $array['governorate'] ?? null,
            'password' => $array['password'],
            'role'        => $array['role'] ?? 'parent',
            'step' => 2
        ]);
        if (!$parent) {
            return false;
        }
        return $parent->load('review');
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
