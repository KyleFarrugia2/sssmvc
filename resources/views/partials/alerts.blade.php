@if(session('success'))
    <div class="alert alert-success glass-card text-dark fw-semibold">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger glass-card text-dark">
        <div class="fw-semibold">Please fix the issues below:</div>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif



