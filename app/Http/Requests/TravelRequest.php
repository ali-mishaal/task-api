<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TravelRequest extends FormRequest
{
    public function rules(): array
    {
        return [

            "is_public" => 'required|boolean',
            "name" => 'required|string|max:225|unique:users,id',
            "description" => 'required',
            "number_of_days" => 'required|numeric'
        ];
    }
}
