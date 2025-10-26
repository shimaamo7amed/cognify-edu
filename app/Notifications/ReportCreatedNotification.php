<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReportCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $report;
    protected $action;

    public function __construct(Report $report, string $action = 'created')
    {
        $this->report = $report;
        $this->action = $action;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $childName = $this->report->child->name ?? 'Unknown Child';
        $creatorName = $this->report->creator->name ?? 'System';

        $messages = [
            'created' => "A new report has been created for {$childName} by {$creatorName}",
            'finalized' => "The report for {$childName} has been finalized by {$creatorName}",
            'sent' => "The report for {$childName} has been sent to parent",
        ];

        $icons = [
            'created' => 'heroicon-o-document-text',
            'finalized' => 'heroicon-o-document-check',
            'sent' => 'heroicon-o-paper-airplane',
        ];

        return [
            'title' => 'Report ' . ucfirst($this->action),
            'body' => $messages[$this->action] ?? 'Report action performed',
            'icon' => $icons[$this->action] ?? 'heroicon-o-document',
            'report_id' => $this->report->id,
            'child_id' => $this->report->cognify_children_id,
            'action' => $this->action,
        ];
    }
}

// Usage in your Report model:
// use App\Notifications\ReportCreatedNotification;
//
// foreach ($recipients as $recipient) {
//     $recipient->notify(new ReportCreatedNotification($this, 'created'));
// }
