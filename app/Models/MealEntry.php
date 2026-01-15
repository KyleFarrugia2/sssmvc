<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * MealEntry Model
 * - Belongs to a meal plan and a food
 * - Calculates calories/protein/carbs/fat based on quantity and food's per-100g values
 */
class MealEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'meal_plan_id',   // Foreign key to meal_plans table
        'food_id',        // Foreign key to foods table
        'meal_type',      // Type of meal: 'Breakfast', 'Lunch', 'Dinner', 'Snack'
        'quantity_grams', // Amount of food in grams
        'notes',          // Optional notes
    ];

    public function mealPlan()
    {
        return $this->belongsTo(MealPlan::class);
    }

    public function food()
    {
        return $this->belongsTo(Food::class);
    }

    // Calculate calories: (quantity_grams / 100) * food->calories_per_100g
    public function getCaloriesAttribute(): int
    {
        return (int) round($this->quantity_grams * $this->food->calories_per_100g / 100);
    }

    public function getProteinAttribute(): float
    {
        return round($this->quantity_grams * $this->food->protein / 100, 2);
    }

    public function getCarbsAttribute(): float
    {
        return round($this->quantity_grams * $this->food->carbs / 100, 2);
    }

    public function getFatAttribute(): float
    {
        return round($this->quantity_grams * $this->food->fat / 100, 2);
    }
}



