<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\Agent\DashboardController as AgentDashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Manager\DashboardController as ManagerDashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;

Route::get('/', fn() => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

Route::post('/logout', [LoginController::class, 'destroy'])->name('logout')->middleware('auth');

Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('contacts', ContactController::class)->names('contacts');
    Route::resource('deals', DealController::class)->names('deals');
    Route::get('/deals/kanban', [DealController::class, 'kanban'])->name('deals.kanban');
    Route::patch('/deals/{deal}/stage', [DealController::class, 'updateStage'])->name('deals.updateStage');
    Route::resource('activities', ActivityController::class)->names('activities');
    Route::patch('/activities/{activity}/done', [ActivityController::class, 'markDone'])->name('activities.markDone');
});

Route::middleware(['auth', 'role:Manager'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('contacts', ContactController::class)->names('contacts');
    Route::resource('deals', DealController::class)->names('deals');
    Route::get('/deals/kanban', [DealController::class, 'kanban'])->name('deals.kanban');
    Route::patch('/deals/{deal}/stage', [DealController::class, 'updateStage'])->name('deals.updateStage');
    Route::resource('activities', ActivityController::class)->names('activities');
    Route::patch('/activities/{activity}/done', [ActivityController::class, 'markDone'])->name('activities.markDone');
});

Route::middleware(['auth', 'role:Agent'])->prefix('agent')->name('agent.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('contacts', ContactController::class)->names('contacts');
    Route::resource('deals', DealController::class)->names('deals');
    Route::get('/deals/kanban', [DealController::class, 'kanban'])->name('deals.kanban');
    Route::patch('/deals/{deal}/stage', [DealController::class, 'updateStage'])->name('deals.updateStage');
    Route::resource('activities', ActivityController::class)->names('activities');
    Route::patch('/activities/{activity}/done', [ActivityController::class, 'markDone'])->name('activities.markDone');
});

Route::middleware('auth')->group(function () {
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
});

require __DIR__.'/auth.php';
