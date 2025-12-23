<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalorieCalculatorController extends Controller
{
    /**
     * Show the calorie calculator form
     */
    public function index()
    {
        return view('calorie-calculator.index');
    }

    /**
     * Calculate maintenance calories
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

        if ($gender === 'male') {
            $bmr = (10 * $weight) + (6.25 * $height) - (5 * $age) + 5;
        } else {
            $bmr = (10 * $weight) + (6.25 * $height) - (5 * $age) - 161;
        }
        $activityMultipliers = [
            'sedentary' => 1.2,
            'light' => 1.375,
            'moderate' => 1.55,
            'active' => 1.725,
            'very_active' => 1.9,
        ];

        $multiplier = $activityMultipliers[$activityLevel];
        $tdee = round($bmr * $multiplier, 2);
        $bmr = round($bmr, 2);

        $activityLabels = [
            'sedentary' => 'Little or no exercise',
            'light' => 'Light exercise 1-3 days/week',
            'moderate' => 'Moderate exercise 3-5 days/week',
            'active' => 'Hard exercise 6-7 days/week',
            'very_active' => 'Very hard exercise, physical job',
        ];

        return view('calorie-calculator.result', [
            'bmr' => $bmr,
            'tdee' => $tdee,
            'weight' => $weight,
            'height' => $height,
            'age' => $age,
            'gender' => $gender,
            'activity_level' => $activityLevel,
            'activity_label' => $activityLabels[$activityLevel],
        ]);
    }
}
