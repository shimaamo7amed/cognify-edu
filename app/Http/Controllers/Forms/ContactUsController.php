<?php

namespace App\Http\Controllers\Forms;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Forms\ContactUsRequest;
use App\Services\Forms\FormsContactUsServices;

class ContactUsController extends Controller
{
    protected $service;

    public function __construct(FormsContactUsServices $service)
    {
    $this->FormsContactUsServices = $service;
    }
    public function ContactUS(ContactUsRequest $data)
    {
        $formData=$this->FormsContactUsServices->ContactUS($data->validated());
        // dd($formData);
        if ($formData) {
            return apiResponse(true, ['formData' => $formData], __('messages.contact_success'));
        }
        else {
            return apiResponse(false, [], __('messages.contact_failed'), 400);
        }
    }
}
