<?php

namespace App\Http\Controllers;

use App\Models\Events;
use Illuminate\Http\Request;
use App\Http\Resources\EventsResource;

class EventsController extends Controller
{

    public function allEvents()
    {
        return EventsResource::collection(Events::all());
    }


}
