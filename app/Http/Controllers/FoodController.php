<?php

namespace App\Http\Controllers;

use App\Http\Requests\FoodRequest;
use App\Models\Food;
use App\Services\OpenFoodFactsClient;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FoodController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'sort', 'direction']);
        $sort = in_array($filters['sort'] ?? null, ['name', 'calories_per_100g', 'protein'], true)
            ? $filters['sort']
            : 'name';
        $direction = ($filters['direction'] ?? 'asc') === 'desc' ? 'desc' : 'asc';

        $foods = Food::query()
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy($sort, $direction)
            ->paginate(8)
            ->withQueryString();

        return view('foods.index', compact('foods', 'filters'));
    }

    public function create()
    {
        return view('foods.create');
    }

    public function store(FoodRequest $request, OpenFoodFactsClient $client)
    {
        $data = $request->validated();
        $data['slug'] = $this->makeSlug($data['name']);

        $fromApi = $client->firstNutrients($data['name']);
        $payload = array_merge($data, array_filter($fromApi, fn ($value) => $value !== null && $value !== ''));

        Food::create($payload + ['source' => $fromApi['source'] ?? 'Manual entry']);

        return redirect()->route('foods.index')->with('success', 'Food item added.');
    }

    public function edit(Food $food)
    {
        return view('foods.edit', compact('food'));
    }

    public function update(FoodRequest $request, Food $food, OpenFoodFactsClient $client)
    {
        $data = $request->validated();

        if ($food->name !== $data['name']) {
            $data['slug'] = $this->makeSlug($data['name']);
        }

        $fromApi = $client->firstNutrients($data['name']);
        $payload = array_merge($data, array_filter($fromApi, fn ($value) => $value !== null && $value !== ''));

        $food->update($payload + ['source' => $fromApi['source'] ?? $food->source]);

        return redirect()->route('foods.index')->with('success', 'Food updated.');
    }

    public function destroy(Food $food)
    {
        $food->delete();

        return redirect()->route('foods.index')->with('success', 'Food removed.');
    }

    private function makeSlug(string $name): string
    {
        $base = Str::slug($name);
        $count = Food::where('slug', 'like', "{$base}%")->count();

        return $count ? "{$base}-" . ($count + 1) : $base;
    }
}
