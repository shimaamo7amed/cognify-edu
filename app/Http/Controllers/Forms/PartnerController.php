<?php

namespace App\Http\Controllers\Forms;
use Illuminate\Http\Request;
use App\Models\Service;

use App\Services\PartnerServices;
use App\Http\Controllers\Controller;
use App\Http\Requests\PartnerRequest;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\ServiceIDResource;


class PartnerController extends Controller
{
      public function getAllServices()
    {

        return ServiceResource::collection(Service::with('items')->get());
    }

    public function getServiceById($id)
    {
        $service = Service::with('items')->find($id);
        if(!$service)
        {
            
          return apiResponse(false,[],__('messages.noservice'));
        }
          return new ServiceIDResource($service);
    }

    public function partnerRegister(PartnerRequest $request)
    {
        PartnerServices::partnerRegister($request->validated());
            return apiResponse(true, [], __('messages.partner_success'));

    }
}