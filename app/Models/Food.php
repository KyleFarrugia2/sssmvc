<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;

    protected $table = 'foods';

    protected $fillable = [
        'name',
        'slug',
        'calories_per_100g',
        'protein',
        'carbs',
        'fat',
        'source',
    ];

    public function mealEntries()
    {
        return $this->hasMany(MealEntry::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}

