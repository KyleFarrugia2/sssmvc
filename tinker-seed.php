<?php

/**
 * Tinker Script for Seeding Dummy Data
 * Run this in Tinker with: php artisan tinker < tinker-seed.php
 * Or copy and paste the commands directly into Tinker
 */

use App\Models\User;
use App\Models\Food;
use App\Models\MealPlan;
use App\Models\MealEntry;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

// Clear existing data (optional - comment out if you want to keep existing data)
// MealEntry::truncate();
// MealPlan::truncate();
// Food::truncate();
// User::where('email', '!=', 'admin@nutritrack.test')->delete();

// Create Users
$user1 = User::firstOrCreate(
    ['email' => 'john@nutritrack.test'],
    [
        'name' => 'John Doe',
        'password' => Hash::make('password123'),
    ]
);

$user2 = User::firstOrCreate(
    ['email' => 'jane@nutritrack.test'],
    [
        'name' => 'Jane Smith',
        'password' => Hash::make('password123'),
    ]
);

echo "Created users: {$user1->name}, {$user2->name}\n";

// Create Foods
$foodsData = [
    ['name' => 'Grilled Chicken Breast', 'calories_per_100g' => 165, 'protein' => 31.0, 'carbs' => 0.0, 'fat' => 3.6, 'source' => 'USDA'],
    ['name' => 'Salmon Fillet', 'calories_per_100g' => 208, 'protein' => 20.0, 'carbs' => 0.0, 'fat' => 12.0, 'source' => 'USDA'],
    ['name' => 'Brown Rice', 'calories_per_100g' => 112, 'protein' => 2.6, 'carbs' => 23.0, 'fat' => 0.8, 'source' => 'USDA'],
    ['name' => 'Quinoa', 'calories_per_100g' => 120, 'protein' => 4.4, 'carbs' => 22.0, 'fat' => 1.9, 'source' => 'USDA'],
    ['name' => 'Steamed Broccoli', 'calories_per_100g' => 55, 'protein' => 3.7, 'carbs' => 11.0, 'fat' => 0.6, 'source' => 'USDA'],
    ['name' => 'Sweet Potato', 'calories_per_100g' => 86, 'protein' => 1.6, 'carbs' => 20.0, 'fat' => 0.1, 'source' => 'USDA'],
    ['name' => 'Greek Yogurt', 'calories_per_100g' => 59, 'protein' => 10.0, 'carbs' => 3.6, 'fat' => 0.4, 'source' => 'USDA'],
    ['name' => 'Almonds', 'calories_per_100g' => 579, 'protein' => 21.0, 'carbs' => 22.0, 'fat' => 50.0, 'source' => 'USDA'],
    ['name' => 'Banana', 'calories_per_100g' => 89, 'protein' => 1.1, 'carbs' => 23.0, 'fat' => 0.3, 'source' => 'USDA'],
    ['name' => 'Avocado', 'calories_per_100g' => 160, 'protein' => 2.0, 'carbs' => 9.0, 'fat' => 15.0, 'source' => 'USDA'],
    ['name' => 'Whole Wheat Bread', 'calories_per_100g' => 247, 'protein' => 13.0, 'carbs' => 41.0, 'fat' => 4.2, 'source' => 'USDA'],
    ['name' => 'Eggs', 'calories_per_100g' => 155, 'protein' => 13.0, 'carbs' => 1.1, 'fat' => 11.0, 'source' => 'USDA'],
];

$foods = [];
foreach ($foodsData as $foodData) {
    $slug = Str::slug($foodData['name']);
    $food = Food::firstOrCreate(
        ['slug' => $slug],
        array_merge($foodData, ['slug' => $slug])
    );
    $foods[] = $food;
}

echo "Created " . count($foods) . " foods\n";

// Create Meal Plans for User 1
$plan1 = MealPlan::firstOrCreate(
    ['slug' => 'john-monday-plan'],
    [
        'user_id' => $user1->id,
        'title' => 'Monday Workout Day',
        'planned_on' => now()->toDateString(),
        'goal_calories' => 2500,
        'notes' => 'High protein day for muscle recovery',
    ]
);

$plan2 = MealPlan::firstOrCreate(
    ['slug' => 'john-tuesday-plan'],
    [
        'user_id' => $user1->id,
        'title' => 'Tuesday Balanced Day',
        'planned_on' => now()->addDay()->toDateString(),
        'goal_calories' => 2200,
        'notes' => 'Balanced macros for maintenance',
    ]
);

// Create Meal Plans for User 2
$plan3 = MealPlan::firstOrCreate(
    ['slug' => 'jane-week-plan'],
    [
        'user_id' => $user2->id,
        'title' => 'Weekly Meal Prep',
        'planned_on' => now()->toDateString(),
        'goal_calories' => 1800,
        'notes' => 'Vegetarian focused meal plan',
    ]
);

echo "Created 3 meal plans\n";

// Create Meal Entries for Plan 1 (John's Monday)
if ($plan1->entries()->count() === 0) {
    MealEntry::create([
        'meal_plan_id' => $plan1->id,
        'food_id' => $foods[0]->id, // Grilled Chicken Breast
        'meal_type' => 'Breakfast',
        'quantity_grams' => 200,
        'notes' => 'Grilled with herbs and spices',
    ]);

    MealEntry::create([
        'meal_plan_id' => $plan1->id,
        'food_id' => $foods[2]->id, // Brown Rice
        'meal_type' => 'Breakfast',
        'quantity_grams' => 150,
        'notes' => 'Steamed',
    ]);

    MealEntry::create([
        'meal_plan_id' => $plan1->id,
        'food_id' => $foods[1]->id, // Salmon Fillet
        'meal_type' => 'Lunch',
        'quantity_grams' => 180,
        'notes' => 'Baked with lemon',
    ]);

    MealEntry::create([
        'meal_plan_id' => $plan1->id,
        'food_id' => $foods[3]->id, // Quinoa
        'meal_type' => 'Lunch',
        'quantity_grams' => 200,
        'notes' => 'Cooked with vegetable broth',
    ]);

    MealEntry::create([
        'meal_plan_id' => $plan1->id,
        'food_id' => $foods[4]->id, // Steamed Broccoli
        'meal_type' => 'Lunch',
        'quantity_grams' => 150,
        'notes' => 'Lightly seasoned',
    ]);

    MealEntry::create([
        'meal_plan_id' => $plan1->id,
        'food_id' => $foods[6]->id, // Greek Yogurt
        'meal_type' => 'Snack',
        'quantity_grams' => 200,
        'notes' => 'With honey',
    ]);

    MealEntry::create([
        'meal_plan_id' => $plan1->id,
        'food_id' => $foods[7]->id, // Almonds
        'meal_type' => 'Snack',
        'quantity_grams' => 30,
        'notes' => 'Raw unsalted',
    ]);
}

// Create Meal Entries for Plan 2 (John's Tuesday)
if ($plan2->entries()->count() === 0) {
    MealEntry::create([
        'meal_plan_id' => $plan2->id,
        'food_id' => $foods[11]->id, // Eggs
        'meal_type' => 'Breakfast',
        'quantity_grams' => 200, // ~3-4 eggs
        'notes' => 'Scrambled with vegetables',
    ]);

    MealEntry::create([
        'meal_plan_id' => $plan2->id,
        'food_id' => $foods[10]->id, // Whole Wheat Bread
        'meal_type' => 'Breakfast',
        'quantity_grams' => 100,
        'notes' => '2 slices toasted',
    ]);

    MealEntry::create([
        'meal_plan_id' => $plan2->id,
        'food_id' => $foods[0]->id, // Grilled Chicken Breast
        'meal_type' => 'Dinner',
        'quantity_grams' => 150,
        'notes' => 'With mixed vegetables',
    ]);

    MealEntry::create([
        'meal_plan_id' => $plan2->id,
        'food_id' => $foods[5]->id, // Sweet Potato
        'meal_type' => 'Dinner',
        'quantity_grams' => 200,
        'notes' => 'Baked',
    ]);
}

// Create Meal Entries for Plan 3 (Jane's Weekly Plan)
if ($plan3->entries()->count() === 0) {
    MealEntry::create([
        'meal_plan_id' => $plan3->id,
        'food_id' => $foods[6]->id, // Greek Yogurt
        'meal_type' => 'Breakfast',
        'quantity_grams' => 250,
        'notes' => 'With fresh berries',
    ]);

    MealEntry::create([
        'meal_plan_id' => $plan3->id,
        'food_id' => $foods[8]->id, // Banana
        'meal_type' => 'Breakfast',
        'quantity_grams' => 120,
        'notes' => 'Medium sized',
    ]);

    MealEntry::create([
        'meal_plan_id' => $plan3->id,
        'food_id' => $foods[3]->id, // Quinoa
        'meal_type' => 'Lunch',
        'quantity_grams' => 180,
        'notes' => 'Quinoa salad with vegetables',
    ]);

    MealEntry::create([
        'meal_plan_id' => $plan3->id,
        'food_id' => $foods[9]->id, // Avocado
        'meal_type' => 'Lunch',
        'quantity_grams' => 100,
        'notes' => 'Sliced on top',
    ]);

    MealEntry::create([
        'meal_plan_id' => $plan3->id,
        'food_id' => $foods[4]->id, // Steamed Broccoli
        'meal_type' => 'Dinner',
        'quantity_grams' => 200,
        'notes' => 'With garlic and olive oil',
    ]);

    MealEntry::create([
        'meal_plan_id' => $plan3->id,
        'food_id' => $foods[5]->id, // Sweet Potato
        'meal_type' => 'Dinner',
        'quantity_grams' => 150,
        'notes' => 'Roasted',
    ]);
}

echo "Created meal entries for all plans\n";
echo "\n=== Summary ===\n";
echo "Users: " . User::count() . "\n";
echo "Foods: " . Food::count() . "\n";
echo "Meal Plans: " . MealPlan::count() . "\n";
echo "Meal Entries: " . MealEntry::count() . "\n";
echo "\nDone! Data seeded successfully.\n";

