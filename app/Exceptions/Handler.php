<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\App;
use Throwable;

class Handler extends ExceptionHandler
{
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        $message = App::getLocale() === 'ar'
            ? 'غير مصرح. يجب تسجيل الدخول أولاً.'
            : 'Unauthenticated. Please login first.';

        return response()->json([
            'status' => false,
            'message' => $message,
        ], 401);
    }

    public function render($request, Throwable $e)
    {
        return parent::render($request, $e);
    }
}