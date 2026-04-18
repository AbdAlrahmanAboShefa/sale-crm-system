<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentIdempotencyKey extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'tenant_id',
        'endpoint',
        'request_hash',
        'response',
        'response_code',
        'locked_until',
        'expires_at',
    ];

    protected $casts = [
        'response' => 'array',
        'locked_until' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Check if this key is currently locked (being processed).
     */
    public function isLocked(): bool
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    /**
     * Check if this key has expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Lock this key for processing (prevents concurrent requests).
     */
    public function lock(int $minutes = 5): void
    {
        $this->update([
            'locked_until' => now()->addMinutes($minutes),
        ]);
    }

    /**
     * Unlock this key after processing.
     */
    public function unlock(): void
    {
        $this->update([
            'locked_until' => null,
        ]);
    }

    /**
     * Store the response for this idempotent request.
     */
    public function storeResponse(array $response, int $code): void
    {
        $this->update([
            'response' => $response,
            'response_code' => $code,
            'locked_until' => null,
        ]);
    }
}
