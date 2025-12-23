<?php

namespace Database\Seeders;

use App\Models\Food;
use App\Models\MealEntry;
use App\Models\MealPlan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'demo@nutritrack.test'],
            [
                'name' => 'Demo Nutritionist',
                'password' => Hash::make('password'),
            ]
        );

        $foods = collect([
            ['name' => 'Roasted Chicken Breast', 'calories_per_100g' => 165, 'protein' => 31, 'carbs' => 0, 'fat' => 3.6],
            ['name' => 'Brown Rice', 'calories_per_100g' => 112, 'protein' => 2.6, 'carbs' => 23, 'fat' => 0.8],
            ['name' => 'Steamed Broccoli', 'calories_per_100g' => 55, 'protein' => 3.7, 'carbs' => 11, 'fat' => 0.6],
        ])->map(function ($item) {
            return Food::firstOrCreate(
                ['slug' => Str::slug($item['name'])],
                $item + ['source' => 'Seed data', 'slug' => Str::slug($item['name'])]
            );
        });

        $plan = MealPlan::firstOrCreate(
            ['slug' => 'demo-plan'],
            [
                'user_id' => $user->id,
                'title' => 'Demo Athlete Plan',
                'planned_on' => now()->toDateString(),
                'goal_calories' => 2200,
                'notes' => 'Seeded sample to demonstrate totals.',
            ]
        );

        if ($plan->entries()->count() === 0) {
            MealEntry::create([
                'meal_plan_id' => $plan->id,
                'food_id' => $foods[0]->id,
                'meal_type' => 'Lunch',
                'quantity_grams' => 180,
                'notes' => 'Grilled with herbs.',
            ]);

            MealEntry::create([
                'meal_plan_id' => $plan->id,
                'food_id' => $foods[1]->id,
                'meal_type' => 'Lunch',
                'quantity_grams' => 150,
                'notes' => 'Short grain.',
            ]);

            MealEntry::create([
                'meal_plan_id' => $plan->id,
                'food_id' => $foods[2]->id,
                'meal_type' => 'Lunch',
                'quantity_grams' => 120,
                'notes' => 'Lightly salted.',
            ]);
        }
    }
}
