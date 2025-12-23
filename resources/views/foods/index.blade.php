@extends('layouts.app')

@section('content')
    <div class="row g-3 align-items-center mb-4">
        <div class="col-md-8">
            <div class="glass-card p-4 h-100">
                <h1 class="h3 mb-2">Food Library</h1>
                <p class="mb-0 text-secondary">Curate trusted foods, validated with OpenFoodFacts.</p>
            </div>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('foods.create') }}" class="btn btn-primary btn-lg w-100 glass-card">
                <i class="bi bi-plus-circle"></i> Add Food
            </a>
        </div>
    </div>

    <div class="glass-card p-3 mb-3">
        <form class="row g-2 align-items-end" method="GET">
            <div class="col-md-4">
                <label class="form-label text-secondary">Search</label>
                <input type="text" name="search" class="form-control" value="{{ $filters['search'] ?? '' }}" placeholder="Chicken, rice...">
            </div>
            <div class="col-md-3">
                <label class="form-label text-secondary">Sort by</label>
                <select name="sort" class="form-select">
                    <option value="name" @selected(($filters['sort'] ?? '') === 'name')>Name</option>
                    <option value="calories_per_100g" @selected(($filters['sort'] ?? '') === 'calories_per_100g')>Calories</option>
                    <option value="protein" @selected(($filters['sort'] ?? '') === 'protein')>Protein</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label text-secondary">Direction</label>
                <select name="direction" class="form-select">
                    <option value="asc" @selected(($filters['direction'] ?? '') !== 'desc')>Ascending</option>
                    <option value="desc" @selected(($filters['direction'] ?? '') === 'desc')>Descending</option>
                </select>
            </div>
            <div class="col-md-3">
                <button class="btn btn-outline-light w-100 glass-card"><i class="bi bi-funnel"></i> Apply</button>
            </div>
        </form>
    </div>

    <div class="alert alert-info mb-3 glass-card border border-info">
        <i class="bi bi-info-circle"></i> <strong>Note:</strong> All nutritional values (calories, protein, carbs, fat) are displayed per 100g.
    </div>

    <div class="glass-card">
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Calories</th>
                    <th>Protein</th>
                    <th>Carbs</th>
                    <th>Fat</th>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($foods as $food)
                    <tr>
                        <td>{{ $food->name }}</td>
                        <td>{{ $food->calories_per_100g }} kcal</td>
                        <td>{{ $food->protein }} g</td>
                        <td>{{ $food->carbs }} g</td>
                        <td>{{ $food->fat }} g</td>
                        <td class="text-end">
                            <a href="{{ route('foods.edit', $food) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('foods.destroy', $food) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Remove {{ $food->name }}?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-secondary py-4">No foods yet.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">
            {{ $foods->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection

