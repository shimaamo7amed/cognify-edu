<?php

namespace App\Http\Controllers\Observation;

use App\Models\SessionSlot;
use Illuminate\Http\Request;
use App\Services\SlotService;
use App\Http\Controllers\Controller;
use App\Http\Requests\SessionInfoRequest;
use App\Http\Resources\ObservationSessionResource;

class ObservationSessionSlotController extends Controller
{
    public function __construct(SlotService $slotService)
    {
        $this->slotService = $slotService;
    }


    public function allSessionSlots()
    {
        $result = $this->slotService->getGroupedSlots();
        return response()->json([
            'availableTimes' => $result,
        ]);
    }

    public function getSessionInfo(Request $request)
    {
        $session = $this->slotService->sessionInfo();
        if (!$session) {
            return apiResponse(false, [], __('messages.session_info_failed'));
        }
        return apiResponse(true, $session, __('messages.session_info_success'));
    }

    // public function getSessionInfo(SessionInfoRequest $request)
    // {
    //     $slot = $this->slotService->findSessionByDateTime($request->date, $request->time);
    //     if (! $slot) {
    //         return apiResponse(false, [], __('messages.seesion_slot_failed'));

    //     }
    //     return new ObservationSessionResource($slot->session);
    // }
}
