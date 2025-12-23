<?php

namespace App\Http\Controllers;

use App\Http\Requests\MealEntryRequest;
use App\Models\MealEntry;
use App\Models\MealPlan;

class MealEntryController extends Controller
{
    public function store(MealEntryRequest $request, MealPlan $meal_plan)
    {
        $meal_plan->entries()->create($request->validated());

        return redirect()->route('meal-plans.show', $meal_plan)->with('success', 'Meal added to plan.');
    }

    public function destroy(MealPlan $meal_plan, MealEntry $meal_entry)
    {
        if ($meal_entry->meal_plan_id !== $meal_plan->id) {
            abort(404);
        }

        $meal_entry->delete();

        return redirect()->route('meal-plans.show', $meal_plan)->with('success', 'Meal entry removed.');
    }
}


