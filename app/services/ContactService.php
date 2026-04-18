<?php

namespace App\Services;

use App\Models\Contact;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class ContactService
{
    public function getFilteredContacts(array $filters, ?int $userId = null, bool $isAdmin = false): LengthAwarePaginator
    {
        $query = Contact::forTenant();

        if (! $isAdmin && $userId) {
            $query->where('user_id', $userId);
        }

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('company', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['source'])) {
            $query->where('source', $filters['source']);
        }

        if (! empty($filters['date_range'])) {
            [$start, $end] = explode(' - ', $filters['date_range']);
            $query->whereBetween('created_at', [
                \Carbon\Carbon::parse($start)->startOfDay(),
                \Carbon\Carbon::parse($end)->endOfDay(),
            ]);
        }

        return $query->orderBy('created_at', 'desc')->paginate(15);
    }
}
