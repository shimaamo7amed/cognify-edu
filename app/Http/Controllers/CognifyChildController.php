<?php

namespace App\Http\Controllers;

use App\Models\CognifyChild;
use Illuminate\Http\Request;
use App\Models\CognifyParent;
use App\Services\ChildServices;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\CognifyChildRequest;

class CognifyChildController extends Controller
{
    protected $ChildServices;

    public function __construct(ChildServices $ChildServices)
    {
        $this->ChildServices = $ChildServices;
    }
    public function chiledcognify(CognifyChildRequest $request): JsonResponse
    {
        $result = $this->ChildServices->processChildCognify($request);

        if ($result['success']) {
            return apiResponse(true, $result['data'], __('messages.child_success'));
        }

        return apiResponse(false, [], $result['message'] ?? __('messages.child_failed'));
    }

    public function details()
    {
        $parent = $this->ChildServices->details();

        if ($parent) {
            return apiResponse(true, [
                'parent' => $parent,
                'token'  => auth()->user()->createToken('API Token')->plainTextToken,
                'step'   => auth()->user()->step,
                'role'   => auth()->user()->role,
            ], __('messages.child_success'));
        } else {
            return apiResponse(false, [], __('messages.child_failed'));
        }
    }
 



}
