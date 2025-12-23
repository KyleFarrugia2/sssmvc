<?php

namespace App\Http\Requests;

use App\Rules\OpenFoodFactsExists;
use App\Services\OpenFoodFactsClient;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FoodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $foodId = $this->food?->id;

        return [
            'name' => [
                'required',
                'string',
                'max:120',
                Rule::unique('foods', 'name')->ignore($foodId),
                new OpenFoodFactsExists(app(OpenFoodFactsClient::class)),
            ],
            'calories_per_100g' => ['required', 'integer', 'min:0', 'max:1500'],
            'protein' => ['required', 'numeric', 'min:0', 'max:200'],
            'carbs' => ['required', 'numeric', 'min:0', 'max:300'],
            'fat' => ['required', 'numeric', 'min:0', 'max:200'],
        ];
    }
}

