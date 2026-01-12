<?php

namespace App\Rules;

use App\Services\OpenFoodFactsClient;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class OpenFoodFactsExists implements ValidationRule
{
    public function __construct(private OpenFoodFactsClient $client)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $this->client->hasMatch($value)) {
            $fail("{$value} was not found on OpenFoodFacts. Try a more common food name.");
        }
    }
}



