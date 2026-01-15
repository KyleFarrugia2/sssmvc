@extends('layouts.app')

@section('content')
    <div class="row g-3 align-items-center mb-4">
        <div class="col-md-8">
            <div class="glass-card p-4 h-100">
                <h1 class="h3 mb-2">Meal Plans</h1>
                <p class="mb-0 text-secondary">Track calories and macros per client day with quick filters.</p>
                @if(request()->hasAny(['search', 'client', 'from', 'to']))
                    <div class="mt-2">
                        <span class="badge bg-info text-dark">
                            <i class="bi bi-funnel-fill"></i> Filters Active
                        </span>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('meal-plans.create') }}" class="btn btn-primary btn-lg w-100 glass-card">
                <i class="bi bi-journal-plus"></i> New Meal Plan
            </a>
        </div>
    </div>

    {{-- 
        SORTING FORM IMPLEMENTATION:
        ============================
        
        HOW SORTING WORKS:
        1. User selects sort column (planned_on/date or title) from dropdown
        2. User selects direction (ascending or descending) from dropdown
        3. User clicks "Apply" button
        4. Form submits as GET: /meal-plans?sort=planned_on&direction=desc
        5. Controller validates and applies sorting to Eloquent query
        6. Results are displayed sorted, and form maintains selected values
        
        SORTING FIELDS:
        - name="sort": Column to sort by (must match controller whitelist)
        - name="direction": Sort direction (asc or desc)
        
        Sorting is applied AFTER all filters, so it sorts the filtered results
    --}}
    <div class="glass-card p-3 mb-3">
        <form class="row g-2 align-items-end" method="GET">
            <div class="col-md-3">
                <label class="form-label text-secondary">Search title</label>
                <input type="text" name="search" class="form-control" value="{{ $filters['search'] ?? '' }}" placeholder="Search meal plan title...">
            </div>
            <div class="col-md-3">
                <label class="form-label text-secondary">Search client</label>
                <input type="text" name="client" class="form-control" value="{{ $filters['client'] ?? '' }}" placeholder="Search by client name...">
            </div>
            <div class="col-md-2">
                <label class="form-label text-secondary">From</label>
                <input type="date" name="from" class="form-control" value="{{ $filters['from'] ?? '' }}">
            </div>
            <div class="col-md-2">
                <label class="form-label text-secondary">To</label>
                <input type="date" name="to" class="form-control" value="{{ $filters['to'] ?? '' }}">
            </div>
            {{-- SORT COLUMN: User selects which column to sort by --}}
            <div class="col-md-2">
                <label class="form-label text-secondary">Sort</label>
                <select name="sort" class="form-select">
                    {{-- Values must match whitelist in MealPlanController --}}
                    <option value="planned_on" @selected(($filters['sort'] ?? '') === 'planned_on')>Date</option>
                    <option value="title" @selected(($filters['sort'] ?? '') === 'title')>Title</option>
                </select>
            </div>
            {{-- SORT DIRECTION: User selects ascending or descending --}}
            <div class="col-md-1">
                <label class="form-label text-secondary">Dir</label>
                <select name="direction" class="form-select">
                    {{-- Default to 'desc' (newest first for dates) --}}
                    <option value="desc" @selected(($filters['direction'] ?? '') !== 'asc')>Desc</option>
                    <option value="asc" @selected(($filters['direction'] ?? '') === 'asc')>Asc</option>
                </select>
            </div>
            <div class="col-md-12 col-lg-2 d-grid gap-2">
                {{-- Submit sends all form values (including sort/direction) as GET parameters --}}
                <button class="btn btn-outline-light mt-3 mt-lg-0 glass-card"><i class="bi bi-funnel"></i> Apply</button>
                @if(request()->hasAny(['search', 'client', 'from', 'to']))
                    <a href="{{ route('meal-plans.index') }}" class="btn btn-outline-secondary mt-lg-0 glass-card"><i class="bi bi-x-circle"></i> Clear</a>
                @endif
            </div>
        </form>
    </div>

    <div class="row g-3">
        @forelse($plans as $plan)
            @php
                $calories = $plan->entries->sum->calories;
                $protein = $plan->entries->sum->protein;
                $carbs = $plan->entries->sum->carbs;
                $fat = $plan->entries->sum->fat;
            @endphp
            <div class="col-md-6">
                <div class="glass-card p-3 h-100">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h2 class="h5 mb-1">{{ $plan->title }}</h2>
                            <span class="badge bg-info text-dark">Client: {{ $plan->user->name }}</span>
                        </div>
                        <span class="text-secondary">{{ $plan->planned_on->format('M d, Y') }}</span>
                    </div>
                    <p class="text-secondary small">{{ \Illuminate\Support\Str::limit($plan->notes, 120) }}</p>
                    <div class="row text-center mb-3">
                        <div class="col">
                            <div class="fw-bold">{{ $calories }} kcal</div>
                            <small class="text-secondary">Total</small>
                        </div>
                        <div class="col">
                            <div class="fw-bold">{{ $protein }} g</div>
                            <small class="text-secondary">Protein</small>
                        </div>
                        <div class="col">
                            <div class="fw-bold">{{ $carbs }} g</div>
                            <small class="text-secondary">Carbs</small>
                        </div>
                        <div class="col">
                            <div class="fw-bold">{{ $fat }} g</div>
                            <small class="text-secondary">Fat</small>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('meal-plans.show', $plan) }}" class="btn btn-outline-light">Open</a>
                        <div>
                            <a href="{{ route('meal-plans.edit', $plan) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('meal-plans.destroy', $plan) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this plan?')"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="glass-card p-4 text-center text-secondary">No meal plans yet.</div>
            </div>
        @endforelse
    </div>
    <div class="mt-3">
        {{ $plans->links('pagination::bootstrap-5') }}
    </div>
@endsection

