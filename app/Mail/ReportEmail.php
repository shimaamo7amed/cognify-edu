<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReportEmail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $data;
    public $child;
    public $pdfContent;

    public function __construct($data, $child, $pdfContent)
    {
        $this->data = $data;
        $this->child = $child;
        $this->pdfContent = $pdfContent;
    }

    public function build()
    {
        return $this->subject('Observation Report for ' . $this->child->name)
                    ->markdown('emails.reports.report')
                    ->attachData($this->pdfContent, 'report.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}
