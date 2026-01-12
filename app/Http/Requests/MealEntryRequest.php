<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MealEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'food_id' => ['required', 'exists:foods,id'],
            'meal_type' => ['required', 'string', 'max:40'],
            'quantity_grams' => ['required', 'integer', 'min:1', 'max:5000'],
            'notes' => ['nullable', 'string'],
        ];
    }
}



