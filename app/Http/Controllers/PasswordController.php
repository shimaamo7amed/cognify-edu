<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgetPasswordRequest;
use App\Http\Requests\ValidateOtpRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\PasswordService;

class PasswordController extends Controller
{
    public function __construct(private PasswordService $passwordService)
    {
    }

    public function forget(ForgetPasswordRequest $request)
    {
        $user = $this->passwordService->forgetPassword($request->validated());
        if (!$user) {
            return apiResponse(false, [], __('messages.parent_otp_failed'));
        }

        return apiResponse(true, [], __('messages.otp_sent'));
    }

    public function validateOtp(ValidateOtpRequest $request)
    {
        $user = $this->passwordService->validateOtp($request->validated());

        if (!$user) {
            return apiResponse(false, [], __('messages.parent_otp_failed'));
        }

        return apiResponse(true, [], __('messages.otp_valid'));
    }

    public function reset(ResetPasswordRequest $request)
    {
        $user = $this->passwordService->resetPassword($request->validated());

        if (!$user) {
            return apiResponse(false, [], __('messages.parent_otp_failed'));
        }

        return apiResponse(true, $user, __('messages.password_reset_success'));
    }
}
