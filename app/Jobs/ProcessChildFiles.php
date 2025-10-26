<?php

namespace App\Jobs;

use App\Models\CognifyChild;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProcessChildFiles implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $child;
    protected $tempPhotoPath;
    protected $tempAudioPath;

    public function __construct(CognifyChild $child, ?string $tempPhotoPath, ?string $tempAudioPath)
    {
        $this->child = $child;
        $this->tempPhotoPath = $tempPhotoPath;
        $this->tempAudioPath = $tempAudioPath;
    }

    public function handle(): void
    {
        if ($this->tempPhotoPath) {
            $newPhotoPath = $this->processAndMoveImage($this->tempPhotoPath);
            $this->child->update(['childPhoto' => $newPhotoPath]);
        }

        if ($this->tempAudioPath) {
            $newAudioPath = $this->moveAudioFile($this->tempAudioPath);
            $this->child->update(['voiceRecording' => $newAudioPath]);
        }
    }

    private function processAndMoveImage(string $tempPath): string
    {
        $newPath = str_replace('/temp', '', $tempPath);

        $image = Image::make(storage_path('app/public/' . $tempPath));

        if ($image->width() > 800) {
            $image->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        $image->save(storage_path('app/public/' . $newPath), 80);
        Storage::disk('public')->delete($tempPath);

        return $newPath;
    }

    private function moveAudioFile(string $tempPath): string
    {
        $newPath = str_replace('/temp', '', $tempPath);
        Storage::disk('public')->move($tempPath, $newPath);
        return $newPath;
    }
}
