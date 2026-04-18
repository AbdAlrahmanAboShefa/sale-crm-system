@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-100 tracking-tight">{{ __('messages.app.dashboard') }}</h1>
            <p class="text-sm text-slate-500 mt-1 flex items-center gap-2">
                <i class="fas fa-calendar-day text-cyan-400"></i>
                {{ now()->format('l, F j, Y') }}
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="dark-stats-card" style="--card-accent: #3b82f6; --card-accent-secondary: #2563eb;">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-400">{{ __('messages.dashboard.total_deals') }}</p>
                    <p class="text-3xl font-bold text-slate-100 mt-2 tracking-tight">{{ $stats['totalDeals'] }}</p>
                    <p class="text-xs text-slate-500 mt-2">All-time deals</p>
                </div>
                <div class="dark-stats-icon" style="--icon-bg: rgba(59, 130, 246, 0.15); --icon-color: #3b82f6; --icon-shadow: rgba(59, 130, 246, 0.3);">
                    <i class="fas fa-hand-holding-dollar"></i>
                </div>
            </div>
        </div>

        <div class="dark-stats-card" style="--card-accent: #10b981; --card-accent-secondary: #059669;">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-400">{{ __('messages.deals.active_deals') }}</p>
                    <p class="text-3xl font-bold text-slate-100 mt-2 tracking-tight">{{ $stats['activeDeals'] }}</p>
                    <p class="text-xs text-slate-500 mt-2">In progress</p>
                </div>
                <div class="dark-stats-icon" style="--icon-bg: rgba(16, 185, 129, 0.15); --icon-color: #10b981; --icon-shadow: rgba(16, 185, 129, 0.3);">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>

        <div class="dark-stats-card" style="--card-accent: #8b5cf6; --card-accent-secondary: #7c3aed;">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-400">{{ __('messages.deals.pipeline_value') }}</p>
                    <p class="text-3xl font-bold text-slate-100 mt-2 tracking-tight">${{ number_format($stats['totalValue'], 0) }}</p>
                    <p class="text-xs text-slate-500 mt-2">Total value</p>
                </div>
                <div class="dark-stats-icon" style="--icon-bg: rgba(139, 92, 246, 0.15); --icon-color: #8b5cf6; --icon-shadow: rgba(139, 92, 246, 0.3);">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
        </div>

        <div class="dark-stats-card" style="--card-accent: #f59e0b; --card-accent-secondary: #d97706;">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-400">{{ __('messages.deals.won_deals') }}</p>
                    <p class="text-3xl font-bold text-slate-100 mt-2 tracking-tight">{{ $stats['wonDeals'] }}</p>
                    <p class="text-xs text-slate-500 mt-2">Successfully closed</p>
                </div>
                <div class="dark-stats-icon" style="--icon-bg: rgba(245, 158, 11, 0.15); --icon-color: #f59e0b; --icon-shadow: rgba(245, 158, 11, 0.3);">
                    <i class="fas fa-trophy"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 dark-card p-6">
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-slate-100">{{ __('messages.dashboard.funnel') }}</h3>
                <p class="text-xs text-slate-500 mt-1">Pipeline progression</p>
            </div>
            <div class="space-y-5">
                @foreach(['New', 'Contacted', 'Qualified', 'Proposal', 'Negotiation', 'Won'] as $stage)
                @php
                    $stageData = $stageStats[$stage] ?? ['count' => 0, 'value' => 0];
                    $maxValue = max($stageStats['Proposal']['value'] ?? 1, $stageStats['Negotiation']['value'] ?? 1, $stageStats['Qualified']['value'] ?? 1);
                    $percentage = $maxValue > 0 ? ($stageData['value'] / $maxValue) * 100 : 0;
                    $colors = [
                        'New' => 'from-blue-500 to-blue-600',
                        'Contacted' => 'from-cyan-500 to-cyan-600',
                        'Qualified' => 'from-teal-500 to-teal-600',
                        'Proposal' => 'from-yellow-500 to-yellow-600',
                        'Negotiation' => 'from-orange-500 to-orange-600',
                        'Won' => 'from-emerald-500 to-emerald-600',
                    ];
                @endphp
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-slate-300">{{ $stage }}</span>
                        <div class="flex items-center gap-3">
                            <span class="text-xs text-slate-500">{{ $stageData['count'] }} deals</span>
                            <span class="text-sm font-semibold text-slate-200">${{ number_format($stageData['value'], 0) }}</span>
                        </div>
                    </div>
                    <div class="dark-progress-bar">
                        <div class="dark-progress-fill bg-gradient-to-r {{ $colors[$stage] }}" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="dark-chart-container">
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-slate-100">{{ __('messages.dashboard.deals_by_stage') }}</h3>
                <p class="text-xs text-slate-500 mt-1">Distribution overview</p>
            </div>
            <div class="flex items-center justify-center">
                <div class="relative">
                    <canvas id="stageChart" width="200" height="200"></canvas>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3 mt-6">
                @foreach(['New', 'Contacted', 'Qualified', 'Proposal', 'Negotiation', 'Won', 'Lost'] as $stage)
                @php
                    $colors = [
                        'New' => 'bg-slate-500',
                        'Contacted' => 'bg-blue-500',
                        'Qualified' => 'bg-cyan-500',
                        'Proposal' => 'bg-yellow-500',
                        'Negotiation' => 'bg-orange-500',
                        'Won' => 'bg-emerald-500',
                        'Lost' => 'bg-red-500',
                    ];
                @endphp
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full {{ $colors[$stage] }}"></div>
                    <span class="text-xs text-slate-400">{{ $stage }}</span>
                    <span class="text-xs text-slate-500 ml-auto">{{ $stageStats[$stage]['count'] ?? 0 }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="dark-card p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-slate-100">{{ __('messages.dashboard.recent_activities') }}</h3>
                    <p class="text-xs text-slate-500 mt-1">Latest interactions</p>
                </div>
                <a href="{{ route('manager.activities.index') }}" class="text-sm text-cyan-400 hover:text-cyan-300 transition-colors flex items-center gap-1">
                    {{ __('messages.common.view_all') }}
                    <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
            <div class="space-y-4">
                @forelse($recentActivities as $activity)
                <div class="dark-activity-item group">
                    <div class="dark-activity-icon
                        @if($activity->type === 'Call') bg-blue-500/15 text-blue-400
                        @elseif($activity->type === 'Meeting') bg-violet-500/15 text-violet-400
                        @elseif($activity->type === 'Email') bg-emerald-500/15 text-emerald-400
                        @elseif($activity->type === 'Demo') bg-orange-500/15 text-orange-400
                        @else bg-slate-500/15 text-slate-400 @endif">
                        <i class="fas fa-{{ $activity->type === 'Call' ? 'phone' : ($activity->type === 'Meeting' ? 'users' : ($activity->type === 'Email' ? 'envelope' : ($activity->type === 'Demo' ? 'desktop' : 'tasks'))) }}"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-slate-200 group-hover:text-white transition-colors truncate">
                            @if($activity->contact)
                                {{ $activity->contact->name }}
                            @elseif($activity->deal)
                                {{ $activity->deal->title }}
                            @else
                                {{ $activity->type }}
                            @endif
                        </p>
                        <p class="text-xs text-slate-500 truncate mt-0.5">{{ $activity->note ?: __('messages.activities.no_notes') }}</p>
                        <div class="flex items-center gap-3 mt-2">
                            <span class="text-xs text-slate-600">{{ $activity->created_at->diffForHumans() }}</span>
                            @if($activity->is_done)
                                <span class="inline-flex items-center gap-1 text-xs text-emerald-400">
                                    <i class="fas fa-check-circle"></i> {{ __('messages.activities.completed') }}
                                </span>
                            @elseif($activity->isOverdue())
                                <span class="inline-flex items-center gap-1 text-xs text-rose-400">
                                    <i class="fas fa-exclamation-circle"></i> {{ __('messages.activities.overdue') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="dark-empty-state">
                    <div class="dark-empty-icon"><i class="fas fa-inbox"></i></div>
                    <p class="text-slate-500">{{ __('messages.activities.no_activities') }}</p>
                </div>
                @endforelse
            </div>
        </div>

        <div class="dark-card p-6">
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-slate-100">{{ __('messages.dashboard.monthly_performance') }}</h3>
                <p class="text-xs text-slate-500 mt-1">Deal closings by month</p>
            </div>
            <div class="h-64 flex items-end justify-around gap-3">
                @for($i = 1; $i <= 12; $i++)
                @php
                    $monthData = $monthlyDeals[$i] ?? null;
                    $count = $monthData->count ?? 0;
                    $maxCount = $monthlyDeals->max('count') ?: 1;
                    $height = ($count / $maxCount) * 100;
                @endphp
                <div class="flex flex-col items-center flex-1 group">
                    <div class="w-full rounded-t-lg bg-gradient-to-t from-cyan-500 to-cyan-400/50 transition-all duration-500 hover:from-cyan-400 hover:to-cyan-300 relative" style="height: {{ max($height, 3) }}%">
                        <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-slate-200 px-2 py-1 rounded text-xs font-medium opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                            {{ $count }} deals
                        </div>
                    </div>
                    <span class="text-xs text-slate-500 mt-3">{{ date('M', mktime(0, 0, 0, $i, 1)) }}</span>
                </div>
                @endfor
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('stageChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode(array_keys($stageStats)) !!},
                datasets: [{
                    data: {!! json_encode(array_column($stageStats, 'count')) !!},
                    backgroundColor: [
                        'rgba(100, 116, 139, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(6, 182, 212, 0.8)',
                        'rgba(234, 179, 8, 0.8)',
                        'rgba(249, 115, 22, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(239, 68, 68, 0.8)'
                    ],
                    borderColor: '#151c2c',
                    borderWidth: 3,
                    hoverOffset: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleColor: '#f1f5f9',
                        bodyColor: '#94a3b8',
                        padding: 12,
                        cornerRadius: 10,
                        borderColor: '#374151',
                        borderWidth: 1,
                    }
                },
                cutout: '70%',
                animation: { animateRotate: true, animateScale: true }
            }
        });
    }
});
</script>
@endpush
@endsection
