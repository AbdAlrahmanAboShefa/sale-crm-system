<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'deal_id',
        'contact_id',
        'user_id',
        'tenant_id',
        'type',
        'note',
        'outcome',
        'due_date',
        'duration_minutes',
        'is_done',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'is_done' => 'boolean',
        'duration_minutes' => 'integer',
    ];

    const TYPES = ['Call', 'Meeting', 'Email', 'Task', 'Demo'];

    const OUTCOMES = ['Positive', 'Neutral', 'Negative'];

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function isOverdue(): bool
    {
        return $this->due_date && ! $this->is_done && $this->due_date->isPast();
    }
}
