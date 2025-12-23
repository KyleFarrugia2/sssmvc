@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="glass-card p-4 mb-3">
                <h1 class="h4 mb-2"><i class="bi bi-pencil-square"></i> Edit {{ $food->name }}</h1>
                <p class="text-secondary mb-3">Update macros. Validation still checks OpenFoodFacts for the name.</p>
                <form method="POST" action="{{ route('foods.update', $food) }}">
                    @csrf
                    @method('PUT')
                    @include('foods._form')
                </form>
            </div>
        </div>
    </div>
@endsection


