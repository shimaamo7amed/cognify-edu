<?php
namespace App\Services\Forms;
use App\Models\JoinUs;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Mail;
use Filament\Notifications\Notification;


class FormsJoinUsServices
{

  static public function JoinUS(array $array)
  {
        $data = JoinUs::create($array);
    // dd($data);
    $emailData = [
        'name'    => $data->name,
        'age'   => $data->age,
        'qualified' => $data->qualified,
        'phone'   => $data->phone,
    ];
    Mail::send('mails.join_us', ['emailData' => $emailData], function ($message) {
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

