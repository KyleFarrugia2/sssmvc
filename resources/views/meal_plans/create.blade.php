@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="glass-card p-4 mb-3">
                <h1 class="h4 mb-2"><i class="bi bi-journal-plus"></i> New Meal Plan</h1>
                <p class="text-secondary mb-3">Set the day, target calories and client info.</p>
                <div class="alert alert-info mb-3 border border-info">
                    <i class="bi bi-info-circle"></i> Fields marked with <span class="text-danger">*</span> are required.
                </div>
                <form method="POST" action="{{ route('meal-plans.store') }}">
                    @csrf
                    @include('meal_plans._form')
                </form>
            </div>
        </div>
    </div>
@endsection


