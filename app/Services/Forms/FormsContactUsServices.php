<?php
namespace App\Services\Forms;
use App\Models\ContactUs;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Mail;
use Filament\Notifications\Notification;


class FormsContactUsServices
{

    static public function ContactUs(array $array)
    {
        $data = ContactUs::create($array);
        // dd($data);
        $emailData = [
            'name'    => $data->name,
            'phoneNumber'   => $data->phoneNumber,
            'email'   => $data->email,
            'message' => $data->message,
            'date'    => $data->date,
        ];
        Mail::send('mails.contact_us', ['emailData' => $emailData], function ($message) {
        $message->to('shimaa0mohamed19@gmail.com')
            ->subject('New Contact Form Submission');
        });

        Notification::make()
            ->title('New Contact Form Submission')
            ->body("A new message from {$data->name}.")
            ->send();
        return $data;
    }




}
