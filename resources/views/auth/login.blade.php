@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="text-center mb-4">
                <h1 class="h2 mb-2 fw-bold"><i class="bi bi-clipboard2-pulse"></i> WELCOME TO NUTRITRACK!</h1>
                <p class="text-secondary mb-0">Your personalized nutrition tracking and meal planning platform. Sign in to manage your meal plans, track calories, and achieve your health goals.</p>
            </div>
            
            <div class="glass-card p-4">
                <h2 class="h5 mb-3 text-center"><i class="bi bi-box-arrow-in-right"></i> Sign In</h2>
                
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="form-floating mb-3">
                        <input type="email" 
                               name="email" 
                               id="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               placeholder="Email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus>
                        <label for="email">Email</label>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-floating mb-3">
                        <input type="password" 
                               name="password" 
                               id="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               placeholder="Password" 
                               required>
                        <label for="password">Password</label>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" name="remember" id="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 mb-3">
                        <i class="bi bi-box-arrow-in-right"></i> Sign In
                    </button>
                    
                    <div class="text-center">
                        <p class="mb-0 text-secondary">Don't have an account? 
                            <a href="{{ route('register') }}">Register here</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

