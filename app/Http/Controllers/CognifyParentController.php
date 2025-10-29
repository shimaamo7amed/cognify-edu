<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SMSRequest;
use App\Services\PasswordService;
use App\Http\Requests\VerifyOtpRequest;
use App\Services\CognifyParentServices;
use App\Http\Requests\ParentLoginRequest;
use App\Http\Requests\CognifyParentRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\ForgetPasswordRequest;

class CognifyParentController extends Controller
{

    protected $CognifyParentServices;

    public function __construct(CognifyParentServices $CognifyParentServices)
    {
        $this->CognifyParentServices = $CognifyParentServices;
    }
    public function register(Request $request)
    {
        $response = $this->CognifyParentServices->ParentRegister($request->all());
        if ($response) {
            return apiResponse(true, [], __('messages.otp_success'));
        } else {
            return apiResponse(false, [], __('messages.otp_failed'));
        }

    }

    public function verifyPhoneOtp(SMSRequest $request)
    {
        return $this->CognifyParentServices->verifyOTP($request->validated());

    }

    public function parentLogin(ParentLoginRequest $request)
    {
        $parent = $this->CognifyParentServices->loginParent($request->validated());
    
        if ($parent) {
            $token = $parent->createToken('parent_token')->plainTextToken;
    
            return apiResponse(true, [
                'token' => $token,
                'data' => $parent
            ], __('messages.parent_login_success'));
        } else {
            return apiResponse(false, [], __('messages.parent_login_failed'));
        }
    }
    public function ForgetPassword(ForgetPasswordRequest $data)
    {
        $parent = PasswordService::ForgetPassword($data->validated());
            if ($parent) {
                return apiResponse(true, [], __('messages.parent_forget_success'));

            }
            else{
                return apiResponse(false, [], __('messages.parent_forget_failed'));

            }

    }
    public function VerifyOtp(VerifyOtpRequest $request)
    {
        $result = PasswordService::VerifyForgetOtp($request->validated());

        if ($result['status']) {
            return apiResponse(true, [], __('messages.parent_otp_success'));
        } else {
            return apiResponse(false, [], $result['message']);
        }
    }


    public function ResetPassword(ResetPasswordRequest $data)
    {
        $parent = PasswordService::ResetPassword($data->validated());
            return response()->json([
                'success' => $result['status'],
                'message' => $result['message'],
            ]);

    }


}