# Sales CRM - Laravel 12 Application

A full-featured Sales CRM built with Laravel 12, featuring Role-Based Access Control, Kanban board, Activities tracking, and Dashboard analytics.

## Tech Stack

- **Laravel 12** with PHP 8.3
- **MySQL 8.0** database
- **Blade + Alpine.js** for frontend
- **Tailwind CSS** (CDN)
- **Laravel Sanctum** for API authentication
- **Spatie Laravel Permission** for RBAC
- **Laravel Telescope** for debugging (dev only)

## Features

### Authentication & RBAC
- [x] Role-based access control (Admin, Manager, Agent)
- [x] Login/logout with role-based redirects
- [x] Custom RoleMiddleware
- [x] Sanctum API tokens

### Modules

#### Contacts Module
- [x] Full CRUD operations
- [x] Soft deletes
- [x] Search by name/email/company
- [x] Filter by status and source
- [x] Tags support (JSON)
- [x] Custom fields (JSON)
- [x] Access control (Agent = own only, Manager/Admin = all)

#### Deals Module
- [x] Full CRUD operations
- [x] Kanban board view (SortableJS)
- [x] Drag-and-drop stage updates
- [x] Deal pipeline tracking
- [x] Forecasting value calculation
- [x] Soft deletes

#### Activities Module
- [x] Log activities (Call, Meeting, Email, Task, Demo)
- [x] Link to Contact or Deal
- [x] Due dates with overdue highlighting
- [x] Mark as done functionality
- [x] Filter by type/status/date

#### Dashboard & Analytics
- [x] KPI cards (Pipeline Value, Won This Month, Conversion Rate, Overdue Activities)
- [x] Monthly Revenue chart (Chart.js)
- [x] Pipeline Funnel chart
- [x] Agent Leaderboard (Admin/Manager only)
- [x] 60-second cache on KPIs

#### Notifications
- [x] Activity Reminder notifications (mail + database)
- [x] Deal Stage Changed notifications
- [x] Notification bell with Alpine.js dropdown
- [x] Mark all as read

## Installation

### Prerequisites
- PHP 8.3+
- Composer
- MySQL 8.0

### Setup

```bash
# Clone and install dependencies
composer install

# Copy environment file
cp .env.example .env

# Configure .env with your database credentials
# DB_DATABASE=sales_crm
# DB_USERNAME=root
# DB_PASSWORD=your_password

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Seed the database
php artisan db:seed

# Start the server
php artisan serve
```

### Default Login Credentials
- **Email:** admin@crm.com
- **Password:** password
- **Role:** Admin

## Database Schema

### Tables
- `users` - User accounts with roles
- `contacts` - Customer contacts
- `deals` - Sales deals/pipeline
- `activities` - Activity logs
- `notifications` - In-app notifications
- `personal_access_tokens` - Sanctum API tokens
- `permissions` / `roles` / `role_has_permissions` - Spatie RBAC
- `jobs` / `job_batches` - Queue management
- `cache` - Application cache

### Relationships
- User hasMany Contacts, Deals, Activities
- Contact hasMany Deals, Activities
- Deal belongsTo Contact, User
- Activity belongsTo Contact, Deal (optional), User

## Routes Overview

| Prefix | Middleware | Description |
|--------|------------|-------------|
| `/login` | guest | Login page |
| `/logout` | auth | Logout action |
| `/admin/*` | auth, role:Admin | Admin routes |
| `/manager/*` | auth, role:Manager | Manager routes |
| `/agent/*` | auth, role:Agent | Agent routes |

### Key Routes

#### Dashboard
- `GET /{role}/dashboard` - Role-specific dashboard

#### Contacts
- `GET /{role}/contacts` - List contacts
- `GET /{role}/contacts/create` - Create form
- `POST /{role}/contacts` - Store contact
- `GET /{role}/contacts/{id}` - View contact
- `GET /{role}/contacts/{id}/edit` - Edit form
- `PUT /{role}/contacts/{id}` - Update contact
- `DELETE /{role}/contacts/{id}` - Delete contact

#### Deals
- `GET /{role}/deals` - List deals (table view)
- `GET /{role}/deals/kanban` - Kanban board
- `PATCH /{role}/deals/{id}/stage` - Update deal stage (AJAX)
- Full CRUD routes

#### Activities
- `GET /{role}/activities` - List activities
- `POST /{role}/activities` - Log activity
- `PATCH /{role}/activities/{id}/done` - Mark done

#### Notifications
- `POST /notifications/mark-all-read` - Mark all read

## Services

### DashboardService
- `getTotalPipelineValue()` - Sum of open deals
- `getWonThisMonth()` - Monthly won deals
- `getConversionRate()` - Won / Total * 100
- `getOverdueActivities()` - Past due, not done
- `getMonthlyRevenue()` - Last 12 months chart data
- `getPipelineFunnel()` - Deals per stage
- `getLeaderboard()` - Top 5 agents by revenue

### ContactService
- `getFilteredContacts()` - Paginated with filters

### DealService
- `getKanbanData()` - Grouped by stage
- `getFilteredDeals()` - Paginated with filters
- `getForecastValue()` - Weighted pipeline value

## Jobs & Scheduling

### SendActivityReminders
Runs daily at 08:00 to notify users of activities due tomorrow.

```bash
# Schedule is defined in routes/console.php
# Run scheduler: php artisan schedule:work
```

## Testing

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test --filter=AuthenticationTest

# Run with coverage
php artisan test --coverage
```

### Test Coverage
- Authentication (login, logout, redirects)
- Contact CRUD with access control
- Deal CRUD with Kanban updates
- Activity logging and marking done
- Model relationships

## Project Structure

```
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/         # LoginController
│   │   │   ├── Admin/        # DashboardController
│   │   │   ├── Manager/      # DashboardController
│   │   │   ├── Agent/        # DashboardController
│   │   │   ├── ContactController.php
│   │   │   ├── DealController.php
│   │   │   ├── ActivityController.php
│   │   │   ├── DashboardController.php
│   │   │   └── NotificationController.php
│   │   ├── Middleware/
│   │   │   └── RoleMiddleware.php
│   │   └── Requests/
│   │       ├── Auth/
│   │       ├── ContactRequest.php
│   │       ├── DealRequest.php
│   │       └── ActivityRequest.php
│   ├── Models/
│   │   ├── User.php          # HasRoles + HasApiTokens
│   │   ├── Contact.php
│   │   ├── Deal.php
│   │   └── Activity.php
│   ├── Services/
│   │   ├── DashboardService.php
│   │   ├── ContactService.php
│   │   └── DealService.php
│   ├── Notifications/
│   │   ├── ActivityReminderNotification.php
│   │   └── DealStageChangedNotification.php
│   └── Jobs/
│       └── SendActivityReminders.php
├── database/
│   ├── migrations/
│   └── factories/
│       ├── ContactFactory.php
│       ├── DealFactory.php
│       └── ActivityFactory.php
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php
│       ├── auth/
│       │   └── login.blade.php
│       ├── contacts/
│       ├── deals/
│       ├── activities/
│       ├── dashboard/
│       └── components/
│           └── notification-bell.blade.php
└── routes/
    ├── web.php
    └── console.php
```

## Configuration Files

### .env Required Variables
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sales_crm
DB_USERNAME=root
DB_PASSWORD=your_password

QUEUE_CONNECTION=database
MAIL_MAILER=smtp

SESSION_DRIVER=database
CACHE_STORE=database
```

### Packages Installed
- `laravel/sanctum` - API authentication
- `spatie/laravel-permission` - RBAC
- `barryvdh/laravel-dompdf` - PDF generation
- `maatwebsite/excel` - Excel export
- `laravel/telescope` - Debugging (dev)

## Access Control Matrix

| Feature | Admin | Manager | Agent |
|---------|-------|---------|-------|
| View all contacts | ✓ | ✓ | ✗ |
| View own contacts | ✓ | ✓ | ✓ |
| Create contacts | ✓ | ✓ | ✓ |
| Edit all contacts | ✓ | ✓ | ✗ |
| View all deals | ✓ | ✓ | ✗ |
| View own deals | ✓ | ✓ | ✓ |
| Create deals | ✓ | ✓ | ✓ |
| Edit all deals | ✓ | ✓ | ✗ |
| Kanban board | ✓ | ✓ | ✓ |
| Activities | ✓ | ✓ | ✓ |
| Dashboard (all data) | ✓ | ✓ | ✗ |
| Leaderboard | ✓ | ✓ | ✗ |
| Mark notifications read | ✓ | ✓ | ✓ |

## Commands

```bash
# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Fresh install
php artisan migrate:fresh --seed

# Run scheduler
php artisan schedule:work

# Run queue worker
php artisan queue:work

# Telescope (dev only)
php artisan telescope:publish

# List routes
php artisan route:list
```

## Changelog

### v1.0.0
- Initial setup with Laravel 12
- Authentication with Sanctum
- RBAC with Spatie Permission
- Contacts, Deals, Activities modules
- Dashboard with charts
- Notifications system
- Unit tests

## License

MIT License - See LICENSE file for details.
