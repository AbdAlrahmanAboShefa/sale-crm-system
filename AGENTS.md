# Agent Guidelines for Sales CRM

This document provides guidelines for AI agents working in this Laravel Sales CRM codebase.

---

## Build/Lint/Test Commands

### Setup & Installation
```bash
composer install              # Install PHP dependencies
npm install                  # Install Node dependencies
```

### Running the Application
```bash
php artisan serve                     # Start dev server (http://localhost:8000)
php artisan queue:listen --tries=1    # Start queue worker
npm run dev                           # Start Vite (hot reload)
```

### Testing (Pest Framework)
```bash
php artisan test                      # Run all tests
php artisan test --filter=TestName    # Run specific test
php artisan test --filter=ClassName   # Run specific test class
./vendor/bin/pest                     # Run Pest directly
./vendor/bin/pest --filter=TestName   # Run single Pest test
```

### Linting & Code Style (Laravel Pint)
```bash
./vendor/bin/pint                     # Auto-fix code style
./vendor/bin/pint --test              # Check without fixing
```

### Database
```bash
php artisan migrate                   # Run migrations
php artisan migrate:fresh             # Reset and re-migrate
php artisan db:seed                  # Seed database
php artisan db:seed --class=ClassName # Seed specific class
```

---

## Git & Version Control

1. After completing changes (verified by tests), commit immediately:
   ```bash
   git add -A && git commit -m "Description" && git push origin master
   ```
2. Only push verified working code
3. Keep commit messages under 72 characters
4. **Never commit**: `.env`, credentials, API keys, secrets

---

## PHP/Laravel Conventions

### Imports (Ordered by Priority)
```php
use Illuminate\Foundation\Vite;           // Laravel Foundation
use Illuminate\Http\Request;               // Laravel Contracts
use Spatie\Permission\Models\Role;         // Packages
use App\Http\Controllers\Controller;       // App (local first)
```

### Naming Conventions
| Type | Convention | Example |
|------|------------|---------|
| Controllers | PascalCase | `DealController` |
| Methods | camelCase | `updateStage` |
| Variables | camelCase | `$isAdminOrManager` |
| DB columns | snake_case | `user_id` |
| Route names | snake_case.dot | `deals.kanban` |
| Views | kebab-case | `create-deal.blade.php` |

### Type Hints (Required)
```php
public function index(Request $request): View
public function store(DealRequest $request): RedirectResponse
public function updateStage(Request $request, Deal $deal): JsonResponse
```

### Constructor Injection
```php
public function __construct(
    private DealService $dealService,
    private ContactService $contactService
) {}
```

---

## Security Guidelines

1. **SQL Injection**: Use query builder with parameter binding - never string concatenation
2. **XSS**: Use `{{ }}` (escaped) for user data, `{!! !!}` only for trusted content
3. **CSRF**: All POST forms must have `@csrf` directive
4. **Mass Assignment**: Always use `$fillable` on models
5. **Authorization**: Check permissions in controllers using dedicated methods:
   ```php
   private function authorizeContact(Contact $contact): void
   {
       $user = auth()->user();
       if (!$user->hasRole(['Admin', 'Manager']) && $contact->user_id !== $user->id) {
           abort(403, 'Unauthorized action.');
       }
   }
   ```
6. **Rate Limiting**: Use `throttle:attempts,minutes` middleware on routes
7. **External Links**: Add `rel="noopener noreferrer"` to `target="_blank"` links

---

## Localization (EN/AR)

This CRM supports English and Arabic with RTL support.

### Translation Keys
- All user-facing text goes in `lang/en/messages.php` and `lang/ar/messages.php`
- Use the `__()` helper: `{{ __('messages.deals.title') }}`
- **Array keys**: Use singular form (`messages.activities.type` not `types`)

### RTL Support
```blade
{{-- In layouts/app.blade.php, add dir attribute --}}
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

{{-- RTL-safe margins --}}
<i class="fas fa-save {{ app()->getLocale() === 'ar' ? 'ms-2 me-0' : 'me-2' }}"></i>
```

### Font for Arabic
```blade
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
```

---

## Database & Models

### N+1 Prevention
```php
// Always eager load relationships
Activity::with(['deal', 'user', 'contact'])->get();
Deal::with(['contact', 'user'])->paginate();
```

### Field Names
| Model | Field | NOT |
|-------|-------|-----|
| Deal | `title` | `name` |
| Activity | `note` | `description` |
| Deal | `stage` | `status` |

### Constants
```php
const STAGES = ['New', 'Contacted', 'Qualified', 'Proposal', 'Negotiation', 'Won', 'Lost'];
const TYPES = ['Call', 'Meeting', 'Email', 'Task', 'Demo'];
```

---

## Views & Blade Templates

### Layout
- Use `layouts/app.blade.php` with `@extends('layouts.app')`
- Or `<x-app-layout>` component

### Scripts
```blade
@push('scripts')
<script src="..."></script>
@endpush
```

### Font Awesome
```blade
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
```

---

## Role-Based Access

**Route prefixes are required:**
```php
route('admin.deals.index')     // Admin deals
route('manager.deals.index')    // Manager deals
route('agent.contacts.create')  // Agent contacts
```

Each role (Admin, Manager, Agent) has separate route groups with identically-named routes. **Always use the role-specific prefix.**

---

## Error Handling

```php
// Redirect with flash message
return redirect()->route('deals.index')
    ->with('success', __('messages.deals.created_success'));

// Abort with message
abort(403, 'Unauthorized action.');

// Throttle errors are handled in bootstrap/app.php
```

---

## Code Review Summary (Pitfalls Fixed)

1. **Carbon Mutation**: Use `$date->copy()->addDay()`, not `now()->addDay()->addDay()`
2. **Duplicate Routes**: `routes/auth.php` and `routes/web.php` both define `POST /logout`
3. **Route Naming**: Use role-specific prefixes (`admin.deals.index`, not `deals.index`)
4. **Translation Arrays**: `__('messages.activities.type')` for strings, not `types`

---

## Common Pitfalls

1. Don't re-open Carbon instances - use `copy()` for mutations
2. Don't use standalone `<script>` tags - use `@push/@stack`
3. Don't hardcode roles - use `$user->hasRole()` or Spatie's `@role()` directive
4. Don't skip eager loading - always use `with()` for relationships in loops
5. Don't use wrong field names - Deal uses `title`, Activity uses `note`
