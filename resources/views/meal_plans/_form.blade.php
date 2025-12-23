<div class="row g-3">
    <div class="col-md-6">
        <div class="form-floating">
            <input type="text" name="title" id="title" class="form-control" placeholder="Title" value="{{ old('title', $meal_plan->title ?? '') }}" required>
            <label for="title">Plan title <span class="text-danger">*</span></label>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-floating">
            <input type="date" name="planned_on" id="planned_on" class="form-control" value="{{ old('planned_on', optional($meal_plan->planned_on ?? null)->format('Y-m-d')) }}" required>
            <label for="planned_on">Date <span class="text-danger">*</span></label>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-floating">
            <input type="number" name="goal_calories" id="goal_calories" class="form-control" placeholder="Goal calories" value="{{ old('goal_calories', $meal_plan->goal_calories ?? '') }}" required min="1" max="20000">
            <label for="goal_calories">Goal calories <span class="text-danger">*</span></label>
        </div>
    </div>
    <div class="col-12">
        <div class="form-floating">
            <textarea name="notes" id="notes" class="form-control" style="height:100px" placeholder="Notes">{{ old('notes', $meal_plan->notes ?? '') }}</textarea>
            <label for="notes">Coach notes <span class="text-secondary small">(optional)</span></label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating">
            <input type="text" name="owner_name" id="owner_name" class="form-control" placeholder="Client name" value="{{ old('owner_name', $meal_plan->user->name ?? '') }}" required pattern="[a-zA-Z\s\-\'\.]+" onkeypress="return /[a-zA-Z\s\-\'\.]/.test(event.key)" title="Only letters, spaces, hyphens, apostrophes, and periods are allowed">
            <label for="owner_name">Client name <span class="text-danger">*</span></label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating">
            <input type="email" name="owner_email" id="owner_email" class="form-control" placeholder="Client email" value="{{ old('owner_email', $meal_plan->user->email ?? '') }}" required>
            <label for="owner_email">Client email <span class="text-danger">*</span></label>
        </div>
    </div>
    <div class="col-12">
        <button class="btn btn-success px-4"><i class="bi bi-check-lg"></i> Save plan</button>
        <a href="{{ route('meal-plans.index') }}" class="btn btn-outline-light ms-2">Cancel</a>
    </div>
</div>


