<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * OpenFoodFactsClient Service
 * 
 * Integrates with OpenFoodFacts API to validate food names and fetch nutrition data.
 * - hasMatch(): Checks if food exists (used in validation)
 * - firstNutrients(): Fetches nutrition data (calories, protein, carbs, fat)
 * - Timeout: 15 seconds
 */
class OpenFoodFactsClient
{
    private const BASE_URL = 'https://world.openfoodfacts.org/cgi/search.pl';
    private const TIMEOUT = 15;

    // Check if food exists in OpenFoodFacts (used in validation)
    public function hasMatch(string $term): bool
    {
        try {
            return count($this->search($term)) > 0;
        } catch (\Exception $e) {
            Log::warning("OpenFoodFacts API error for '{$term}': " . $e->getMessage());
            return false;
        }
    }

    // Get nutrition data from first matching food in API
    public function firstNutrients(string $term): array
    {
        try {
            $products = $this->search($term);
            
            if (empty($products)) {
                Log::info("OpenFoodFacts: No products found for '{$term}'");
                return [];
            }

            // Score products: prefer products where name closely matches search term
            // This helps avoid getting processed foods when searching for fresh ingredients
            $scoredProducts = [];
            $searchLower = strtolower(trim($term));
            
            // Keywords that indicate processed foods (penalize these)
            $processedKeywords = ['mayo', 'mayonnaise', 'oil', 'sauce', 'dressing', 'spread', 'dip', 
                                 'chips', 'crisps', 'flavour', 'flavor', 'flavored', 'flavoured',
                                 'spray', 'powder', 'extract', 'paste', 'puree'];
            
            foreach ($products as $product) {
                if (!isset($product['nutriments'])) {
                    continue;
                }

                $nutriments = $product['nutriments'];
                $productName = strtolower($product['product_name'] ?? '');
                
                // Extract nutrition values
                $calories = $this->extractCalories($nutriments);
                $protein = (float) ($nutriments['proteins_100g'] ?? 0);
                $carbs = (float) ($nutriments['carbohydrates_100g'] ?? 0);
                $fat = (float) ($nutriments['fat_100g'] ?? 0);
                
                // Skip if no nutrition data
                if ($calories == 0 && $protein == 0 && $carbs == 0 && $fat == 0) {
                    continue;
                }
                
                // Score: exact match = 100, contains search term = 50, similar = 25
                $score = 0;
                if ($productName === $searchLower) {
                    $score = 100; // Exact match - best!
                } elseif (preg_match('/\b' . preg_quote($searchLower, '/') . '\b/', $productName)) {
                    // Search term appears as whole word (e.g., "avocado" in "Fresh Avocado")
                    $score = 80;
                } elseif (strpos($productName, $searchLower) !== false) {
                    $score = 50; // Contains search term
                } else {
                    // Check similarity (simple check - if first few chars match)
                    $searchWords = explode(' ', $searchLower);
                    $productWords = explode(' ', $productName);
                    $matchingWords = count(array_intersect($searchWords, $productWords));
                    $score = $matchingWords * 10; // 10 points per matching word
                }
                
                // Penalize processed foods
                foreach ($processedKeywords as $keyword) {
                    if (strpos($productName, $keyword) !== false) {
                        $score -= 50; // Penalty for processed foods
                        break;
                    }
                }
                
                // Penalize products with suspiciously high calories for fresh foods
                // Fresh avocado should be ~160-200 kcal, not 700+
                // If calories are > 400 and it's a simple food name, it's probably processed
                if ($calories > 400 && count(explode(' ', $searchLower)) <= 2) {
                    $score -= 30; // Penalty for high-calorie "simple" foods
                }
                
                // Add points for products that are just the food name or "fresh/ripe/ready" + food name
                if (preg_match('/^(fresh|ripe|ready|organic|raw)?\s*' . preg_quote($searchLower, '/') . '\s*$/i', $productName)) {
                    $score += 20; // Points for simple, fresh products
                }
                
                $scoredProducts[] = [
                    'score' => $score,
                    'product' => $product,
                    'calories' => $calories,
                    'protein' => $protein,
                    'carbs' => $carbs,
                    'fat' => $fat,
                ];
            }
            
            // Sort by score (highest first) and return best match
            if (empty($scoredProducts)) {
                // No products with nutrition data
                return [];
            }
            
            usort($scoredProducts, fn($a, $b) => $b['score'] <=> $a['score']);
            
            // Find the best product that's not obviously wrong
            // Reject products with suspiciously high calories for simple food searches
            $best = null;
            foreach ($scoredProducts as $candidate) {
                $candidateName = strtolower($candidate['product']['product_name'] ?? '');
                $candidateCalories = $candidate['calories'];
                
                // For simple food names (1-2 words), reject if calories are way too high
                // Common fresh foods: fruits ~50-100, vegetables ~20-50, nuts ~500-600, oils ~800-900
                // If searching for a simple name and getting >600 calories, it's probably processed
                $searchWordCount = count(explode(' ', $searchLower));
                if ($searchWordCount <= 2 && $candidateCalories > 600) {
                    // Check if it's a known high-calorie food (nuts, oils, etc.)
                    $highCalorieFoods = ['nut', 'seed', 'oil', 'butter', 'fat', 'lard'];
                    $isHighCalorieFood = false;
                    foreach ($highCalorieFoods as $hcf) {
                        if (strpos($searchLower, $hcf) !== false) {
                            $isHighCalorieFood = true;
                            break;
                        }
                    }
                    
                    // If it's not a known high-calorie food, skip this candidate
                    if (!$isHighCalorieFood) {
                        Log::info("OpenFoodFacts: Rejecting '{$candidateName}' ({$candidateCalories} kcal) - too high for simple food search '{$term}'");
                        continue;
                    }
                }
                
                $best = $candidate;
                break;
            }
            
            // If we filtered out all candidates, use the highest scoring one anyway
            if ($best === null) {
                $best = $scoredProducts[0];
            }
            
            // Log what product was selected for debugging
            $selectedName = $best['product']['product_name'] ?? 'Unknown';
            Log::info("OpenFoodFacts: Selected product '{$selectedName}' (score: {$best['score']}, calories: {$best['calories']}) for search '{$term}'");
            
            // If score is low, the match might not be good - log a warning
            if ($best['score'] < 30) {
                Log::warning("OpenFoodFacts: Low match score ({$best['score']}) for '{$term}' -> '{$selectedName}'. User might want to check spelling.");
            }
            
            return [
                'calories_per_100g' => $best['calories'],
                'protein' => $best['protein'],
                'carbs' => $best['carbs'],
                'fat' => $best['fat'],
                'source' => $best['product']['brands_tags'][0] ?? $best['product']['brands'] ?? 'OpenFoodFacts',
                'matched_product_name' => $selectedName, // Include for potential validation
            ];
        } catch (\Exception $e) {
            Log::warning("OpenFoodFacts API error for '{$term}': " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Extract calories from nutriments, handling both kcal and kJ units
     * Always use _100g suffix to ensure we get per 100g values, not per serving
     */
    private function extractCalories(array $nutriments): int
    {
        // Priority 1: energy-kcal_100g (explicit kcal per 100g - most reliable)
        if (isset($nutriments['energy-kcal_100g']) && $nutriments['energy-kcal_100g'] > 0) {
            return (int) round($nutriments['energy-kcal_100g']);
        }
        
        // Priority 2: energy-kj_100g converted to kcal (1 kcal = 4.184 kJ)
        if (isset($nutriments['energy-kj_100g']) && $nutriments['energy-kj_100g'] > 0) {
            return (int) round($nutriments['energy-kj_100g'] / 4.184);
        }
        
        // Priority 3: energy_100g - check the unit to determine if it's kcal or kJ
        if (isset($nutriments['energy_100g']) && $nutriments['energy_100g'] > 0) {
            $energy = $nutriments['energy_100g'];
            $unit = $nutriments['energy_unit'] ?? '';
            
            // If unit is explicitly kJ, convert it
            if (strtolower($unit) === 'kj' || strtolower($unit) === 'kilojoule' || strtolower($unit) === 'kilojoules') {
                return (int) round($energy / 4.184);
            }
            
            // If unit is kcal or not specified, check value range
            // Fresh foods are typically 0-500 kcal per 100g
            // If value > 500, it's likely in kJ (e.g., 824 kJ = ~197 kcal)
            if ($energy > 500) {
                // Likely in kJ, convert it
                return (int) round($energy / 4.184);
            }
            
            // Otherwise assume it's in kcal
            return (int) round($energy);
        }
        
        // DO NOT use energy-kcal or energy-kj without _100g suffix - those are per serving!
        
        return 0;
    }

    // Search OpenFoodFacts API for products
    private function search(string $term): array
    {
        try {
            $response = Http::timeout(self::TIMEOUT)
                ->acceptJson()
                ->get(self::BASE_URL, [
                    'search_terms' => $term,
                    'search_simple' => 1,
                    'action' => 'process',
                    'json' => 1,
                    'page_size' => 20, // Increased to get more results and find fresh products, not just processed ones
                ]);

            if ($response->failed()) {
                Log::warning("OpenFoodFacts API request failed for '{$term}': " . $response->status());
                return [];
            }

            $products = $response->json('products', []);
            
            if (empty($products)) {
                Log::info("OpenFoodFacts: No products in response for '{$term}'");
            }
            
            return $products;
        } catch (ConnectionException $e) {
            throw new \RuntimeException("The OpenFoodFacts API is taking too long to respond. Please try again in a moment or use a different food name.");
        } catch (RequestException $e) {
            throw new \RuntimeException("Unable to connect to OpenFoodFacts API. Please try again later.");
        }
    }
}



