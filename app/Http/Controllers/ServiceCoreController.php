<?php

namespace App\Http\Controllers;

use App\Models\ServiceCore;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceCoreResource;

class ServiceCoreController extends Controller
{
    public function getAllServices()
    {
        return ServiceCoreResource::collection(
            ServiceCore::whereNull('parent_id')->get()
        );
    }

 public function getServiceCoreById($id)
{
    $service = ServiceCore::with('children')->findOrFail($id);

    return new ServiceCoreResource($service);
}




}