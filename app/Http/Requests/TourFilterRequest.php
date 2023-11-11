<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TourFilterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'dateFrom' => 'date',
            'dateTo' => 'date',
            'priceFrom' => 'numeric',
            'priceTo' => 'numeric',
            'sortBy' => Rule::in('price'),
            'sortOrder' => Rule::in(['asc', 'desc']),
        ];
    }
}
