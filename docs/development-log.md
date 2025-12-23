# NutriTrack Development Log (Laravel 10) – ~380 words

## MVC design choices
I scoped NutriTrack around three core tables with two clear one-to-many relationships: `users -> meal_plans -> meal_entries`, plus a `foods` catalogue. Slugs power SEO-friendly URLs for foods and meal plans. Controllers stay thin by delegating validation to FormRequests and reusing small helpers (slug generation, user resolution). Views sit on a shared `layouts/app.blade.php` that adapts a glassmorphism Bootstrap form template from freefrontend.com; cards and floating labels keep static and dynamic data consistent. Filtering and sorting are handled server-side with safe whitelists to avoid SQL injection. Meal plan pages eager-load `entries.food` so macro totals are calculated without N+1 queries. A reusable partial renders flash/validation alerts to keep feedback consistent.

## CRUD and relationships
Foods: full CRUD with search + sort (name, calories, protein). Each create/update hits an external API before persisting.  
Meal plans: CRUD with date/goal fields, optional notes, and client name/email. Plans list page supports keyword search plus date-range filtering and sorting.  
Meal entries: nested under meal plans; entries belong to a plan and a food, and totals roll up per plan.

## External API validation
To demonstrate independent research, I integrated OpenFoodFacts (free, no key) via a tiny service class that searches by term and can pull nutrients. A custom validation rule `OpenFoodFactsExists` blocks saving foods whose names are not found on the API. When present, API nutriments hydrate calories/macros automatically while retaining user-entered values. Timeouts are short (5s) to avoid UI stalls.

## Originality and technical depth
- SEO slugs with route-model binding for both main resources.  
- External API–driven validation + gentle enrichment.  
- Safe filtering/sorting, eager loading, cascade deletes via FK constraints.  
- Seed data provides a demo user, foods, and a sample plan with entries.  
- UI uses an adapted freefrontend Bootstrap glassmorphism form to meet the “original design” criterion.

## Challenges & solutions
1) **Duplicate users migration**: replaced placeholder migration with domain tables (`meal_plans`, `foods`, `meal_entries`) to avoid conflicts with the default `users` table.  
2) **API reliability**: wrapped OpenFoodFacts calls with short timeouts and graceful fallbacks so CRUD works even if the API is slow.  
3) **SEO-friendly routes**: added slug generators that ensure uniqueness by counting existing slugs with the same base.  
4) **Macro aggregation**: used computed attributes on `MealEntry` and eager loading to keep totals accurate without extra queries.

## Next steps before submission
- Capture and embed screenshots:
  - `public/screenshots/01_migrations.png` – migration setup.
  - `public/screenshots/02_food_crud.png` – first CRUD attempt.
  - `public/screenshots/03_validation.png` – validation/alert example.
- Record a 5-minute voiceover demo (code + running app) and upload to YouTube.
- Push to a public GitHub repo with incremental commits (avoid one big commit).
- Re-run `php artisan migrate --seed`, then manual QA of filters/sorts and API validation.


