<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * MealPlan Model
 * - Belongs to a user (client)
 * - Has many meal entries
 * - Can be sorted by date or title
 * - Uses slug for route binding
 */
class MealPlan extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'user_id',        // Foreign key to users table (client)
        'title',          // Plan title (e.g., "Sarah's Weight Loss Meal Plan")
        'slug',           // URL-friendly version
        'planned_on',     // Date the meal plan is for
        'goal_calories',  // Target calories for the day
        'notes',          // Optional notes about the plan
    ];

    protected $casts = [
        'planned_on' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function entries()
    {
        return $this->hasMany(MealEntry::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // Calculate total calories from all meal entries
    public function totalCalories(): int
    {
        return (int) $this->entries->sum(fn ($entry) => $entry->calories);
    }

    public function ensureSlug(): void
    {
        if (! $this->slug) {
            $baseSlug = Str::slug($this->title);
            $this->slug = $baseSlug ?: Str::random(8);
        }
    }
}



