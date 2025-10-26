<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PartnerRequestMail extends Mailable
{
    use Queueable, SerializesModels;
    public $partner;

    public function __construct($partner)
    {
        $this->partner = $partner;
    }

    public function build()
    {
        return $this->subject('Partner Request')
        ->view('mails.partner-request');
    }

}