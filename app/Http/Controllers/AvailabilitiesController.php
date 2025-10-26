<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObservationSession;

class AvailabilitiesController extends Controller
{
    public function index()
    {
        $sessions = ObservationSession::all();
        if (!$sessions) {
            return apiResponse(fale, [], __('messages.no_data_found'));
        }
        return apiResponse(true,$sessions,__('messages.data_found'));
    }

}