<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AIEmailController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\TenantRegistrationController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\PaymentDashboardController;
use App\Http\Controllers\SuperAdmin\TenantController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware('set.locale')->group(function () {
    Route::get('/', [LandingController::class, 'index'])->name('landing');

    Route::get('/home', function () {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('Super Admin')) {
                return redirect()->route('super_admin.dashboard');
            } elseif ($user->hasRole('Admin')) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->hasRole('Manager')) {
                return redirect()->route('manager.dashboard');
            } else {
                return redirect()->route('agent.dashboard');
            }
        }

        return redirect()->route('landing');
    })->name('home');
});

Route::middleware(['set.locale', 'guest'])->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.submit');
    Route::get('/register', [TenantRegistrationController::class, 'create'])->name('register');
    Route::post('/register', [TenantRegistrationController::class, 'store'])->name('register.store');
});

Route::get('/language/{locale}', [LanguageController::class, 'switch'])
    ->middleware(['set.locale'])
    ->name('language.switch');

Route::middleware(['set.locale', 'auth', 'role:Admin', 'tenant.active'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('contacts', ContactController::class)->names('contacts');
    Route::get('/deals/kanban', [DealController::class, 'kanban'])->name('deals.kanban');
    Route::patch('/deals/{deal}/stage', [DealController::class, 'updateStage'])->name('deals.updateStage');
    Route::resource('deals', DealController::class)->names('deals');

    Route::resource('activities', ActivityController::class)->names('activities');
    Route::patch('/activities/{activity}/done', [ActivityController::class, 'markDone'])->name('activities.markDone');

    Route::resource('users', UserController::class)->names('users');
    Route::post('/users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
});

Route::middleware(['set.locale', 'auth', 'role:Manager', 'tenant.active'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('contacts', ContactController::class)->names('contacts');
    Route::get('/deals/kanban', [DealController::class, 'kanban'])->name('deals.kanban');
    Route::patch('/deals/{deal}/stage', [DealController::class, 'updateStage'])->name('deals.updateStage');
    Route::resource('deals', DealController::class)->names('deals');

    Route::resource('activities', ActivityController::class)->names('activities');
    Route::patch('/activities/{activity}/done', [ActivityController::class, 'markDone'])->name('activities.markDone');
});

Route::middleware(['set.locale', 'auth', 'role:Agent', 'tenant.active'])->prefix('agent')->name('agent.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('contacts', ContactController::class)->names('contacts');
    Route::get('/deals/kanban', [DealController::class, 'kanban'])->name('deals.kanban');
    Route::patch('/deals/{deal}/stage', [DealController::class, 'updateStage'])->name('deals.updateStage');
    Route::resource('deals', DealController::class)->names('deals');

    Route::resource('activities', ActivityController::class)->names('activities');
    Route::patch('/activities/{activity}/done', [ActivityController::class, 'markDone'])->name('activities.markDone');
});

Route::middleware(['set.locale', 'auth'])->group(function () {
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/ai/generate-email', [AIEmailController::class, 'generate'])->name('ai.email.generate');
});

Route::middleware(['set.locale', 'auth'])->group(function () {
    Route::get('/billing/upgrade', [BillingController::class, 'index'])->name('billing.upgrade');
    Route::post('/billing/checkout', [BillingController::class, 'checkout'])->name('billing.checkout');
    Route::post('/billing/payment-intent', [BillingController::class, 'createPaymentIntent'])->name('billing.payment-intent');
    Route::post('/billing/confirm-payment', [BillingController::class, 'confirmPayment'])->name('billing.confirm-payment');
    Route::post('/billing/cancel-subscription', [BillingController::class, 'cancelSubscription'])->name('billing.cancel-subscription');
    Route::get('/billing/transactions', [BillingController::class, 'transactions'])->name('billing.transactions');
    Route::get('/billing/plans', [BillingController::class, 'getPlans'])->name('billing.plans');
    
});

// Stripe webhook (no auth middleware - Stripe signs requests with webhook secret)
Route::post('/webhooks/stripe', [BillingController::class, 'webhook'])->name('webhooks.stripe');

Route::middleware(['set.locale', 'auth', 'role:Super Admin'])->prefix('super_admin')->name('super_admin.')->group(function () {
    Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('tenants', TenantController::class)->names('tenants');
    Route::put('/tenants/{tenant}/toggle-active', [TenantController::class, 'toggleActive'])->name('tenants.toggleActive');
    Route::get('/payments', [PaymentDashboardController::class, 'index'])->name('payments.dashboard');
});
// Route::post('/stripe/webhook', \Laravel\Cashier\Http\Controllers\WebhookController::class);