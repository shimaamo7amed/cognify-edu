<?php

namespace App\Models;

use App\Models\CognifyChild;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Filament\Notifications\Notification;

class Report extends Model
{
    protected $table = 'reports';

    protected $fillable = [
        'cognify_children_id',
        'doc',
        'status',
        'created_by',
        'employee_id',
        'report_type'
    ];

    protected static function booted()
    {
        // Send notification when report is created
        static::created(function ($report) {
            $report->sendReportCreatedNotification();
        });

        // Send notification when report status is updated to finalized
        static::updated(function ($report) {
            if ($report->wasChanged('status') && $report->status === 'finalized') {
                $report->sendReportFinalizedNotification();
            }
        });
    }

    public function child(): BelongsTo
    {
        return $this->belongsTo(CognifyChild::class, 'cognify_children_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function getDocUrlAttribute(): string
    {
        return asset('storage/' . $this->doc);
    }

    public function sendReportCreatedNotification()
    {
        // Get all admins who should be notified
        $recipients = Admin::all(); // Or filter specific admins: Admin::where('role', 'supervisor')->get();

        $childName = $this->child->name ?? 'Unknown Child';
        $creatorName = $this->creator->name ?? 'System';

        foreach ($recipients as $recipient) {
            Notification::make()
                ->title('New Report Created')
                ->body("A new report has been created for {$childName} by {$creatorName}")
                ->icon('heroicon-o-document-text')
                ->iconColor('info')
                ->actions([
                    \Filament\Notifications\Actions\Action::make('view')
                        ->button()
                        ->label('View Report')
                        ->url($this->doc_url)
                        ->openUrlInNewTab()
                        ->markAsRead(),
                ])
                ->sendToDatabase($recipient);
        }
    }

    public function sendReportFinalizedNotification()
    {
        // Get all admins who should be notified
        $recipients = Admin::all();

        $childName = $this->child->name ?? 'Unknown Child';
        $creatorName = $this->creator->name ?? 'System';

        foreach ($recipients as $recipient) {
            Notification::make()
                ->title('Report Finalized')
                ->body("The report for {$childName} has been finalized by {$creatorName}")
                ->icon('heroicon-o-document-check')
                ->iconColor('success')
                ->actions([
                    \Filament\Notifications\Actions\Action::make('download')
                        ->button()
                        ->label('Download Report')
                        ->url($this->doc_url)
                        ->openUrlInNewTab()
                        ->markAsRead(),
                ])
                ->sendToDatabase($recipient);
        }
    }

    public function sendReportSentNotification()
    {
        // Get all admins who should be notified
        $recipients = Admin::all();

        $childName = $this->child->name ?? 'Unknown Child';
        $parentEmail = $this->child->parent->email ?? 'Parent';

        foreach ($recipients as $recipient) {
            Notification::make()
                ->title('Report Sent to Parent')
                ->body("The report for {$childName} has been sent to {$parentEmail}")
                ->icon('heroicon-o-paper-airplane')
                ->iconColor('warning')
                ->sendToDatabase($recipient);
        }
    }
}
