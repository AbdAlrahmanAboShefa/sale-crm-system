<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Cashier\Billable;
use Laravel\Cashier\Subscription;
use Laravel\Cashier\PaymentMethod;
use Laravel\Cashier\PaymentTransaction;
use Laravel\Cashier\SubscriptionItem;
use Laravel\Cashier\Cashier;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\Activity;
use App\Models\User;


class Tenant extends Model
{
    use HasFactory;
    use Billable;
    protected $table = 'tenants';
public function getForeignKey()
    {
        return 'tenant_id';
    }
    public const PLAN_LIMITS = [
        'free'       => 3,
        'basic'      => 10,
        'pro'        => 25,
        'enterprise' => PHP_INT_MAX,
    ];

    public const CONTACT_LIMITS = [
        'free'       => 50,
        'basic'      => 500,
        'pro'        => PHP_INT_MAX,
        'enterprise' => PHP_INT_MAX,
    ];

    protected $fillable = [
        'name',
        'subdomain',
        'plan',
        'is_active',
        'trial_ends_at',
        // Cashier (Stripe) columns — required by the Billable trait
        'stripe_id',
        'pm_type',
        'pm_last_four',
    ];

    protected $casts = [
        'is_active'     => 'boolean',
        'trial_ends_at' => 'datetime',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
public function subscriptions(): HasMany
{
    return $this->hasMany(Cashier::$subscriptionModel, 'tenant_id');
}
    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    public function deals(): HasMany
    {
        return $this->hasMany(Deal::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function paymentMethods(): HasMany
    {
        return $this->hasMany(PaymentMethod::class);
    }

    public function paymentTransactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function isOnTrial(): bool
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    public function getUserLimit(): int
    {
        return self::PLAN_LIMITS[$this->plan] ?? self::PLAN_LIMITS['free'];
    }

    public function getUserCount(): int
    {
        return $this->users()->count();
    }

    public function canAddUser(): bool
    {
        return $this->getUserCount() < $this->getUserLimit();
    }

    public function getRemainingUserSlots(): int
    {
        return max(0, $this->getUserLimit() - $this->getUserCount());
    }

    public function getContactLimit(): int
    {
        return self::CONTACT_LIMITS[$this->plan] ?? self::CONTACT_LIMITS['free'];
    }

    public function getContactCount(): int
    {
        return $this->contacts()->count();
    }

    public function canAddContact(): bool
    {
        return $this->getContactCount() < $this->getContactLimit();
    }
}
