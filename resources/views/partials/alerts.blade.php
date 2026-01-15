@if(session('success'))
    <div class="alert alert-success glass-card fw-semibold" style="color: #065f46; background-color: rgba(16, 185, 129, 0.3); border-color: rgba(16, 185, 129, 0.6);">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger glass-card" style="color: #fee2e2; background-color: rgba(220, 38, 38, 0.2); border-color: rgba(220, 38, 38, 0.5);">
        <div class="fw-semibold" style="color: #fecaca;">Please fix the issues below:</div>
        <ul class="mb-0" style="color: #fee2e2;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif



