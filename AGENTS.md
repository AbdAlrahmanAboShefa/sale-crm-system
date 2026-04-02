# Agent Guidelines for Sales CRM

This document provides guidelines for AI agents working in this Laravel Sales CRM codebase.

## Code Review Summary (Recent Issues Fixed)

The following bugs were identified and should be avoided in future code:

1. **Carbon Mutation Bug**: Never call `addDay()` twice on the same Carbon instance. Use `$tomorrow->copy()->endOfDay()` instead of calling `addDay()` again on `now()`.

2. **Duplicate Logout Routes**: Both `routes/web.php` and `routes/auth.php` define `POST /logout` with the same name. The auth.php version takes precedence.

3. **Route Naming Collision**: Each role group defines identically-named routes (e.g., `deals.kanban`). Always use role-specific prefixes when generating URLs.

4. **Case-Sensitive Imports**: PHP class names are case-insensitive but follow PSR-4 conventions. Use proper casing (`LoginController` not `logincontroller`).

---

## Git & Version Control

**IMPORTANT**: When making changes to this codebase, follow this workflow:

1. After completing successful changes (verified by tests or user confirmation), commit immediately:
   ```bash
   git add -A
   git commit -m "Description of changes"
   git push origin master
   ```

2. Only push when changes are successfully verified - do not push broken code.

3. Keep commit messages descriptive but concise (under 72 characters for the first line).

4. Do NOT commit sensitive files (`.env`, credentials, API keys, etc.).

---

## Build/Lint/Test Commands

### Setup & Installation
```bash
composer install           # Install PHP dependencies
npm install               # Install Node dependencies
```

### Running the Application
```bash
php artisan serve                    # Start dev server
php artisan queue:listen --tries=1   # Start queue worker
npm run dev                          # Start Vite (hot reload)
```

### Testing
```bash
php artisan test                     # Run all tests
php artisan test --filter=TestName  # Run specific test
php artisan test --filter=ClassName # Run specific test class
./vendor/bin/pest                   # Run Pest tests directly
./vendor/bin/pest --filter=TestName # Run single Pest test
```

### Linting & Code Style
```bash
./vendor/bin/pint                    # Auto-fix code style (Laravel Pint)
./vendor/bin/pint --test            # Check without fixing
```

### Database
```bash
php artisan migrate                  # Run migrations
php artisan migrate:fresh           # Reset and re-migrate
php artisan db:seed                # Seed database
php artisan db:seed --class=ClassName # Seed specific class
```

---

## Code Style Guidelines

### PHP/Laravel Conventions

1. **Imports**: Use absolute namespaces with `use` statements. Group by: Laravel core, Packages, App namespace.
   ```php
   use Illuminate\Http\Request;        // Laravel
   use Spatie\Permission\Models\Role;  // Packages
   use App\Models\User;                // App
   ```

2. **Naming Conventions**:
   - Controllers: `PascalCase` (e.g., `DealController`)
   - Methods: `camelCase` (e.g., `updateStage`)
   - Variables: `camelCase` (e.g., `$isAdminOrManager`)
   - Database columns: `snake_case` (e.g., `user_id`)
   - Route names: `snake_case.dot` (e.g., `deals.kanban`)
   - Views: `kebab-case.blade.php`

3. **Type Hints**: Always use strict type hints where applicable.
   ```php
   public function index(Request $request): View
   public function store(DealRequest $request): RedirectResponse
   public function updateStage(Request $request, Deal $deal): JsonResponse
   ```

4. **Controllers**: Use constructor injection for services.
   ```php
   public function __construct(
       private DealService $dealService
   ) {}
   ```

5. **Authorization**: Create dedicated methods for authorization checks.
   ```php
   private function authorizeDeal(Deal $deal): void
   {
       // ... logic
   }
   ```

### Blade Templates

1. **Component Layouts**: Use `<x-app-layout>` for pages that need the full layout.
   ```blade
   <x-app-layout title="Dashboard">
       {{ $slot }}
   </x-app-layout>
   ```

2. **Stacks for Scripts**: Always push scripts to stacks, don't use standalone `<script>` tags.
   ```blade
   @push('scripts')
   <script src="..."></script>
   @endpush
   ```

3. **Include Font Awesome**: Add in `<head>` when using icons:
   ```blade
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
   ```

### Service Layer

1. **Service Location**: Create services in `app/Services/` for business logic.
2. **Cache Keys**: Use consistent cache key patterns:
   ```php
   $cacheKey = "pipeline_value_{$userId}_{$isAdminOrManager}";
   Cache::remember($cacheKey, 60, fn() => ...);
   ```

### Database Queries

1. **Avoid N+1**: Always eager load relationships:
   ```php
   Activity::with(['deal', 'user', 'contact'])->get();
   ```

2. **Per-Stage Queries**: When querying multiple stages, create separate queries:
   ```php
   // Bad: Query built once, then filtered
   $query = Deal::whereNotIn('stage', ['Lost']);
   
   // Good: Fresh query for each stage
   foreach ($stages as $stage) {
       $funnel[$stage] = Deal::where('stage', $stage)->count();
   }
   ```

### Error Handling

1. **Redirect with Errors**: Always include success/error flash messages.
   ```php
   return redirect()->route('deals.index')
       ->with('success', 'Deal created successfully.');
   ```

2. **Abort with Messages**: Use descriptive error messages for 403/404.
   ```php
   abort(403, 'Unauthorized action.');
   ```

### Model Conventions

1. **Deal Stages**: Use these exact stage names:
   ```php
   const STAGES = ['New', 'Contacted', 'Qualified', 'Proposal', 'Negotiation', 'Won', 'Lost'];
   ```

2. **Activity Types**: Use these exact type names:
   ```php
   const TYPES = ['Call', 'Meeting', 'Email', 'Task', 'Demo'];
   ```

3. **Field Names**: Deal model uses `title` (not `name`), Activity uses `note` (not `description`).

### Role-Based Access

1. **Route Prefixes**: Always use role-specific route names:
   ```php
   route('admin.deals.index')  // NOT route('deals.index')
   route('manager.dashboard')
   route('agent.contacts.create')
   ```

2. **Route Groups**: Admin, Manager, and Agent have separate route groups with same-named routes. Be explicit about which role prefix to use.

---

## Testing Guidelines

1. **Test File Location**: Tests go in `tests/Feature/` or `tests/Unit/`
2. **Pest Framework**: This project uses Pest for testing
3. **Run Single Test**: Use `--filter` flag with test name

---

## Common Pitfalls to Avoid

1. **Don't re-open Carbon instances**: `now()->addDay()` mutates; use `$date->copy()->addDay()`
2. **Don't use standalone HTML in views**: Use `@extends('layouts.app')` or `<x-app-layout>`
3. **Don't hardcode role checks**: Use Spatie's `@role()` directive or `$user->hasRole()`
4. **Don't skip eager loading**: Always use `with()` for relationships in lists/loops
5. **Don't use wrong field names**: Deal uses `title`, Activity uses `note`
