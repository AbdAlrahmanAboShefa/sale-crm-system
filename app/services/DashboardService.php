<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\Deal;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getTotalPipelineValue(bool $isAdminOrManager = false, ?int $userId = null): float
    {
        $tenantId = auth()->user()?->tenant_id ?? 'super';
        $cacheKey = "pipeline_value_t{$tenantId}_u{$userId}_a{$isAdminOrManager}";

        return Cache::remember($cacheKey, 60, function () use ($isAdminOrManager, $userId) {
            $query = Deal::forTenant()->whereNotIn('stage', ['Won', 'Lost']);

            if (! $isAdminOrManager && $userId) {
                $query->where('user_id', $userId);
            }

            return (float) $query->sum('value');
        });
    }

    public function getWonThisMonth(bool $isAdminOrManager = false, ?int $userId = null): array
    {
        $tenantId = auth()->user()?->tenant_id ?? 'super';
        $cacheKey = "won_month_t{$tenantId}_u{$userId}_a{$isAdminOrManager}";

        return Cache::remember($cacheKey, 60, function () use ($isAdminOrManager, $userId) {
            $query = Deal::forTenant()
                ->where('stage', 'Won')
                ->whereMonth('updated_at', now()->month)
                ->whereYear('updated_at', now()->year);

            if (! $isAdminOrManager && $userId) {
                $query->where('user_id', $userId);
            }

            return [
                'count' => $query->count(),
                'value' => (float) $query->sum('value'),
            ];
        });
    }

    public function getConversionRate(bool $isAdminOrManager = false, ?int $userId = null): float
    {
        $tenantId = auth()->user()?->tenant_id ?? 'super';
        $cacheKey = "conversion_rate_t{$tenantId}_u{$userId}_a{$isAdminOrManager}";

        return Cache::remember($cacheKey, 60, function () use ($isAdminOrManager, $userId) {
            $query = Deal::forTenant();
            $wonQuery = Deal::forTenant()->where('stage', 'Won');

            if (! $isAdminOrManager && $userId) {
                $query->where('user_id', $userId);
                $wonQuery->where('user_id', $userId);
            }

            $total = $query->count();
            $won = $wonQuery->count();

            return $total > 0 ? round(($won / $total) * 100, 1) : 0;
        });
    }

    public function getOverdueActivities(bool $isAdminOrManager = false, ?int $userId = null): int
    {
        $tenantId = auth()->user()?->tenant_id ?? 'super';
        $cacheKey = "overdue_activities_t{$tenantId}_u{$userId}_a{$isAdminOrManager}";

        return Cache::remember($cacheKey, 60, function () use ($isAdminOrManager, $userId) {
            $query = Activity::forTenant()
                ->where('is_done', false)
                ->where('due_date', '<', now());

            if (! $isAdminOrManager && $userId) {
                $query->where('user_id', $userId);
            }

            return $query->count();
        });
    }

    public function getMonthlyRevenue(bool $isAdminOrManager = false, ?int $userId = null): array
    {
        $query = Deal::forTenant()
            ->where('stage', 'Won')
            ->where('updated_at', '>=', now()->subMonths(12));

        if (! $isAdminOrManager && $userId) {
            $query->where('user_id', $userId);
        }

        $deals = $query->get()->groupBy(function ($deal) {
            return $deal->updated_at->format('Y-m');
        });

        $months = [];
        $values = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i)->format('Y-m');
            $label = now()->subMonths($i)->format('M Y');
            $months[] = $label;
            $values[] = $deals->has($month) ? $deals->get($month)->sum('value') : 0;
        }

        return ['labels' => $months, 'values' => $values];
    }

    public function getPipelineFunnel(bool $isAdminOrManager = false, ?int $userId = null): array
    {
        $stages = ['New', 'Contacted', 'Qualified', 'Proposal', 'Negotiation', 'Won'];
        $funnel = ['labels' => [], 'values' => []];

        foreach ($stages as $stage) {
            $query = Deal::forTenant()->where('stage', $stage);

            if (! $isAdminOrManager && $userId) {
                $query->where('user_id', $userId);
            }

            $funnel['labels'][] = $stage;
            $funnel['values'][] = $query->count();
        }

        return $funnel;
    }

    public function getLeaderboard(): array
    {
        $tenantId = auth()->user()?->tenant_id ?? 'super';
        $cacheKey = "leaderboard_t{$tenantId}";

        return Cache::remember($cacheKey, 60, function () {
            return DB::table('users')
                ->join('deals', 'users.id', '=', 'deals.user_id')
                ->where('deals.stage', 'Won')
                ->where('users.tenant_id', auth()->user()->tenant_id)
                ->select(
                    'users.id',
                    'users.name',
                    DB::raw('COUNT(deals.id) as won_deals'),
                    DB::raw('SUM(deals.value) as pipeline_value')
                )
                ->groupBy('users.id', 'users.name')
                ->orderByDesc('pipeline_value')
                ->limit(5)
                ->get()->toArray();
        });
    }

    public function getRecentActivities(bool $isAdminOrManager = false, ?int $userId = null): array
    {
        $query = Activity::forTenant()->with(['deal', 'user', 'contact'])->orderBy('created_at', 'desc');

        if (! $isAdminOrManager && $userId) {
            $query->where('user_id', $userId);
        }

        return $query->limit(5)->get()->map(function ($activity) {
            $title = $activity->type.' with ';
            if ($activity->contact) {
                $title .= $activity->contact->name;
            } elseif ($activity->deal) {
                $title .= $activity->deal->title;
            } else {
                $title = $activity->type.' activity';
            }

            return [
                'id' => $activity->id,
                'type' => strtolower($activity->type),
                'title' => $title,
                'description' => $activity->note,
                'deal_name' => $activity->deal?->title,
                'user_name' => $activity->user?->name,
                'created_at' => $activity->created_at,
                'time_ago' => $activity->created_at->diffForHumans(),
            ];
        })->toArray();
    }

    public function getTopDeals(bool $isAdminOrManager = false, ?int $userId = null): array
    {
        $query = Deal::forTenant()->where('stage', '!=', 'Lost')->orderBy('value', 'desc');

        if (! $isAdminOrManager && $userId) {
            $query->where('user_id', $userId);
        }

        return $query->limit(5)->get()->map(function ($deal) {
            $probability = match ($deal->stage) {
                'Won' => 100,
                'Negotiation' => 75,
                'Proposal' => 50,
                'Qualified' => 25,
                'Contacted' => 15,
                'New' => 10,
                default => 0,
            };

            return [
                'id' => $deal->id,
                'name' => $deal->title,
                'value' => $deal->value,
                'stage' => $deal->stage,
                'probability' => $probability,
                'contact_name' => $deal->contact?->name ?? 'N/A',
            ];
        })->toArray();
    }
}
