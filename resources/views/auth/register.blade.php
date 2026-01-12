@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="glass-card p-4">
                <h1 class="h4 mb-3 text-center"><i class="bi bi-person-plus"></i> Register</h1>
                <p class="text-secondary small text-center mb-4">Create a new account to get started</p>
                
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    
                    <div class="form-floating mb-3">
                        <input type="text" 
                               name="name" 
                               id="name" 
                               class="form-control @error('name') is-invalid @enderror" 
                               placeholder="Name" 
                               value="{{ old('name') }}" 
                               required 
                               autofocus
                               pattern="[a-zA-Z\s\-\'\.]+"
                               onkeypress="return /[a-zA-Z\s\-\'\.]/.test(event.key)"
                               title="Only letters, spaces, hyphens, apostrophes, and periods are allowed">
                        <label for="name">Name</label>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-floating mb-3">
                        <input type="email" 
                               name="email" 
                               id="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               placeholder="Email" 
                               value="{{ old('email') }}" 
                               required>
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
                    
                    <div class="form-floating mb-3">
                        <input type="password" 
                               name="password_confirmation" 
                               id="password_confirmation" 
                               class="form-control" 
                               placeholder="Confirm Password" 
                               required>
                        <label for="password_confirmation">Confirm Password</label>
                    </div>
                    
                    <button type="submit" class="btn btn-success w-100 mb-3">
                        <i class="bi bi-person-plus"></i> Register
                    </button>
                    
                    <div class="text-center">
                        <p class="mb-0 text-secondary">Already have an account? 
                            <a href="{{ route('login') }}">Sign in here</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


