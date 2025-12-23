<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MealPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'planned_on',
        'goal_calories',
        'notes',
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


