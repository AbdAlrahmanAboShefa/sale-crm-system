<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware('set.locale')->group(function () {
    Route::get('/', function () {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('Admin')) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->hasRole('Manager')) {
                return redirect()->route('manager.dashboard');
            } else {
                return redirect()->route('agent.dashboard');
            }
        }

        return redirect()->route('login');
    });
});

Route::middleware(['set.locale', 'guest'])->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login');
});

Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

Route::middleware(['set.locale', 'auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('contacts', ContactController::class)->names('contacts');
    Route::get('/deals/kanban', [DealController::class, 'kanban'])->name('deals.kanban');
    Route::patch('/deals/{deal}/stage', [DealController::class, 'updateStage'])->name('deals.updateStage');
    Route::resource('deals', DealController::class)->names('deals');

    Route::resource('activities', ActivityController::class)->names('activities');
    Route::patch('/activities/{activity}/done', [ActivityController::class, 'markDone'])->name('activities.markDone');

    Route::resource('users', UserController::class)->names('users');
    Route::post('/users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
});

Route::middleware(['set.locale', 'auth', 'role:Manager'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('contacts', ContactController::class)->names('contacts');
    Route::get('/deals/kanban', [DealController::class, 'kanban'])->name('deals.kanban');
    Route::patch('/deals/{deal}/stage', [DealController::class, 'updateStage'])->name('deals.updateStage');
    Route::resource('deals', DealController::class)->names('deals');

    Route::resource('activities', ActivityController::class)->names('activities');
    Route::patch('/activities/{activity}/done', [ActivityController::class, 'markDone'])->name('activities.markDone');
});

Route::middleware(['set.locale', 'auth', 'role:Agent'])->prefix('agent')->name('agent.')->group(function () {
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
});
