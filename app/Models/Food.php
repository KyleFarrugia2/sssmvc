<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Food Model
 * - Has many meal entries
 * - Uses slug for route binding (SEO-friendly URLs)
 * - Nutrition data auto-filled from OpenFoodFacts API
 */
class Food extends Model
{
    use HasFactory;

    protected $table = 'foods';

    protected $fillable = [
        'name',              // Food name (e.g., "Chicken Breast")
        'slug',              // URL-friendly version (e.g., "chicken-breast")
        'calories_per_100g', // Calories per 100g
        'protein',           // Protein in grams per 100g
        'carbs',            // Carbohydrates in grams per 100g
        'fat',              // Fat in grams per 100g
        'source',           // Data source (e.g., "OpenFoodFacts", "Manual entry")
    ];

    // Relationship: A food can appear in multiple meal entries
    public function mealEntries()
    {
        return $this->hasMany(MealEntry::class);
    }

    // Use slug instead of id for URLs (SEO-friendly)
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}

