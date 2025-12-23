@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="glass-card p-4 mb-3">
                <h1 class="h4 mb-2"><i class="bi bi-calculator"></i> Maintenance Calorie Calculator</h1>
                <p class="text-secondary mb-4">Calculate your daily maintenance calories based on your body metrics and activity level.</p>
                
                <form method="POST" action="{{ route('calorie-calculator.calculate') }}">
                    @csrf
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" 
                                       class="form-control @error('weight_kg') is-invalid @enderror" 
                                       id="weight_kg" 
                                       name="weight_kg" 
                                       placeholder="Weight (kg)" 
                                       value="{{ old('weight_kg') }}" 
                                       step="0.1" 
                                       min="30" 
                                       max="300" 
                                       required>
                                <label for="weight_kg">Weight (kg)</label>
                                @error('weight_kg')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" 
                                       class="form-control  @error('height_cm') is-invalid @enderror" 
                                       id="height_cm" 
                                       name="height_cm" 
                                       placeholder="Height (cm)" 
                                       value="{{ old('height_cm') }}" 
                                       step="0.1" 
                                       min="100" 
                                       max="250" 
                                       required>
                                <label for="height_cm">Height (cm)</label>
                                @error('height_cm')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" 
                                       class="form-control  @error('age') is-invalid @enderror" 
                                       id="age" 
                                       name="age" 
                                       placeholder="Age" 
                                       value="{{ old('age') }}" 
                                       min="10" 
                                       max="120" 
                                       required>
                                <label for="age">Age (years)</label>
                                @error('age')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select  @error('gender') is-invalid @enderror" 
                                        id="gender" 
                                        name="gender" 
                                        required>
                                    <option value="">Select gender</option>
                                    <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                                <label for="gender">Gender</label>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-floating">
                                <select class="form-select  @error('activity_level') is-invalid @enderror" 
                                        id="activity_level" 
                                        name="activity_level" 
                                        required>
                                    <option value="">Select activity level</option>
                                    <option value="sedentary" {{ old('activity_level') === 'sedentary' ? 'selected' : '' }}>Sedentary - Little or no exercise</option>
                                    <option value="light" {{ old('activity_level') === 'light' ? 'selected' : '' }}>Light - Exercise 1-3 days/week</option>
                                    <option value="moderate" {{ old('activity_level') === 'moderate' ? 'selected' : '' }}>Moderate - Exercise 3-5 days/week</option>
                                    <option value="active" {{ old('activity_level') === 'active' ? 'selected' : '' }}>Active - Hard exercise 6-7 days/week</option>
                                    <option value="very_active" {{ old('activity_level') === 'very_active' ? 'selected' : '' }}>Very Active - Very hard exercise, physical job</option>
                                </select>
                                <label for="activity_level">Activity Level</label>
                                @error('activity_level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100 py-2">
                                <i class="bi bi-calculator"></i> Calculate Maintenance Calories
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="glass-card p-4">
                <h2 class="h6 mb-3"><i class="bi bi-info-circle"></i> How It Works</h2>
                <p class="small text-secondary mb-2">
                    This calculator uses the <strong>Mifflin-St Jeor Equation</strong> to calculate your Basal Metabolic Rate (BMR) - 
                    the number of calories your body needs at rest. Then it multiplies by your activity level to get your 
                    Total Daily Energy Expenditure (TDEE) - your maintenance calories.
                </p>
                <ul class="small text-secondary mb-0">
                    <li><strong>BMR:</strong> Calories needed at complete rest</li>
                    <li><strong>TDEE:</strong> Calories needed with your activity level (maintenance calories)</li>
                </ul>
            </div>
        </div>
    </div>
@endsection

