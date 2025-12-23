@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="glass-card p-4 mb-3">
                <h1 class="h4 mb-4"><i class="bi bi-graph-up"></i> Your Maintenance Calories</h1>
                
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="glass-card p-3 text-center">
                            <div class="text-secondary small mb-1">Basal Metabolic Rate (BMR)</div>
                            <div class="h3 text-primary mb-0">{{ number_format($bmr, 0) }}</div>
                            <div class="text-secondary small">calories/day</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="glass-card p-3 text-center border border-success">
                            <div class="text-secondary small mb-1">Maintenance Calories (TDEE)</div>
                            <div class="h3 text-success mb-0">{{ number_format($tdee, 0) }}</div>
                            <div class="text-secondary small">calories/day</div>
                        </div>
                    </div>
                </div>
                
                <div class="glass-card p-3 mb-3">
                    <h3 class="h6 mb-3">Your Details</h3>
                    <div class="row g-2 small">
                        <div class="col-6"><strong>Weight:</strong> {{ $weight }} kg</div>
                        <div class="col-6"><strong>Height:</strong> {{ $height }} cm</div>
                        <div class="col-6"><strong>Age:</strong> {{ $age }} years</div>
                        <div class="col-6"><strong>Gender:</strong> {{ ucfirst($gender) }}</div>
                        <div class="col-12"><strong>Activity Level:</strong> {{ $activity_label }}</div>
                    </div>
                </div>
                
                <div class="alert alert-info mb-3">
                    <i class="bi bi-info-circle"></i> 
                    <strong>Maintenance Calories:</strong> To maintain your current weight, consume approximately 
                    <strong>{{ number_format($tdee, 0) }} calories</strong> per day based on your activity level.
                </div>
                
                <div class="d-flex gap-2">
                    <a href="{{ route('calorie-calculator.index') }}" class="btn btn-outline-light">
                        <i class="bi bi-arrow-left"></i> Calculate Again
                    </a>
                    <a href="{{ route('meal-plans.index') }}" class="btn btn-primary">
                        <i class="bi bi-clipboard2"></i> View Meal Plans
                    </a>
                </div>
            </div>
            
            <div class="glass-card p-4">
                <h2 class="h6 mb-3"><i class="bi bi-lightbulb"></i> Weight Goal Recommendations</h2>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="p-3 bg-dark rounded">
                            <div class="h6 text-warning mb-2">Weight Loss</div>
                            <div class="small text-secondary">
                                Subtract <strong>500 calories</strong> from your maintenance calories for safe weight loss (~0.5 kg/week).
                            </div>
                            <div class="mt-2">
                                <strong class="text-warning">{{ number_format($tdee - 500, 0) }} cal/day</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-dark rounded border border-success">
                            <div class="h6 text-success mb-2">Maintenance</div>
                            <div class="small text-secondary">
                                Maintain your current weight by eating at your maintenance calories.
                            </div>
                            <div class="mt-2">
                                <strong class="text-success">{{ number_format($tdee, 0) }} cal/day</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-dark rounded">
                            <div class="h6 text-info mb-2">Weight Gain</div>
                            <div class="small text-secondary">
                                Add <strong>500 calories</strong> to your maintenance calories for healthy weight gain (~0.5 kg/week).
                            </div>
                            <div class="mt-2">
                                <strong class="text-info">{{ number_format($tdee + 500, 0) }} cal/day</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

