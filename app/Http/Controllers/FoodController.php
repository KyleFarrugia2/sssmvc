<?php

namespace App\Http\Controllers;

use App\Http\Requests\FoodRequest;
use App\Models\Food;
use App\Services\OpenFoodFactsClient;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * FoodController
 * 
 * Handles food CRUD operations with OpenFoodFacts API integration.
 * Implements filtering and sorting via GET parameters.
 */
class FoodController extends Controller
{
    // Display foods with filtering and sorting
    // Filtering: search by name
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'sort', 'direction']);
        
        // Extract sort parameter from GET request (e.g., ?sort=name&direction=asc)
        // Whitelist validation: Only allow specific columns to prevent SQL injection
        // Allowed: 'name', 'calories_per_100g', 'protein'
        // If invalid/missing, default to 'name'
        $sort = in_array($filters['sort'] ?? null, ['name', 'calories_per_100g', 'protein'], true)
            ? $filters['sort']  // Use provided sort if it's in whitelist
            : 'name';           // Default fallback if invalid
        
        // Extract direction from GET request (?direction=asc or ?direction=desc)
        // Validate: Only 'asc' or 'desc' allowed
        // Default to 'asc' if not provided or invalid
        $direction = ($filters['direction'] ?? 'asc') === 'desc' ? 'desc' : 'asc';

        $foods = Food::query()
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            // Use validated sort column and direction
            // Laravel's orderBy() safely handles the column name (already whitelisted)
            ->orderBy($sort, $direction)
            ->paginate(8)
            // withQueryString() preserves ?sort=...&direction=... in pagination links
            // Without this, clicking page 2 would lose your sort settings
            ->withQueryString();

        return view('foods.index', compact('foods', 'filters'));
    }

    public function create()
    {
        return view('foods.create');
    }

    // Store new food: fetch nutrition data from API and save
    public function store(FoodRequest $request, OpenFoodFactsClient $client)
    {
        $data = $request->validated();
        $data['slug'] = $this->makeSlug($data['name']);

        try {
            $fromApi = $client->firstNutrients($data['name']);
            
            // Check if API returned empty or all zeros (no meaningful nutrition data)
            if (empty($fromApi) || $this->hasNoNutritionData($fromApi)) {
                return back()->withErrors([
                    'name' => "The food '{$data['name']}' was not found or has no nutrition data on OpenFoodFacts. Please check the spelling (e.g., 'avocado' not 'avacado') or try a different food name."
                ])->withInput();
            }

            // Check if the matched product name is very different from search term
            // This catches cases like "avacado" matching "Avacado & Lime Flavour Sauce"
            $matchedName = $fromApi['matched_product_name'] ?? '';
            if (!empty($matchedName) && $this->isPoorMatch($data['name'], $matchedName)) {
                return back()->withErrors([
                    'name' => "Found product '{$matchedName}' which doesn't closely match '{$data['name']}'. Please check spelling (e.g., 'avocado' not 'avacado') or be more specific with the food name."
                ])->withInput();
            }

            // Remove matched_product_name from API data (it's not a database field)
            unset($fromApi['matched_product_name']);
            
            $payload = array_merge($data, array_filter($fromApi, fn ($value) => $value !== null && $value !== ''));
            
            Food::create($payload + ['source' => $fromApi['source'] ?? 'Manual entry']);

            return redirect()->route('foods.index')->with('success', 'Food item added.');
        } catch (\RuntimeException $e) {
            return back()->withErrors([
                'name' => $e->getMessage()
            ])->withInput();
        } catch (\Exception $e) {
            return back()->withErrors([
                'name' => "Unable to fetch nutrition data from OpenFoodFacts API. Please try again in a moment."
            ])->withInput();
        }
    }

    public function edit(Food $food)
    {
        return view('foods.edit', compact('food'));
    }

    // Update food: fetch fresh data from API
    public function update(FoodRequest $request, Food $food, OpenFoodFactsClient $client)
    {
        $data = $request->validated();

        // Only regenerate slug if name changed
        if ($food->name !== $data['name']) {
            $data['slug'] = $this->makeSlug($data['name']);
        }

        try {
            // Fetch fresh data from API
            $fromApi = $client->firstNutrients($data['name']);
            
            // Check if API returned empty or all zeros (no meaningful nutrition data)
            if (empty($fromApi) || $this->hasNoNutritionData($fromApi)) {
                return back()->withErrors([
                    'name' => "The food '{$data['name']}' was not found or has no nutrition data on OpenFoodFacts. Please check the spelling (e.g., 'avocado' not 'avacado') or try a different food name."
                ])->withInput();
            }

            // Check if the matched product name is very different from search term
            $matchedName = $fromApi['matched_product_name'] ?? '';
            if (!empty($matchedName) && $this->isPoorMatch($data['name'], $matchedName)) {
                return back()->withErrors([
                    'name' => "Found product '{$matchedName}' which doesn't closely match '{$data['name']}'. Please check spelling (e.g., 'avocado' not 'avacado') or be more specific with the food name."
                ])->withInput();
            }

            // Remove matched_product_name from API data (it's not a database field)
            unset($fromApi['matched_product_name']);
            
            // Merge and update
            $payload = array_merge($data, array_filter($fromApi, fn ($value) => $value !== null && $value !== ''));
            
            $food->update($payload + ['source' => $fromApi['source'] ?? $food->source]);

            return redirect()->route('foods.index')->with('success', 'Food updated.');
        } catch (\RuntimeException $e) {
            return back()->withErrors([
                'name' => $e->getMessage()
            ])->withInput();
        } catch (\Exception $e) {
            return back()->withErrors([
                'name' => "Unable to fetch nutrition data from OpenFoodFacts API. Please try again in a moment."
            ])->withInput();
        }
    }

    public function destroy(Food $food)
    {
        $food->delete();

        return redirect()->route('foods.index')->with('success', 'Food removed.');
    }

    private function makeSlug(string $name): string
    {
        $base = Str::slug($name);
        $count = Food::where('slug', 'like', "{$base}%")->count();

        return $count ? "{$base}-" . ($count + 1) : $base;
    }

    /**
     * Check if API response has no meaningful nutrition data (all zeros)
     * This happens when API finds a product but it has no nutrition info
     */
    private function hasNoNutritionData(array $apiData): bool
    {
        // Check if calories, protein, carbs, and fat are all zero
        // This means the API found a product but it has no nutrition data
        $calories = $apiData['calories_per_100g'] ?? 0;
        $protein = $apiData['protein'] ?? 0;
        $carbs = $apiData['carbs'] ?? 0;
        $fat = $apiData['fat'] ?? 0;
        
        // If all nutrition values are zero, there's no meaningful data
        return $calories == 0 && $protein == 0 && $carbs == 0 && $fat == 0;
    }

    /**
     * Check if the matched product name is a poor match for the search term
     * This helps catch cases like "avacado" matching "Avacado & Lime Flavour Sauce"
     */
    private function isPoorMatch(string $searchTerm, string $matchedName): bool
    {
        $searchLower = strtolower(trim($searchTerm));
        $matchedLower = strtolower(trim($matchedName));
        
        // Exact match is good
        if ($matchedLower === $searchLower) {
            return false;
        }
        
        // If matched name contains the search term as a whole word, it's probably okay
        // e.g., "avocado" in "Fresh Avocado" is fine
        if (preg_match('/\b' . preg_quote($searchLower, '/') . '\b/', $matchedLower)) {
            return false;
        }
        
        // If matched name is much longer and contains extra words, it might be a processed food
        // e.g., "avacado" -> "Avacado & Lime Flavour Sauce" (bad match)
        $searchWords = explode(' ', $searchLower);
        $matchedWords = explode(' ', $matchedLower);
        
        // If matched name has 3+ more words than search, it's probably a different product
        if (count($matchedWords) > count($searchWords) + 2) {
            // But check if most search words are in the matched name
            $matchingWords = count(array_intersect($searchWords, $matchedWords));
            if ($matchingWords < count($searchWords) * 0.7) {
                return true; // Poor match
            }
        }
        
        return false;
    }
}
