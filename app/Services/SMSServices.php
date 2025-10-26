<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SMSService
{
    protected $username;
    protected $password;
    protected $sender;

    public function __construct()
    {
        $this->username = env('SMS_USERNAME');
        $this->password = env('SMS_PASSWORD');
        $this->sender   = env('SMS_SENDER');
    }

    public function sendSMS($receiver, $message, $language = 'e')
    {
        $payload = [
            'username' => $this->username,
            'password' => $this->password,
            'message'  => $message,
            'language' => $language,
            'sender'   => $this->sender,
            'receiver' => $receiver,
        ];

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post(env('SMS_API_BASE'), $payload);
        if ($response->successful()) {
            return $response->json();
        }

        return [
            'status' => false,
            'error' => $response->body(),
            'code' => $response->status(),
        ];
    }
}
