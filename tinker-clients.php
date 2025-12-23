<?php

use App\Models\User;
use App\Models\Food;
use App\Models\MealPlan;
use App\Models\MealEntry;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

echo "=== Creating Clients with Meal Plans ===\n\n";

$clients = [
    ['name' => 'Sarah Johnson', 'email' => 'sarah.johnson@nutritrack.test', 'gender' => 'female', 'age' => 28, 'weight' => 70, 'height' => 165, 'activity' => 'moderate', 'goal' => 'lose', 'target' => 65],
    ['name' => 'Michael Chen', 'email' => 'michael.chen@nutritrack.test', 'gender' => 'male', 'age' => 35, 'weight' => 85, 'height' => 178, 'activity' => 'active', 'goal' => 'maintain', 'target' => 85],
    ['name' => 'Emma Williams', 'email' => 'emma.williams@nutritrack.test', 'gender' => 'female', 'age' => 24, 'weight' => 58, 'height' => 160, 'activity' => 'very_active', 'goal' => 'gain', 'target' => 62],
    ['name' => 'David Rodriguez', 'email' => 'david.rodriguez@nutritrack.test', 'gender' => 'male', 'age' => 42, 'weight' => 95, 'height' => 180, 'activity' => 'light', 'goal' => 'lose', 'target' => 85],
    ['name' => 'Olivia Brown', 'email' => 'olivia.brown@nutritrack.test', 'gender' => 'female', 'age' => 31, 'weight' => 68, 'height' => 170, 'activity' => 'moderate', 'goal' => 'maintain', 'target' => 68],
    ['name' => 'James Wilson', 'email' => 'james.wilson@nutritrack.test', 'gender' => 'male', 'age' => 29, 'weight' => 78, 'height' => 175, 'activity' => 'active', 'goal' => 'gain', 'target' => 82],
    ['name' => 'Sophia Martinez', 'email' => 'sophia.martinez@nutritrack.test', 'gender' => 'female', 'age' => 26, 'weight' => 62, 'height' => 163, 'activity' => 'moderate', 'goal' => 'lose', 'target' => 58],
    ['name' => 'Daniel Taylor', 'email' => 'daniel.taylor@nutritrack.test', 'gender' => 'male', 'age' => 38, 'weight' => 88, 'height' => 182, 'activity' => 'sedentary', 'goal' => 'lose', 'target' => 80],
    ['name' => 'Isabella Anderson', 'email' => 'isabella.anderson@nutritrack.test', 'gender' => 'female', 'age' => 22, 'weight' => 55, 'height' => 158, 'activity' => 'very_active', 'goal' => 'gain', 'target' => 58],
    ['name' => 'Christopher Lee', 'email' => 'christopher.lee@nutritrack.test', 'gender' => 'male', 'age' => 33, 'weight' => 82, 'height' => 177, 'activity' => 'active', 'goal' => 'maintain', 'target' => 82],
];

$foods = Food::all();
if ($foods->isEmpty()) {
    echo "⚠️  No foods found. Creating sample foods...\n";
    $foodData = [
        ['name' => 'Grilled Chicken Breast', 'calories_per_100g' => 165, 'protein' => 31.0, 'carbs' => 0.0, 'fat' => 3.6],
        ['name' => 'Salmon Fillet', 'calories_per_100g' => 208, 'protein' => 20.0, 'carbs' => 0.0, 'fat' => 12.0],
        ['name' => 'White Rice', 'calories_per_100g' => 130, 'protein' => 2.7, 'carbs' => 28.0, 'fat' => 0.3],
        ['name' => 'Pasta', 'calories_per_100g' => 131, 'protein' => 5.0, 'carbs' => 25.0, 'fat' => 1.1],
        ['name' => 'Eggs', 'calories_per_100g' => 155, 'protein' => 13.0, 'carbs' => 1.1, 'fat' => 11.0],
        ['name' => 'Oats', 'calories_per_100g' => 389, 'protein' => 16.9, 'carbs' => 66.3, 'fat' => 6.9],
        ['name' => 'Greek Yogurt 0% Fat', 'calories_per_100g' => 59, 'protein' => 10.0, 'carbs' => 3.6, 'fat' => 0.0],
        ['name' => 'Apples', 'calories_per_100g' => 52, 'protein' => 0.3, 'carbs' => 14.0, 'fat' => 0.2],
        ['name' => 'Banana', 'calories_per_100g' => 89, 'protein' => 1.1, 'carbs' => 23.0, 'fat' => 0.3],
        ['name' => 'Blueberries', 'calories_per_100g' => 57, 'protein' => 0.7, 'carbs' => 14.5, 'fat' => 0.3],
        ['name' => 'Egg Whites', 'calories_per_100g' => 52, 'protein' => 11.0, 'carbs' => 0.7, 'fat' => 0.2],
        ['name' => 'Lean Ground Beef', 'calories_per_100g' => 250, 'protein' => 26.0, 'carbs' => 0.0, 'fat' => 15.0],
        ['name' => 'Extra Virgin Olive Oil', 'calories_per_100g' => 884, 'protein' => 0.0, 'carbs' => 0.0, 'fat' => 100.0],
        ['name' => 'Quinoa', 'calories_per_100g' => 120, 'protein' => 4.4, 'carbs' => 22.0, 'fat' => 1.9],
        ['name' => 'Steamed Broccoli', 'calories_per_100g' => 55, 'protein' => 3.7, 'carbs' => 11.0, 'fat' => 0.6],
        ['name' => 'Sweet Potato', 'calories_per_100g' => 86, 'protein' => 1.6, 'carbs' => 20.0, 'fat' => 0.1],
        ['name' => 'Avocado', 'calories_per_100g' => 160, 'protein' => 2.0, 'carbs' => 9.0, 'fat' => 15.0],
    ];
    
    foreach ($foodData as $item) {
        Food::firstOrCreate(
            ['slug' => Str::slug($item['name'])],
            array_merge($item, ['slug' => Str::slug($item['name']), 'source' => 'Seed data'])
        );
    }
    $foods = Food::all();
    echo "✅ Created " . $foods->count() . " foods\n\n";
}

$createdUsers = [];
$createdPlans = 0;
$createdEntries = 0;

foreach ($clients as $clientData) {
    $user = User::updateOrCreate(
        ['email' => $clientData['email']],
        [
            'name' => $clientData['name'],
            'password' => Hash::make('password123'),
            'weight_kg' => $clientData['weight'],
            'height_cm' => $clientData['height'],
            'age' => $clientData['age'],
            'gender' => $clientData['gender'],
            'activity_level' => $clientData['activity'],
            'weight_goal' => $clientData['goal'],
            'target_weight_kg' => $clientData['target'],
        ]
    );

    $tdee = $user->calculateTDEE();
    if ($tdee === null) {
        $tdee = 2000;
    }

    $loseCalories = max(round($tdee - 500, 0), round($tdee * 0.8, 0));
    $gainCalories = round($tdee + 500, 0);

    $createdUsers[] = [
        'user' => $user,
        'lose_calories' => $loseCalories,
        'gain_calories' => $gainCalories,
        'tdee' => $tdee,
    ];

    $mealPlans = [
        [
            'title' => "{$user->name}'s Weight Loss Meal Plan",
            'goal_calories' => $loseCalories,
            'notes' => "Personalized meal plan designed for weight loss goal.",
            'goal' => 'lose',
        ],
        [
            'title' => "{$user->name}'s Weight Gain Meal Plan",
            'goal_calories' => $gainCalories,
            'notes' => "Personalized meal plan designed for weight gain goal.",
            'goal' => 'gain',
        ],
    ];

    $clientIndex = array_search($clientData['email'], array_column($clients, 'email'));
    $baseDate = now()->subDays(rand(5, 60));
    
    foreach ($mealPlans as $index => $planData) {
        $dayOffset = ($clientIndex * 4) + ($index * rand(2, 5));
        $planDate = $baseDate->copy()->addDays($dayOffset);
        $slug = Str::slug($user->name . ' ' . $planData['goal'] . ' ' . $planDate->format('Y-m-d'));

        $plan = MealPlan::firstOrCreate(
            ['slug' => $slug],
            [
                'user_id' => $user->id,
                'title' => $planData['title'],
                'planned_on' => $planDate->toDateString(),
                'goal_calories' => $planData['goal_calories'],
                'notes' => $planData['notes'],
            ]
        );

        if ($plan->wasRecentlyCreated) {
            $createdPlans++;

            $foodMap = [];
            foreach ($foods as $food) {
                $foodMap[$food->slug] = $food;
            }

            $mealCombinations = [
                'lose' => [
                    'Breakfast' => [
                        ['slug' => 'oats', 'qty' => 50],
                        ['slug' => 'greek-yogurt-0-fat', 'qty' => 150],
                        ['slug' => 'blueberries', 'qty' => 80],
                    ],
                    'Lunch' => [
                        ['slug' => 'grilled-chicken-breast', 'qty' => 150],
                        ['slug' => 'white-rice', 'qty' => 100],
                        ['slug' => 'steamed-broccoli', 'qty' => 150],
                    ],
                    'Dinner' => [
                        ['slug' => 'salmon-fillet', 'qty' => 120],
                        ['slug' => 'quinoa', 'qty' => 100],
                        ['slug' => 'sweet-potato', 'qty' => 100],
                    ],
                    'Snack' => [
                        ['slug' => 'apples', 'qty' => 150],
                    ],
                ],
                'gain' => [
                    'Breakfast' => [
                        ['slug' => 'eggs', 'qty' => 200],
                        ['slug' => 'oats', 'qty' => 80],
                        ['slug' => 'banana', 'qty' => 120],
                    ],
                    'Lunch' => [
                        ['slug' => 'lean-ground-beef', 'qty' => 200],
                        ['slug' => 'pasta', 'qty' => 150],
                        ['slug' => 'avocado', 'qty' => 100],
                    ],
                    'Dinner' => [
                        ['slug' => 'grilled-chicken-breast', 'qty' => 200],
                        ['slug' => 'white-rice', 'qty' => 150],
                        ['slug' => 'extra-virgin-olive-oil', 'qty' => 15],
                    ],
                    'Snack' => [
                        ['slug' => 'greek-yogurt-0-fat', 'qty' => 200],
                    ],
                ],
            ];

            $variations = [
                [
                    'lose' => [
                        'Breakfast' => [['slug' => 'egg-whites', 'qty' => 150], ['slug' => 'oats', 'qty' => 60], ['slug' => 'apples', 'qty' => 100]],
                        'Lunch' => [['slug' => 'grilled-chicken-breast', 'qty' => 120], ['slug' => 'quinoa', 'qty' => 120], ['slug' => 'steamed-broccoli', 'qty' => 200]],
                        'Dinner' => [['slug' => 'salmon-fillet', 'qty' => 150], ['slug' => 'sweet-potato', 'qty' => 150], ['slug' => 'steamed-broccoli', 'qty' => 100]],
                        'Snack' => [['slug' => 'blueberries', 'qty' => 100]],
                    ],
                    'gain' => [
                        'Breakfast' => [['slug' => 'eggs', 'qty' => 250], ['slug' => 'oats', 'qty' => 100], ['slug' => 'banana', 'qty' => 150]],
                        'Lunch' => [['slug' => 'lean-ground-beef', 'qty' => 180], ['slug' => 'white-rice', 'qty' => 180], ['slug' => 'avocado', 'qty' => 120]],
                        'Dinner' => [['slug' => 'grilled-chicken-breast', 'qty' => 180], ['slug' => 'pasta', 'qty' => 200], ['slug' => 'extra-virgin-olive-oil', 'qty' => 10]],
                        'Snack' => [['slug' => 'greek-yogurt-0-fat', 'qty' => 250], ['slug' => 'blueberries', 'qty' => 100]],
                    ],
                ],
                [
                    'lose' => [
                        'Breakfast' => [['slug' => 'greek-yogurt-0-fat', 'qty' => 200], ['slug' => 'oats', 'qty' => 40], ['slug' => 'blueberries', 'qty' => 100]],
                        'Lunch' => [['slug' => 'grilled-chicken-breast', 'qty' => 130], ['slug' => 'white-rice', 'qty' => 80], ['slug' => 'steamed-broccoli', 'qty' => 180]],
                        'Dinner' => [['slug' => 'salmon-fillet', 'qty' => 140], ['slug' => 'quinoa', 'qty' => 110], ['slug' => 'sweet-potato', 'qty' => 120]],
                        'Snack' => [['slug' => 'apples', 'qty' => 180]],
                    ],
                    'gain' => [
                        'Breakfast' => [['slug' => 'eggs', 'qty' => 220], ['slug' => 'oats', 'qty' => 90], ['slug' => 'banana', 'qty' => 140]],
                        'Lunch' => [['slug' => 'lean-ground-beef', 'qty' => 190], ['slug' => 'pasta', 'qty' => 160], ['slug' => 'avocado', 'qty' => 110]],
                        'Dinner' => [['slug' => 'grilled-chicken-breast', 'qty' => 190], ['slug' => 'white-rice', 'qty' => 160], ['slug' => 'extra-virgin-olive-oil', 'qty' => 12]],
                        'Snack' => [['slug' => 'greek-yogurt-0-fat', 'qty' => 220], ['slug' => 'banana', 'qty' => 100]],
                    ],
                ],
            ];

            $variationIndex = $clientIndex % count($variations);
            $selectedCombinations = $variations[$variationIndex][$planData['goal']] ?? $mealCombinations[$planData['goal']];

            foreach ($selectedCombinations as $mealType => $foodItems) {
                foreach ($foodItems as $item) {
                    if (isset($foodMap[$item['slug']])) {
                        $food = $foodMap[$item['slug']];
                        $quantity = $item['qty'];
                        
                        if ($item['slug'] === 'extra-virgin-olive-oil') {
                            $quantity = rand(5, 15);
                        } elseif ($planData['goal'] === 'lose') {
                            if ($quantity > 150) {
                                $quantity = rand(80, 150);
                            }
                        } elseif ($planData['goal'] === 'gain') {
                            if ($quantity < 150) {
                                $quantity = rand(150, 250);
                            }
                        }

                        MealEntry::create([
                            'meal_plan_id' => $plan->id,
                            'food_id' => $food->id,
                            'meal_type' => $mealType,
                            'quantity_grams' => $quantity,
                            'notes' => "Part of {$mealType}",
                        ]);
                        $createdEntries++;
                    }
                }
            }
        }
    }
}

echo "✅ Created " . count($createdUsers) . " clients\n";
echo "✅ Created {$createdPlans} meal plans\n";
echo "✅ Created {$createdEntries} meal entries\n\n";

echo "=== Client Summary ===\n";
foreach ($createdUsers as $item) {
    $user = $item['user'];
    echo "\n{$user->name} ({$user->gender}, {$user->age} years)\n";
    echo "  Weight: {$user->weight_kg} kg | Height: {$user->height_cm} cm\n";
    echo "  Activity: {$user->activity_level} | Primary Goal: {$user->weight_goal} weight\n";
    echo "  TDEE: {$item['tdee']} cal\n";
    echo "  Weight Loss Plan: {$item['lose_calories']} cal/day\n";
    echo "  Weight Gain Plan: {$item['gain_calories']} cal/day\n";
}

echo "\n=== Done! ===\n";
echo "You can now test calorie calculations:\n";
echo "  \$user = User::where('email', 'sarah.johnson@nutritrack.test')->first();\n";
echo "  \$user->calculateDailyCalories();\n";

