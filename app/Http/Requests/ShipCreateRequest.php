<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShipCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' =>'required',
            'latitude' =>'required|numeric|min:-90|max:90',
            'longitude' =>'required|numeric|min:-180|max:180',
            'radius' =>'required|numeric|max:9999999|gt:0',
            'price' =>'required|numeric|gt:0',
        ];
    }
}
