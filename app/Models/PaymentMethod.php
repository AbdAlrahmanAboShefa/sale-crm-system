<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'gateway',
        'gateway_id',
        'type',
        'brand',
        'last_four',
        'exp_month',
        'exp_year',
        'is_default',
        'metadata',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'metadata' => 'array',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get display name for the payment method.
     */
    public function getDisplayNameAttribute(): string
    {
        if ($this->brand && $this->last_four) {
            return ucfirst($this->brand) . ' •••• ' . $this->last_four;
        }

        return ucfirst($this->gateway) . ' Account';
    }

    /**
     * Scope to filter by gateway.
     */
    public function scopeByGateway($query, string $gateway)
    {
        return $query->where('gateway', $gateway);
    }

    /**
     * Scope to get default payment method.
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}
