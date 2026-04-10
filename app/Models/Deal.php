<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deal extends Model
{
    use HasFactory, SoftDeletes;

    protected static function booted(): void
    {
        static::addGlobalScope('tenant', function ($query) {
            $tenantId = auth()->user()?->tenant_id;
            if ($tenantId) {
                $query->where('tenant_id', $tenantId);
            }
        });
    }

    protected $fillable = [
        'contact_id',
        'user_id',
        'tenant_id',
        'title',
        'value',
        'currency',
        'stage',
        'probability',
        'expected_close_date',
        'lost_reason',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'probability' => 'integer',
        'expected_close_date' => 'date',
    ];

    const STAGES = ['New', 'Contacted', 'Qualified', 'Proposal', 'Negotiation', 'Won', 'Lost'];

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeOwnedBy($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function daysInStage(): int
    {
        return now()->diffInDays($this->updated_at);
    }
}
