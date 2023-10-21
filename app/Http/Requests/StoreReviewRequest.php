<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:199',
            'description' => 'required|max:600',
            'grade' => 'required|between:1,5',
            'title' => 'required|max:199',
            'email' => 'required|email|max:199',
        ];
    }
}
