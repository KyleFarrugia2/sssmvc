@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="glass-card p-4 mb-3">
                <h1 class="h4 mb-2"><i class="bi bi-plus-circle"></i> Add Food</h1>
                <p class="text-secondary mb-3">Macro data is validated against OpenFoodFacts before saving.</p>
                <form method="POST" action="{{ route('foods.store') }}">
                    @csrf
                    @include('foods._form')
                </form>
            </div>
        </div>
    </div>
@endsection


