<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'meal_plan_id',
        'food_id',
        'meal_type',
        'quantity_grams',
        'notes',
    ];

    public function mealPlan()
    {
        return $this->belongsTo(MealPlan::class);
    }

    public function food()
    {
        return $this->belongsTo(Food::class);
    }

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


