<?php

namespace App\Services;

use App\Models\Deal;

class DealService
{
    public function getKanbanData(bool $isAdmin = false, ?int $userId = null): array
    {
        $query = Deal::with(['contact', 'user']);

        if (! $isAdmin && $userId) {
            $query->where('user_id', $userId);
        }

        $deals = $query->orderBy('updated_at', 'desc')->get();

        $kanban = [];

        foreach (Deal::STAGES as $stage) {
            $stageDeals = $deals->where('stage', $stage)->values();
            $kanban[$stage] = [
                'deals' => $stageDeals,
                'count' => $stageDeals->count(),
            ];
        }

        return $kanban;
    }

    public function getForecastValue(bool $isAdmin = false, ?int $userId = null): float
    {
        $query = Deal::query()->whereNotIn('stage', ['Won', 'Lost']);

        if (! $isAdmin && $userId) {
            $query->where('user_id', $userId);
        }

        $deals = $query->get();

        return $deals->sum(function ($deal) {
            return $deal->value * ($deal->probability / 100);
        });
    }

    public function getFilteredDeals(array $filters, bool $isAdmin = false, ?int $userId = null): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = Deal::with(['contact', 'user']);

        if (! $isAdmin && $userId) {
            $query->where('user_id', $userId);
        }

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('contact', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if (! empty($filters['stage'])) {
            $query->where('stage', $filters['stage']);
        }

        if (! empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (! empty($filters['date_range'])) {
            [$start, $end] = explode(' - ', $filters['date_range']);
            $query->whereBetween('expected_close_date', [
                \Carbon\Carbon::parse($start)->startOfDay(),
                \Carbon\Carbon::parse($end)->endOfDay(),
            ]);
        }

        return $query->orderBy('created_at', 'desc')->paginate(15);
    }
}
