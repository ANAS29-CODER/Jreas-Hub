---
name: run
description: Launch and test the Jireas Hub Filament admin panel (courses, workshops, initiatives, trainings, donation campaigns, categories, site gallery, activity log, pages, settings). Use when asked to run, test, or verify the admin dashboard.
---

# Running and testing Jireas Hub's admin panel

This is a Laravel 12 + Filament 3 app. The admin panel lives at `/admin`, not the app root.

## 1. Prerequisites

- MySQL must be running (Laragon on this machine). Check `.env` for `DB_DATABASE=jireas_hub`.
- Migrations + seeders give you a working admin user and base data:

```bash
php artisan migrate --force
php artisan db:seed --force
```

This seeds: 1 admin user, 8 categories (2 per type), 2 pages (about/contact), and default settings — via `AdminUserSeeder`, `CategorySeeder`, `PageSeeder`, `SettingSeeder`.

**Admin login**: `admin@jireashub.com` / `password`

## 2. Start the server

```bash
php artisan serve --port=8787
```

Login page: `http://127.0.0.1:8787/admin/login`. Dashboard: `/admin`.

## 3. No browser tool available? Here's the gotcha.

**Filament's login form is a Livewire component, not a plain HTML form.** A raw `curl -X POST /admin/login` will hang or fail — Livewire needs its own snapshot/checksum protocol, not a normal form POST. Don't waste time fighting this with curl.

Instead, to test pages as an authenticated admin without a real browser, drive the request through Laravel's HTTP kernel directly in tinker:

```bash
php artisan tinker --execute="
use App\Models\User;
\$kernel = app(\Illuminate\Contracts\Http\Kernel::class);
\$user = User::where('role', 'admin')->first();

\$routes = [
    '/admin' => 'Dashboard',
    '/admin/courses' => 'Courses',
    '/admin/workshops' => 'Workshops',
    '/admin/initiatives' => 'Initiatives',
    '/admin/trainings' => 'Trainings',
    '/admin/donation-campaigns' => 'DonationCampaigns',
    '/admin/categories' => 'Categories',
    '/admin/site-galleries' => 'SiteGallery',
    '/admin/activity-logs' => 'ActivityLog',
    '/admin/pages' => 'Pages',
    '/admin/settings' => 'Settings',
];

foreach (\$routes as \$uri => \$label) {
    \$request = \Illuminate\Http\Request::create(\$uri, 'GET');
    \$request->setLaravelSession(app('session.store'));
    app('auth')->guard('web')->login(\$user);
    \$request->setUserResolver(fn () => \$user);

    try {
        \$response = \$kernel->handle(\$request);
        echo \$label . ': ' . \$response->getStatusCode() . PHP_EOL;
    } catch (\Throwable \$e) {
        echo \$label . ': EXCEPTION - ' . get_class(\$e) . ' - ' . \$e->getMessage() . PHP_EOL;
    }
}
"
```

If a real browser tool (Playwright/chromium-cli) IS available, prefer that instead — log in via the actual form fields at `/admin/login`, since it exercises the real JS/Livewire flow rather than bypassing it.

## 4. Seeding sample content for a section

Always create records through the resource's `Service` class (matches what the Filament Create pages actually call) — never `Model::create()` directly, or you skip the same validation/logic paths the UI uses. Example for courses:

```bash
php artisan tinker --execute="
use App\Services\CourseService;
use App\Models\Category;
use App\Enums\CategoryType;
use App\Enums\DeliveryType;
use App\Enums\CourseLevel;
use App\Enums\PublishStatus;
use Illuminate\Support\Str;

\$cat = Category::where('type', CategoryType::Course->value)->first();
\$title = 'دورة تجريبية';
app(CourseService::class)->create([
    'title' => \$title,
    'slug' => Str::slug(\$title),
    'short_description' => 'وصف مختصر',
    'description' => 'وصف تفصيلي',
    'category_id' => \$cat->id,
    'instructor_name' => 'مدرب تجريبي',
    'type' => DeliveryType::Online->value,
    'level' => CourseLevel::Beginner->value,
    'duration' => 10,
    'seats' => 30,
    'start_date' => now(),
    'end_date' => now()->addDays(30),
    'price' => 100,
    'status' => PublishStatus::Published->value,
]);
"
```

Same pattern for `WorkshopService`, `InitiativeService`, `TrainingService`, `DonationCampaignService`, `ActivityLogService`. `SiteGalleryService` needs a real uploaded image file for `image_path` — don't fabricate a fake path, either skip it or use a real test image.

## 5. Dashboard widgets are lazy-loaded — this changes how you verify them

`StatsOverviewWidget`, `LatestActivitiesWidget`, and `DonationProgressWidget` all declare `$isLazy = true` for performance (see `app/Filament/Widgets/`). This means:

- A plain HTTP response for `/admin` will NOT contain the widgets' actual data — only an empty Livewire placeholder (`"data":[]`, `"lazyLoaded":false`) with an `x-intersect` Alpine directive that fires the real data fetch only in a real browser when the widget scrolls into view.
- **Do not conclude the widgets are broken if their numbers don't appear in a raw curl/tinker HTTP response.** That's expected.
- To verify widget logic directly, call the widget's data method via reflection instead of parsing HTML:

```bash
php artisan tinker --execute="
use Illuminate\Support\Facades\Cache;
Cache::forget('dashboard.stats-overview');

\$widget = new \App\Filament\Widgets\StatsOverviewWidget();
\$reflection = new ReflectionMethod(\$widget, 'getStats');
\$reflection->setAccessible(true);
\$stats = \$reflection->invoke(\$widget);

foreach (\$stats as \$stat) {
    \$r = new ReflectionClass(\$stat);
    \$label = \$r->getProperty('label'); \$label->setAccessible(true);
    \$value = \$r->getProperty('value'); \$value->setAccessible(true);
    echo \$label->getValue(\$stat) . ': ' . \$value->getValue(\$stat) . PHP_EOL;
}
"
```

`StatsOverviewWidget`'s stats are cached for 5 minutes (`dashboard.stats-overview` cache key) — clear it first if you just changed seed data and want fresh numbers.

## 6. Cleanup

```bash
pkill -f "artisan serve"
```
