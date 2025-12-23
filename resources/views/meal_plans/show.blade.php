@extends('layouts.app')

@section('content')
    <div class="row g-3 mb-3">
        <div class="col-md-8">
            <div class="glass-card p-4 h-100">
                <div class="d-flex justify-content-between">
                    <div>
                        <h1 class="h4 mb-1">{{ $meal_plan->title }}</h1>
                        <p class="mb-0 text-secondary">For {{ $meal_plan->user->name }} | {{ $meal_plan->planned_on->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <a href="{{ route('meal-plans.edit', $meal_plan) }}" class="btn btn-outline-info btn-sm"><i class="bi bi-pencil"></i></a>
                        <a href="{{ route('meal-plans.index') }}" class="btn btn-outline-light btn-sm">Back</a>
                    </div>
                </div>
                @php
                    $totalCalories = $meal_plan->entries->sum->calories;
                    $totalProtein = $meal_plan->entries->sum->protein;
                    $totalCarbs = $meal_plan->entries->sum->carbs;
                    $totalFat = $meal_plan->entries->sum->fat;
                @endphp
                <div class="row text-center mt-3">
                    <div class="col">
                        <div class="fw-bold h5">{{ $totalCalories }} kcal</div>
                        <small class="text-secondary">Total</small>
                    </div>
                    <div class="col">
                        <div class="fw-bold h5">{{ $totalProtein }} g</div>
                        <small class="text-secondary">Protein</small>
                    </div>
                    <div class="col">
                        <div class="fw-bold h5">{{ $totalCarbs }} g</div>
                        <small class="text-secondary">Carbs</small>
                    </div>
                    <div class="col">
                        <div class="fw-bold h5">{{ $totalFat }} g</div>
                        <small class="text-secondary">Fat</small>
                    </div>
                </div>
                @if($meal_plan->goal_calories)
                    <div class="mt-2 text-secondary">Goal: {{ $meal_plan->goal_calories }} kcal ({{ $totalCalories - $meal_plan->goal_calories }} kcal delta)</div>
                @endif
                <p class="mt-3 text-secondary">{{ $meal_plan->notes }}</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="glass-card p-4">
                <h2 class="h5 mb-3">Add Meal Entry</h2>
                <form action="{{ route('meal-entries.store', $meal_plan) }}" method="POST">
                    @csrf
                    <div class="form-floating mb-2">
                        <select name="meal_type" id="meal_type" class="form-select ">
                            @foreach(['Breakfast','Lunch','Snack','Dinner'] as $type)
                                <option value="{{ $type }}" @selected(old('meal_type')===$type)>{{ $type }}</option>
                            @endforeach
                        </select>
                        <label for="meal_type">Meal type</label>
                    </div>
                    <div class="form-floating mb-2">
                        <select name="food_id" id="food_id" class="form-select ">
                            @foreach($foods as $food)
                                <option value="{{ $food->id }}" @selected(old('food_id')==$food->id)>{{ $food->name }} ({{ $food->calories_per_100g }} kcal)</option>
                            @endforeach
                        </select>
                        <label for="food_id">Food</label>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="number" name="quantity_grams" id="quantity_grams" class="form-control " placeholder="Quantity" value="{{ old('quantity_grams', 120) }}" required>
                        <label for="quantity_grams">Quantity (g)</label>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea name="notes" id="notes" class="form-control " style="height: 80px" placeholder="Notes">{{ old('notes') }}</textarea>
                        <label for="notes">Notes</label>
                    </div>
                    <button class="btn btn-success w-100"><i class="bi bi-plus-circle"></i> Add</button>
                </form>
            </div>
        </div>
    </div>

    <div class="glass-card">
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0">
                <thead>
                <tr>
                    <th>Meal</th>
                    <th>Food</th>
                    <th>Quantity</th>
                    <th>Calories</th>
                    <th>Protein</th>
                    <th>Carbs</th>
                    <th>Fat</th>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($meal_plan->entries as $entry)
                    <tr>
                        <td>{{ $entry->meal_type }}</td>
                        <td>{{ $entry->food->name }}</td>
                        <td>{{ $entry->quantity_grams }} g</td>
                        <td>{{ $entry->calories }} kcal</td>
                        <td>{{ $entry->protein }} g</td>
                        <td>{{ $entry->carbs }} g</td>
                        <td>{{ $entry->fat }} g</td>
                        <td class="text-end">
                            <form action="{{ route('meal-entries.destroy', [$meal_plan, $entry]) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Remove this entry?')"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-secondary py-4">No meals logged yet.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection


