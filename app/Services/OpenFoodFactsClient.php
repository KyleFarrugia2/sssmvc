<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenFoodFactsClient
{
    private const BASE_URL = 'https://world.openfoodfacts.org/cgi/search.pl';

    public function hasMatch(string $term): bool
    {
        return count($this->search($term)) > 0;
    }

    public function firstNutrients(string $term): array
    {
        $product = collect($this->search($term))->first();
        if (! $product || ! isset($product['nutriments'])) {
            return [];
        }

        $nutriments = $product['nutriments'];

        return [
            'calories_per_100g' => (int) round($nutriments['energy-kcal_100g'] ?? 0),
            'protein' => (float) ($nutriments['proteins_100g'] ?? 0),
            'carbs' => (float) ($nutriments['carbohydrates_100g'] ?? 0),
            'fat' => (float) ($nutriments['fat_100g'] ?? 0),
            'source' => $product['brands_tags'][0] ?? $product['brands'] ?? 'OpenFoodFacts',
        ];
    }

    private function search(string $term): array
    {
        $response = Http::timeout(5)->acceptJson()->get(self::BASE_URL, [
            'search_terms' => $term,
            'search_simple' => 1,
            'action' => 'process',
            'json' => 1,
            'page_size' => 2,
        ]);

        if ($response->failed()) {
            return [];
        }

        return $response->json('products', []);
    }
}


