<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'weight_kg',
        'height_cm',
        'age',
        'gender',
        'activity_level',
        'weight_goal',
        'target_weight_kg',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // User has many meal plans (used in filtering by client name)
    public function mealPlans()
    {
        return $this->hasMany(MealPlan::class);
    }

    /**
     * Calculate Basal Metabolic Rate (BMR) using Mifflin-St Jeor Equation
     */
    public function calculateBMR(): ?float
    {
        if (!$this->weight_kg || !$this->height_cm || !$this->age || !$this->gender) {
            return null;
        }

        if ($this->gender === 'male') {
            $bmr = (10 * $this->weight_kg) + (6.25 * $this->height_cm) - (5 * $this->age) + 5;
        } else {
            $bmr = (10 * $this->weight_kg) + (6.25 * $this->height_cm) - (5 * $this->age) - 161;
        }

        return round($bmr, 2);
    }

    /**
     * Calculate Total Daily Energy Expenditure (TDEE)
     */
    public function calculateTDEE(): ?float
    {
        $bmr = $this->calculateBMR();
        if ($bmr === null) {
            return null;
        }

        $activityMultipliers = [
            'sedentary' => 1.2,
            'light' => 1.375,
            'moderate' => 1.55,
            'active' => 1.725,
            'very_active' => 1.9,
        ];

        $multiplier = $activityMultipliers[$this->activity_level] ?? 1.55;
        $tdee = $bmr * $multiplier;

        return round($tdee, 2);
    }

    /**
     * Calculate daily calories needed based on weight goal
     * 
     * @return array{calories: float, deficit_surplus: float, message: string}
     */
    public function calculateDailyCalories(): array
    {
        $tdee = $this->calculateTDEE();
        
        if ($tdee === null) {
            return [
                'calories' => 0,
                'deficit_surplus' => 0,
                'message' => 'Please complete your profile (weight, height, age, gender) to calculate calories.',
            ];
        }

        $deficitSurplus = 0;
        $message = '';

        switch ($this->weight_goal) {
            case 'lose':
                $deficitSurplus = -500;
                $calories = max($tdee + $deficitSurplus, $tdee * 0.8);
                $message = "Weight loss plan: {$calories} calories/day (500 cal deficit from TDEE of {$tdee})";
                break;

            case 'gain':
                $deficitSurplus = 500;
                $calories = $tdee + $deficitSurplus;
                $message = "Weight gain plan: {$calories} calories/day (500 cal surplus from TDEE of {$tdee})";
                break;

            case 'maintain':
            default:
                $deficitSurplus = 0;
                $calories = $tdee;
                $message = "Maintenance plan: {$calories} calories/day (TDEE)";
                break;
        }

        return [
            'calories' => round($calories, 0),
            'deficit_surplus' => $deficitSurplus,
            'tdee' => $tdee,
            'bmr' => $this->calculateBMR(),
            'message' => $message,
        ];
    }
}
