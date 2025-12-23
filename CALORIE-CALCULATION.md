# Calorie Calculation Feature

## Overview
The NutriTrack app now includes automatic calorie calculation based on client profiles. The system uses the Mifflin-St Jeor equation to calculate BMR (Basal Metabolic Rate) and TDEE (Total Daily Energy Expenditure), then adjusts calories based on weight goals.

## Client Profile Fields

Each user/client can have:
- **weight_kg**: Current weight in kilograms
- **height_cm**: Height in centimeters
- **age**: Age in years
- **gender**: 'male', 'female', or 'other'
- **activity_level**: 
  - 'sedentary' (1.2x) - Little or no exercise
  - 'light' (1.375x) - Light exercise 1-3 days/week
  - 'moderate' (1.55x) - Moderate exercise 3-5 days/week
  - 'active' (1.725x) - Hard exercise 6-7 days/week
  - 'very_active' (1.9x) - Very hard exercise, physical job
- **weight_goal**: 'lose', 'maintain', or 'gain'
- **target_weight_kg**: Target weight in kilograms

## Available Methods

### 1. Calculate BMR (Basal Metabolic Rate)
```php
$user = User::find(1);
$bmr = $user->calculateBMR();
// Returns: 1430.25 (calories needed at rest)
```

### 2. Calculate TDEE (Total Daily Energy Expenditure)
```php
$tdee = $user->calculateTDEE();
// Returns: 2216.89 (calories needed with activity)
```

### 3. Calculate Daily Calories for Weight Goal
```php
$calorieInfo = $user->calculateDailyCalories();
/*
Returns:
[
    'calories' => 1774,           // Recommended daily calories
    'deficit_surplus' => -500,    // Calorie deficit/surplus
    'tdee' => 2216.89,           // Total Daily Energy Expenditure
    'bmr' => 1430.25,             // Basal Metabolic Rate
    'message' => 'Weight loss plan: 1773.512 calories/day (500 cal deficit from TDEE of 2216.89)'
]
*/
```

## Weight Goal Calculations

### Weight Loss
- **Deficit**: 500 calories per day
- **Result**: ~0.5 kg (1 lb) weight loss per week
- **Minimum**: Never goes below 80% of TDEE for safety

### Weight Maintenance
- **Deficit/Surplus**: 0 calories
- **Result**: Maintains current weight
- **Calories**: Equal to TDEE

### Weight Gain
- **Surplus**: 500 calories per day
- **Result**: ~0.5 kg (1 lb) weight gain per week
- **Calories**: TDEE + 500

## Example Usage in Tinker

```php
// Get a client
$user = User::where('email', 'sarah.johnson@nutritrack.test')->first();

// View their profile
echo "Name: {$user->name}\n";
echo "Weight: {$user->weight_kg} kg\n";
echo "Height: {$user->height_cm} cm\n";
echo "Age: {$user->age} years\n";
echo "Gender: {$user->gender}\n";
echo "Activity: {$user->activity_level}\n";
echo "Goal: {$user->weight_goal}\n";

// Calculate calories
$info = $user->calculateDailyCalories();
echo "\n{$info['message']}\n";
echo "BMR: {$info['bmr']} cal\n";
echo "TDEE: {$info['tdee']} cal\n";
echo "Daily Target: {$info['calories']} cal\n";
```

## Created Clients

The `tinker-clients.php` script created 10 clients with complete profiles:

1. **Sarah Johnson** - Female, 28, Weight Loss (70kg → 65kg)
2. **Michael Chen** - Male, 35, Maintenance (85kg)
3. **Emma Williams** - Female, 24, Weight Gain (58kg → 62kg)
4. **David Rodriguez** - Male, 42, Weight Loss (95kg → 85kg)
5. **Olivia Brown** - Female, 31, Maintenance (68kg)
6. **James Wilson** - Male, 29, Weight Gain (78kg → 82kg)
7. **Sophia Martinez** - Female, 26, Weight Loss (62kg → 58kg)
8. **Daniel Taylor** - Male, 38, Weight Loss (88kg → 80kg)
9. **Isabella Anderson** - Female, 22, Weight Gain (55kg → 58kg)
10. **Christopher Lee** - Male, 33, Maintenance (82kg)

Each client has 7 meal plans (one for each day of the week) with meal entries.

## Running the Script Again

The script uses `updateOrCreate` and `firstOrCreate`, so running it multiple times won't create duplicates. To add more clients, edit the `$clients` array in `tinker-clients.php`.

