<?php

namespace App\Providers;

use App\Models\Tenant;
use App\Services\Payment\IdempotencyService;
use App\Services\Payment\PaymentManager;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(PaymentManager::class, function ($app) {
            return new PaymentManager(config('services.payment'));
        });

        $this->app->singleton(IdempotencyService::class);

        if ($this->app->isLocal()) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
        Cashier::useCustomerModel(Tenant::class);
        // Cashier::useSubscriptionModel(Subscription::class);
    }
}
