<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SMSService
{
    protected $apiBase;
    protected $username;
    protected $password;
    protected $sender;

    public function __construct()
    {
        $this->apiBase = env('SMS_API_BASE');
        $this->username = env('SMS_USERNAME');
        $this->password = env('SMS_PASSWORD');
        $this->sender = env('SMS_SENDER');
    }

    public function sendSMS($recipient, $message)
    {
        // Kazumi يحتاج receiver وليس to، ويحتاج language = e
        $payload = [
            'username' => $this->username,
            'password' => $this->password,
            'message'  => $message,
            'language' => 'e', // e = English (أو "a" لو الرسالة بالعربي)
            'sender'   => $this->sender,
            'receiver' => $recipient,
        ];

        // تسجيل الطلب في log
        Log::info('Sending SMS via Kazumi', $payload);

        $response = Http::asJson()->post($this->apiBase, $payload);

        Log::info('Kazumi SMS response', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        if ($response->successful()) {
            return [
                'status' => true,
                'response' => $response->json(),
            ];
        }

        return [
            'status' => false,
            'error' => $response->body(),
        ];
    }
}
