<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CalorieCalculatorController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\MealEntryController;
use App\Http\Controllers\MealPlanController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
});

Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/', [MealPlanController::class, 'index'])->name('home');
    
    Route::get('calorie-calculator', [CalorieCalculatorController::class, 'index'])->name('calorie-calculator.index');
    Route::post('calorie-calculator', [CalorieCalculatorController::class, 'calculate'])->name('calorie-calculator.calculate');
    
    Route::resource('foods', FoodController::class)->except('show');
    Route::resource('meal-plans', MealPlanController::class);
    
    Route::post('meal-plans/{meal_plan:slug}/entries', [MealEntryController::class, 'store'])->name('meal-entries.store');
    Route::delete('meal-plans/{meal_plan:slug}/entries/{meal_entry}', [MealEntryController::class, 'destroy'])->name('meal-entries.destroy');
});
