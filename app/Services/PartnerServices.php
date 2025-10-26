<?php
namespace App\Services;
use App\Models\Partner;
use Filament\Facades\Filament;
use App\Mail\PartnerRequestMail;
use Illuminate\Support\Facades\Mail;
use Filament\Notifications\Notification;

class PartnerServices
{

    static public function partnerRegister(array $array)
    {
        $serviceItemIds = $array['service_items'];

// dd($serviceItemIds);

        unset($array['service_items']);
        $partner = Partner::create($array);
        $partner->serviceItems()->attach($serviceItemIds);

        Mail::to('shimaa0mohamed19@gmail.com')->send(new PartnerRequestMail($partner));
    }





}
