<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShipStationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'serviceName' => 'required',
            'serviceCode' => 'required',
            'carrierCode' => 'required',
            'shipmentCost' => 'required',
            'otherCost' => 'required',
        ];
    }
}
