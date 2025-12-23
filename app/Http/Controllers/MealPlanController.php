<?php

namespace App\Http\Controllers;

use App\Http\Requests\MealPlanRequest;
use App\Models\Food;
use App\Models\MealPlan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MealPlanController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'client', 'from', 'to', 'sort', 'direction']);
        $sort = in_array($filters['sort'] ?? null, ['planned_on', 'title'], true) ? $filters['sort'] : 'planned_on';
        $direction = ($filters['direction'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

        $plans = MealPlan::with(['user', 'entries.food'])
            ->when($filters['search'] ?? null, fn ($query, $term) => $query->where('title', 'like', "%{$term}%"))
            ->when($filters['client'] ?? null, fn ($query, $client) => $query->whereHas('user', function ($q) use ($client) {
                $q->where('name', 'like', "%{$client}%");
            }))
            ->when($filters['from'] ?? null, fn ($query, $from) => $query->whereDate('planned_on', '>=', $from))
            ->when($filters['to'] ?? null, fn ($query, $to) => $query->whereDate('planned_on', '<=', $to))
            ->orderBy($sort, $direction)
            ->paginate(6)
            ->withQueryString();

        $clients = \App\Models\User::whereHas('mealPlans')->orderBy('name')->pluck('name', 'id');

        return view('meal_plans.index', [
            'plans' => $plans,
            'filters' => $filters,
            'clients' => $clients,
        ]);
    }

    public function create()
    {
        return view('meal_plans.create');
    }

    public function store(MealPlanRequest $request)
    {
        $user = $this->resolveUser($request);
        $data = $request->validated();

        $plan = MealPlan::create([
            'user_id' => $user->id,
            'title' => $data['title'],
            'slug' => $this->makeSlug($data['title']),
            'planned_on' => $data['planned_on'],
            'goal_calories' => $data['goal_calories'],
            'notes' => $data['notes'] ?? null,
        ]);

        return redirect()->route('meal-plans.show', $plan)->with('success', 'Meal plan created. Add your meals below.');
    }

    public function show(MealPlan $meal_plan)
    {
        $meal_plan->load(['entries.food', 'user']);
        $foods = Food::orderBy('name')->get();

        return view('meal_plans.show', compact('meal_plan', 'foods'));
    }

    public function edit(MealPlan $meal_plan)
    {
        $meal_plan->load('user');
        return view('meal_plans.edit', compact('meal_plan'));
    }

    public function update(MealPlanRequest $request, MealPlan $meal_plan)
    {
        $user = $this->resolveUser($request);
        $data = $request->validated();

        $meal_plan->update([
            'user_id' => $user->id,
            'title' => $data['title'],
            'slug' => $meal_plan->title === $data['title'] ? $meal_plan->slug : $this->makeSlug($data['title']),
            'planned_on' => $data['planned_on'],
            'goal_calories' => $data['goal_calories'],
            'notes' => $data['notes'] ?? null,
        ]);

        return redirect()->route('meal-plans.show', $meal_plan)->with('success', 'Meal plan updated.');
    }

    public function destroy(MealPlan $meal_plan)
    {
        $meal_plan->delete();

        return redirect()->route('meal-plans.index')->with('success', 'Meal plan removed.');
    }

    private function resolveUser(MealPlanRequest $request): User
    {
        return User::updateOrCreate(
            ['email' => $request->validated('owner_email')],
            [
                'name' => $request->validated('owner_name'),
                'password' => Hash::make('password'),
            ]
        );
    }

    private function makeSlug(string $title): string
    {
        $base = Str::slug($title);
        $count = MealPlan::where('slug', 'like', "{$base}%")->count();

        return $count ? "{$base}-" . ($count + 1) : $base;
    }
}

