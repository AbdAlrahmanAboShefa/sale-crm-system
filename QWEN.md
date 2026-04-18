# Sales CRM — Project Context

## Overview

A multi-tenant **Sales CRM** built on **Laravel 12** with PHP 8.3. It provides role-based access control (Super Admin, Admin, Manager, Agent), full CRUD for Contacts/Deals/Activities, a Kanban board for deal pipeline management, dashboard analytics, AI-powered email generation, and bilingual support (English/Arabic with RTL).

The application uses a **tenant-based multi-tenant architecture** where each tenant (company) has isolated data, users, and branding. Tenants subscribe to plans (Free, Basic, Pro, Enterprise) with different resource limits.

## Tech Stack

| Layer | Technology |
|-------|-----------|
| **Backend** | Laravel 12, PHP 8.3+ |
| **Database** | MySQL 8.0 |
| **Frontend** | Blade templates, Alpine.js, Tailwind CSS (CDN), Vite |
| **Charts** | Chart.js |
| **Kanban** | SortableJS |
| **RBAC** | Spatie Laravel Permission |
| **API Auth** | Laravel Sanctum |
| **PDF** | Barryvdh DomPDF |
| **Excel** | Maatwebsite Excel |
| **AI** | OpenAI PHP Client |
| **Testing** | Pest Framework |
| **Dev Tools** | Laravel Telescope, Laravel Pail |

## Project Structure

```
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin-specific (Users, Settings)
│   │   │   ├── Agent/          # Agent role controllers
│   │   │   ├── Auth/           # LoginController, TenantRegistrationController
│   │   │   ├── Manager/        # Manager role controllers
│   │   │   ├── SuperAdmin/     # Super Admin (Dashboard, Tenants)
│   │   │   ├── ContactController.php
│   │   │   ├── DealController.php
│   │   │   ├── ActivityController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── AIEmailController.php
│   │   │   ├── LandingController.php
│   │   │   ├── LanguageController.php
│   │   │   ├── NotificationController.php
│   │   │   └── ProfileController.php
│   │   ├── Middleware/
│   │   │   ├── RoleMiddleware.php
│   │   │   └── SetLocale.php
│   │   └── Requests/
│   │       ├── ContactRequest.php, DealRequest.php, ActivityRequest.php
│   ├── Models/
│   │   ├── User.php            # HasRoles, HasApiTokens, BelongsTo Tenant
│   │   ├── Tenant.php          # HasMany users/contacts/deals/activities
│   │   ├── Contact.php         # SoftDeletes, BelongsTo User/Tenant
│   │   ├── Deal.php            # SoftDeletes, BelongsTo Contact/User/Tenant
│   │   └── Activity.php        # BelongsTo Contact/Deal/User
│   ├── Services/
│   │   ├── DashboardService.php
│   │   ├── ContactService.php
│   │   └── DealService.php
│   ├── Notifications/
│   │   ├── ActivityReminderNotification.php
│   │   └── DealStageChangedNotification.php
│   ├── Jobs/
│   │   └── SendActivityReminders.php
│   ├── Providers/
│   │   └── AppServiceProvider.php
│   └── Traits/
├── config/
│   ├── permission.php
│   ├── telescope.php
│   └── ...
├── database/
│   ├── migrations/             # 16 migration files
│   ├── seeders/
│   └── factories/
├── lang/
│   ├── en/messages.php         # English translations
│   └── ar/messages.php         # Arabic translations (RTL)
├── resources/
│   ├── views/
│   │   ├── layouts/app.blade.php        # Main dark theme layout
│   │   ├── landing/index.blade.php      # Public landing page
│   │   ├── auth/
│   │   │   ├── login.blade.php          # Login (dark, split-panel)
│   │   │   └── register.blade.php       # Registration (dark, split-panel)
│   │   ├── admin/                       # Admin dashboard + users + settings
│   │   ├── manager/                     # Manager dashboard
│   │   ├── agent/                       # Agent dashboard
│   │   ├── super_admin/
│   │   │   ├── dashboard.blade.php      # Super admin overview
│   │   │   └── tenants/                 # Tenant CRUD (index, create, edit, show)
│   │   ├── contacts/                    # Full CRUD + form partial
│   │   ├── deals/                       # Full CRUD + kanban + form partial
│   │   ├── activities/                  # Full CRUD + form partial
│   │   ├── components/
│   │   │   ├── language-switcher.blade.php
│   │   │   ├── notification-bell.blade.php
│   │   │   └── ai-email-generator.blade.php
│   │   ├── billing/upgrade.blade.php
│   │   ├── profile/index.blade.php
│   │   └── errors/
│   ├── css/app.css                      # Tailwind directives
│   └── js/
├── public/
│   └── css/dark-theme.css               # Shared dark theme styles
├── routes/
│   └── web.php                          # All route definitions
├── tests/
├── composer.json
├── package.json
├── tailwind.config.js
├── vite.config.js (missing — uses defaults)
├── docker-compose.yaml
└── Dockerfile
```

## Roles & Permissions

| Feature | Super Admin | Admin | Manager | Agent |
|---------|:-----------:|:-----:|:-------:|:-----:|
| Manage Tenants | ✓ | — | — | — |
| View all data | ✓ | ✓ | ✓ | — |
| Users management | — | ✓ | — | — |
| Kanban board | — | ✓ | ✓ | ✓ |
| Create/edit all contacts | — | ✓ | ✓ | — |
| Create/edit own contacts | — | ✓ | ✓ | ✓ |
| Create/edit all deals | — | ✓ | ✓ | — |
| Create/edit own deals | — | ✓ | ✓ | ✓ |
| Activities | — | ✓ | ✓ | ✓ |
| Leaderboard | — | ✓ | ✓ | — |
| Settings | — | ✓ | — | — |

## Key Commands

### Setup
```bash
composer install              # PHP dependencies
npm install                   # Node dependencies
cp .env.example .env          # Environment config
php artisan key:generate      # App key
php artisan migrate           # Run migrations
php artisan db:seed           # Seed database
```

### Development
```bash
composer dev                  # Server + queue + logs + Vite (concurrently)
php artisan serve             # Dev server (http://localhost:8000)
php artisan queue:listen --tries=1   # Queue worker
npm run dev                   # Vite hot reload
php artisan schedule:work     # Run scheduler
```

### Testing
```bash
php artisan test              # Run all tests (Pest)
php artisan test --filter=TestName   # Specific test
./vendor/bin/pest             # Direct Pest runner
```

### Code Quality
```bash
./vendor/bin/pint             # Auto-fix PHP code style
./vendor/bin/pint --test      # Check without fixing
```

### Database
```bash
php artisan migrate:fresh --seed   # Reset and re-seed
php artisan db:seed --class=ClassName   # Seed specific class
php artisan route:list              # List all routes
```

### Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

## Default Credentials

| Email | Password | Role |
|-------|----------|------|
| admin@crm.com | password | Admin |

## Architecture Notes

### Multi-Tenant Model
- **Tenant** is the top-level entity (company/organization)
- Each **User** belongs to a Tenant via `tenant_id`
- All domain models (Contact, Deal, Activity) are scoped to a Tenant
- Super Admin operates outside tenant scope — manages all tenants
- Plans enforce limits: users (Free=3, Basic=10, Pro=25, Enterprise=∞), contacts (Free=50, Basic=500, Pro/Enterprise=∞)
- Trial banner shows when trial ends within 7 days
- `tenant.active` middleware gates access for inactive tenants

### Dark Theme System
- Layout: `layouts/app.blade.php` — sets `body class="dark-mode"`
- Shared styles: `public/css/dark-theme.css`
- Component classes: `dark-card`, `dark-stats-card`, `dark-btn`, `dark-input`, `dark-select`, `dark-badge`, `dark-table`, `dark-nav-item`, `dark-sidebar`, `dark-header`, `dark-empty-state`, `dark-modal`, `dark-toast`, `dark-pagination`
- Landing/Auth pages use **inline CSS** with custom design tokens (not Tailwind)
- Authenticated pages use **Tailwind CDN** + dark-theme.css classes
- Landing page and auth pages use **Bricolage Grotesque** + **Plus Jakarta Sans** (English) / **Tajawal** (Arabic)

### Localization
- English (`lang/en/messages.php`) and Arabic (`lang/ar/messages.php`)
- HTML `dir` attribute toggles between `ltr` and `rtl`
- All text uses `__('messages.*')` helper
- CSS uses `inset-inline-start/end`, `border-inline-end/start`, `margin-inline-start/end` for RTL-safe positioning
- Font: Tajawal for Arabic, Bricolage Grotesque + Plus Jakarta Sans for English

### Security
- Custom `RoleMiddleware` guards role-specific route groups
- `$fillable` on all models prevents mass assignment
- Sanctum API tokens for API access
- CSRF protection on all POST forms
- SoftDeletes on Contact and Deal models

### Notifications & Jobs
- `SendActivityReminders` job runs daily at 08:00
- Notifies users about activities due tomorrow
- Notification bell component with Alpine.js dropdown
- Mark all as read via POST to `/notifications/mark-all-read`

### AI Email Generator
- `AIEmailController` uses OpenAI PHP client
- Component: `components/ai-email-generator.blade.php`
- Supports email types (follow-up, proposal, welcome, meeting, thank you, custom)
- Tone options: formal, friendly, salesy, casual

## View Conventions

- Views extend `layouts/app.blade.php` via `@extends('layouts.app')`
- Page title via `$title` variable
- Content injected via `@yield('content')`
- Scripts pushed via `@push('scripts')` / `@stack('scripts')`
- Landing and auth pages are **standalone** (do not extend layout)
- Font Awesome 6.5.1 loaded via CDN
- Alpine.js loaded via CDN
- Chart.js used in dashboards

## Common Pitfalls

1. **Carbon Mutation**: Use `$date->copy()->addDay()`, not `now()->addDay()` (mutates in place)
2. **Route Naming**: Always use role-specific prefixes (`admin.deals.index`, not `deals.index`)
3. **Field Names**: Deal uses `title` (not `name`), Activity uses `note` (not `description`), Deal uses `stage` (not `status`)
4. **N+1 Queries**: Always eager load with `with()` for relationships in loops
5. **Script Tags**: Use `@push/@stack` instead of standalone `<script>` tags in views that extend layout
6. **Translation Keys**: Use `__('messages.activities.type')` for strings, not plural `types`
7. **Vite Config**: `vite.config.js` is missing from repo — Tailwind loads via CDN in views
