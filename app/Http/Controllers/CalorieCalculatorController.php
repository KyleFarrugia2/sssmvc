<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * CalorieCalculatorController
 * 
 * Calculates maintenance calories using Mifflin-St Jeor equation.
 * Provides BMR (Basal Metabolic Rate) and TDEE (Total Daily Energy Expenditure).
 * 
 * CALCULATION FLOW:
 * 1. User submits body metrics (weight, height, age, gender, activity level)
 * 2. Calculate BMR using Mifflin-St Jeor equation (different formula for male/female)
 * 3. Multiply BMR by activity multiplier to get TDEE (maintenance calories)
 * 4. Pass TDEE to view where recommendations are calculated
 * 
 * RECOMMENDATIONS:
 * - Weight Loss: TDEE - 500 calories (calculated in result view)
 * - Maintenance: TDEE (already calculated)
 * - Weight Gain: TDEE + 500 calories (calculated in result view)
 */
class CalorieCalculatorController extends Controller
{
    public function index()
    {
        return view('calorie-calculator.index');
    }

    /**
     * Calculate maintenance calories
     * 
     * CALCULATION PROCESS:
     * 1. Validate user input (weight, height, age, gender, activity level)
     * 2. Calculate BMR using Mifflin-St Jeor equation:
     *    - Male: (10 × weight) + (6.25 × height) - (5 × age) + 5
     *    - Female: (10 × weight) + (6.25 × height) - (5 × age) - 161
     * 3. Calculate TDEE by multiplying BMR by activity multiplier:
     *    - Sedentary: 1.2
     *    - Light: 1.375
     *    - Moderate: 1.55
     *    - Active: 1.725
     *    - Very Active: 1.9
     * 4. Pass TDEE to result view where recommendations are displayed
     */
    public function calculate(Request $request)
    {
        $request->validate([
            'weight_kg' => 'required|numeric|min:30|max:300',
            'height_cm' => 'required|numeric|min:100|max:250',
            'age' => 'required|integer|min:10|max:120',
            'gender' => 'required|in:male,female',
            'activity_level' => 'required|in:sedentary,light,moderate,active,very_active',
        ]);

        $weight = $request->input('weight_kg');
        $height = $request->input('height_cm');
        $age = $request->input('age');
        $gender = $request->input('gender');
        $activityLevel = $request->input('activity_level');

        // Calculate BMR using Mifflin-St Jeor equation
        // Different formula for male vs female
        if ($gender === 'male') {
            $bmr = (10 * $weight) + (6.25 * $height) - (5 * $age) + 5;
        } else {
            $bmr = (10 * $weight) + (6.25 * $height) - (5 * $age) - 161;
        }
        
        // Activity multipliers to convert BMR to TDEE
        $activityMultipliers = [
            'sedentary' => 1.2,      // Little or no exercise
            'light' => 1.375,        // Light exercise 1-3 days/week
            'moderate' => 1.55,      // Moderate exercise 3-5 days/week
            'active' => 1.725,       // Hard exercise 6-7 days/week
            'very_active' => 1.9,    // Very hard exercise, physical job
        ];

        // Calculate TDEE: BMR × activity multiplier
        $multiplier = $activityMultipliers[$activityLevel];
        $tdee = round($bmr * $multiplier, 2);
        $bmr = round($bmr, 2);

        // Activity level labels for display
        $activityLabels = [
            'sedentary' => 'Little or no exercise',
            'light' => 'Light exercise 1-3 days/week',
            'moderate' => 'Moderate exercise 3-5 days/week',
            'active' => 'Hard exercise 6-7 days/week',
            'very_active' => 'Very hard exercise, physical job',
        ];

        // Pass TDEE to view - recommendations are calculated in the view
        return view('calorie-calculator.result', [
            'bmr' => $bmr,
            'tdee' => $tdee,  // This is used to calculate recommendations in the view
            'weight' => $weight,
            'height' => $height,
            'age' => $age,
            'gender' => $gender,
            'activity_level' => $activityLevel,
            'activity_label' => $activityLabels[$activityLevel],
        ]);
    }
}
