<?php

namespace App\Traits;

trait BelongsToTenant
{
    public function scopeForTenant($query)
    {
        $tenantId = auth()->user()?->tenant_id;
        if ($tenantId) {
            return $query->where('tenant_id', $tenantId);
        }

        return $query;
    }
}
