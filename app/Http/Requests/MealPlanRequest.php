<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MealPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:120'],
            'planned_on' => ['required', 'date'],
            'goal_calories' => ['required', 'integer', 'min:1', 'max:20000'],
            'notes' => ['nullable', 'string'],
            'owner_name' => ['required', 'string', 'max:80', 'regex:/^[a-zA-Z\s\-\'\.]+$/'],
            'owner_email' => ['required', 'email', 'max:120'],
        ];
    }
}


