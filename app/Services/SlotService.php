<?php
namespace App\Services;

use App\Models\SessionSlot;
use Illuminate\Support\Carbon;
use App\Models\ObservationSession;
use App\Http\Resources\ObservationSessionResource;

class SlotService
{


    public function getGroupedSlots(): array
    {
        $slots = SessionSlot::where('is_booked', false)
            ->with('session')
            ->get();

        $grouped = [];
        $locale = request()->header('Accept-Language', 'en');
        $isArabic = strtolower($locale) === 'ar';
        $today = Carbon::today();

        foreach ($slots as $slot) {
            $start = Carbon::parse($slot->start_time);
            $end = Carbon::parse($slot->end_time);
            $interval = $slot->interval;
            $date = $start->format('Y-m-d');
            $sessionId = $slot->session ? $slot->session->id : null;

            // الشرط: ما نعرضش إلا الأيام الحالية أو المستقبلية
            if ($start->lt($today)) {
                continue;
            }

            if (!isset($grouped[$date])) {
                $grouped[$date] = [
                    'id' => $sessionId,
                    'date' => $date,
                    'times' => [],
                ];
            }

            $current = $start->copy();
            while ($current->lt($end)) {
                if ($isArabic) {
                    $hour = (int)$current->format('H');
                    $formatted = $current->format('g:i');
                    $period = $hour < 12 ? 'ص' : 'م';
                    $time = $formatted . ' ' . $period;
                } else {
                    $time = $current->format('g:i A');
                }

                if (!in_array($time, $grouped[$date]['times'])) {
                    $grouped[$date]['times'][] = $time;
                }

                $current->addMinutes((int) $interval);
            }
        }

        return array_values($grouped);
    }

    // public function findSessionByDateTime(string $date, string $time): ?SessionSlot
    // {
    //     $targetDateTime = Carbon::parse("$date $time");

    //     return SessionSlot::whereDate('start_time', $date)
    //         ->where('is_booked', false)
    //         ->with('session')
    //         ->get()
    //         ->first(function ($slot) use ($targetDateTime) {
    //             return Carbon::parse($slot->start_time)->lte($targetDateTime)
    //                 && Carbon::parse($slot->end_time)->gte($targetDateTime);
    //         });
    // }
    public function sessionInfo()
    {
        $sessions = ObservationSession::all();
        return ObservationSessionResource::collection($sessions);
    }

}

