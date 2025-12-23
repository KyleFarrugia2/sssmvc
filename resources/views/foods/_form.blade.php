<div class="row g-3">
    <div class="col-12 col-md-6">
        <div class="form-floating mb-3">
            <input type="text" name="name" id="name" class="form-control" placeholder="Food name" value="{{ old('name', $food->name ?? '') }}" required>
            <label for="name">Food name</label>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="form-floating mb-3">
            <input type="number" name="calories_per_100g" id="calories" class="form-control " placeholder="Calories per 100g" value="{{ old('calories_per_100g', $food->calories_per_100g ?? '') }}" required>
            <label for="calories">Calories / 100g</label>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="form-floating mb-3">
            <input type="number" step="0.1" name="protein" id="protein" class="form-control " placeholder="Protein" value="{{ old('protein', $food->protein ?? '') }}" required>
            <label for="protein">Protein (g)</label>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="form-floating mb-3">
            <input type="number" step="0.1" name="carbs" id="carbs" class="form-control " placeholder="Carbs" value="{{ old('carbs', $food->carbs ?? '') }}" required>
            <label for="carbs">Carbs (g)</label>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="form-floating mb-3">
            <input type="number" step="0.1" name="fat" id="fat" class="form-control " placeholder="Fat" value="{{ old('fat', $food->fat ?? '') }}" required>
            <label for="fat">Fat (g)</label>
        </div>
    </div>
    <div class="col-12">
        <button class="btn btn-success px-4"><i class="bi bi-check-lg"></i> Save food</button>
        <a href="{{ route('foods.index') }}" class="btn btn-outline-light ms-2">Cancel</a>
    </div>
</div>


