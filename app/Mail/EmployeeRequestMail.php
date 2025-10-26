<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmployeeRequestMail extends Mailable
{
    use Queueable, SerializesModels;
    public $employee;

    public function __construct($employee)
    {
        $this->employee = $employee;
    }

    public function build()
    {
        return $this->subject('employee Request')
        ->view('mails.employee-request');
    }

}
