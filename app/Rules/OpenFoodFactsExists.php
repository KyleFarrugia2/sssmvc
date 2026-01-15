<?php

namespace App\Rules;

use App\Services\OpenFoodFactsClient;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * OpenFoodFactsExists Validation Rule
 * 
 * Validates that food name exists in OpenFoodFacts API before saving.
 * Used in FoodRequest validation.
 */
class OpenFoodFactsExists implements ValidationRule
{
    public function __construct(private OpenFoodFactsClient $client)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            if (! $this->client->hasMatch($value)) {
                $fail("The food '{$value}' was not found on OpenFoodFacts API. Please try a more common food name or check your spelling.");
            }
        } catch (\RuntimeException $e) {
            $fail($e->getMessage());
        } catch (\Exception $e) {
            $fail("Unable to verify food with OpenFoodFacts API. Please try again in a moment.");
        }
    }
}



