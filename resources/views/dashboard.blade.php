@extends('layouts.app')
@section('content')
    <div class="space-y-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-100 tracking-tight">
                    {{ __('messages.dashboard.welcome') }}, <span class="dark-gradient-text">{{ auth()->user()->name }}</span>
                </h1>
                <p class="text-slate-400 mt-1 flex items-center gap-2">
                    <i class="fas fa-sparkles text-cyan-400"></i>
                    {{ __('messages.dashboard.today_summary') ?? "Here's what's happening with your sales today." }}
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route($routePrefix . '.contacts.create') }}" class="dark-btn dark-btn-secondary">
                    <i class="fas fa-user-plus"></i>
                    {{ __('messages.contacts.new_contact') }}
                </a>
                <a href="{{ route($routePrefix . '.deals.create') }}" class="dark-btn dark-btn-primary">
                    <i class="fas fa-plus"></i>
                    {{ __('messages.deals.new_deal') }}
                </a>
                <a href="{{ route($routePrefix . '.activities.create') }}" class="dark-btn dark-btn-secondary">
                    <i class="fas fa-clipboard-list"></i>
                    {{ __('messages.activities.log_activity') }}
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="dark-stats-card" style="--card-accent: #06b6d4; --card-accent-secondary: #0891b2;">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-400">{{ __('messages.deals.pipeline_value') }}</p>
                        <p class="text-3xl font-bold text-slate-100 mt-2 tracking-tight">${{ number_format($kpis['pipelineValue'], 0) }}</p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="inline-flex items-center gap-1 text-xs font-medium text-emerald-400">
                                <i class="fas fa-arrow-up"></i>
                                <span>12%</span>
                            </span>
                            <span class="text-xs text-slate-500">{{ __('messages.dashboard.active_deals_value') ?? 'Active deals value' }}</span>
                        </div>
                    </div>
                    <div class="dark-stats-icon" style="--icon-bg: rgba(6, 182, 212, 0.15); --icon-color: #06b6d4; --icon-shadow: rgba(6, 182, 212, 0.3);">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-cyan-500/20 to-transparent rounded-b-xl"></div>
            </div>

            <div class="dark-stats-card" style="--card-accent: #10b981; --card-accent-secondary: #059669;">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-400">{{ __('messages.dashboard.won_this_month') ?? 'Won This Month' }}</p>
                        <p class="text-3xl font-bold text-slate-100 mt-2 tracking-tight">${{ number_format($kpis['wonThisMonth']['value'] ?? 0, 0) }}</p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="inline-flex items-center gap-1 text-xs font-medium text-emerald-400">
                                <i class="fas fa-trophy"></i>
                                {{ $kpis['wonThisMonth']['count'] ?? 0 }} {{ __('messages.dashboard.deals_closed') ?? 'deals closed' }}
                            </span>
                        </div>
                    </div>
                    <div class="dark-stats-icon" style="--icon-bg: rgba(16, 185, 129, 0.15); --icon-color: #10b981; --icon-shadow: rgba(16, 185, 129, 0.3);">
                        <i class="fas fa-trophy"></i>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500/20 to-transparent rounded-b-xl"></div>
            </div>

            <div class="dark-stats-card" style="--card-accent: #8b5cf6; --card-accent-secondary: #7c3aed;">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-400">{{ __('messages.dashboard.conversion_rate') ?? 'Conversion Rate' }}</p>
                        <p class="text-3xl font-bold text-slate-100 mt-2 tracking-tight">{{ $kpis['conversionRate'] ?? 0 }}%</p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="inline-flex items-center gap-1 text-xs font-medium text-violet-400">
                                <i class="fas fa-bullseye"></i>
                                {{ __('messages.dashboard.leads_to_won') ?? 'Leads to won deals' }}
                            </span>
                        </div>
                    </div>
                    <div class="dark-stats-icon" style="--icon-bg: rgba(139, 92, 246, 0.15); --icon-color: #8b5cf6; --icon-shadow: rgba(139, 92, 246, 0.3);">
                        <i class="fas fa-bullseye"></i>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-violet-500/20 to-transparent rounded-b-xl"></div>
            </div>

            <div class="dark-stats-card" style="--card-accent: #f43f5e; --card-accent-secondary: #e11d48;">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-400">{{ __('messages.dashboard.overdue_activities') ?? 'Overdue Activities' }}</p>
                        <p class="text-3xl font-bold text-slate-100 mt-2 tracking-tight">{{ $kpis['overdueActivities'] ?? 0 }}</p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="inline-flex items-center gap-1 text-xs font-medium text-rose-400">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ __('messages.dashboard.need_attention') ?? 'Need attention' }}
                            </span>
                        </div>
                    </div>
                    <div class="dark-stats-icon" style="--icon-bg: rgba(244, 63, 94, 0.15); --icon-color: #f43f5e; --icon-shadow: rgba(244, 63, 94, 0.3);">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-rose-500/20 to-transparent rounded-b-xl"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 dark-chart-container">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-100">{{ __('messages.dashboard.monthly_revenue') ?? 'Monthly Revenue' }}</h2>
                        <p class="text-xs text-slate-500 mt-1">Performance overview</p>
                    </div>
                    <select class="dark-select text-sm">
                        <option>{{ __('messages.dashboard.last_12_months') ?? 'Last 12 months' }}</option>
                    </select>
                </div>
                <div class="h-72">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <div class="dark-chart-container">
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-slate-100">{{ __('messages.dashboard.pipeline_funnel') ?? 'Pipeline Funnel' }}</h2>
                    <p class="text-xs text-slate-500 mt-1">Deal stages distribution</p>
                </div>
                <div class="h-72">
                    <canvas id="funnelChart"></canvas>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="dark-card p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-100">{{ __('messages.dashboard.recent_activities') }}</h2>
                        <p class="text-xs text-slate-500 mt-1">Latest interactions</p>
                    </div>
                    <a href="{{ route($routePrefix . '.activities.index') }}" class="text-sm text-cyan-400 hover:text-cyan-300 transition-colors flex items-center gap-1">
                        {{ __('messages.common.view_all') }}
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
                <div class="space-y-3">
                    @forelse($recentActivities as $activity)
                    <div class="dark-activity-item group">
                        <div class="dark-activity-icon
                            @if($activity['type'] === 'call') bg-cyan-500/15 text-cyan-400
                            @elseif($activity['type'] === 'email') bg-violet-500/15 text-violet-400
                            @elseif($activity['type'] === 'meeting') bg-emerald-500/15 text-emerald-400
                            @else bg-slate-500/15 text-slate-400
                            @endif">
                            @if($activity['type'] === 'call')
                            <i class="fas fa-phone"></i>
                            @elseif($activity['type'] === 'email')
                            <i class="fas fa-envelope"></i>
                            @elseif($activity['type'] === 'meeting')
                            <i class="fas fa-users"></i>
                            @else
                            <i class="fas fa-clipboard-list"></i>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-200 group-hover:text-white transition-colors truncate">{{ $activity['title'] }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">
                                @if($activity['deal_name'])
                                {{ $activity['deal_name'] }}
                                @else
                                {{ $activity['description'] }}
                                @endif
                            </p>
                        </div>
                        <span class="text-xs text-slate-600 whitespace-nowrap">{{ $activity['time_ago'] }}</span>
                    </div>
                    @empty
                    <div class="dark-empty-state">
                        <div class="dark-empty-icon">
                            <i class="fas fa-inbox"></i>
                        </div>
                        <h3 class="text-lg font-medium text-slate-300 mb-1">No activities yet</h3>
                        <p class="text-sm text-slate-500">Start logging activities to see them here</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="dark-card p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-100">{{ __('messages.dashboard.top_deals') ?? 'Top Deals' }}</h2>
                        <p class="text-xs text-slate-500 mt-1">Highest value opportunities</p>
                    </div>
                    <a href="{{ route($routePrefix . '.deals.index') }}" class="text-sm text-cyan-400 hover:text-cyan-300 transition-colors flex items-center gap-1">
                        {{ __('messages.common.view_all') }}
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
                <div class="overflow-hidden">
                    <table class="dark-table">
                        <thead>
                            <tr>
                                <th class="!border-t-transparent">{{ __('messages.deals.deal') }}</th>
                                <th class="!border-t-transparent">{{ __('messages.deals.stage') }}</th>
                                <th class="!border-t-transparent text-right">{{ __('messages.deals.value') }}</th>
                                <th class="!border-t-transparent text-right">{{ __('messages.deals.probability') ?? 'Prob.' }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topDeals as $deal)
                            <tr>
                                <td>
                                    <a href="{{ route($routePrefix . '.deals.show', $deal['id']) }}" class="text-sm font-medium text-slate-200 hover:text-cyan-400 transition-colors">
                                        {{ $deal['name'] }}
                                    </a>
                                    <p class="text-xs text-slate-500 mt-0.5">{{ $deal['contact_name'] }}</p>
                                </td>
                                <td>
                                    @php
                                    $stageColors = [
                                        'New' => 'dark-badge-gray',
                                        'Contacted' => 'dark-badge-cyan',
                                        'Qualified' => 'dark-badge-violet',
                                        'Proposal' => 'dark-badge-amber',
                                        'Negotiation' => 'dark-badge-rose',
                                        'Won' => 'dark-badge-emerald',
                                        'Lost' => 'dark-badge-gray',
                                    ];
                                    @endphp
                                    <span class="dark-badge {{ $stageColors[$deal['stage']] ?? 'dark-badge-gray' }}">
                                        {{ __("messages.deals.stages." . strtolower($deal['stage'])) }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <span class="text-sm font-semibold text-emerald-400">${{ number_format($deal['value'], 0) }}</span>
                                </td>
                                <td class="text-right">
                                    <span class="text-sm text-slate-400">{{ $deal['probability'] }}%</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-12">
                                    <div class="dark-empty-icon mx-auto mb-4">
                                        <i class="fas fa-hand-holding-dollar"></i>
                                    </div>
                                    <p class="text-slate-500">{{ __('messages.deals.no_deals') ?? __('messages.common.no_data') }}</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if($isAdminOrManager && !empty($leaderboard))
        <div class="dark-card p-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500/20 to-orange-500/20 flex items-center justify-center">
                        <i class="fas fa-trophy text-amber-400"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-slate-100">{{ __('messages.dashboard.top_agents') ?? 'Top Agents Leaderboard' }}</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Performance rankings</p>
                    </div>
                </div>
            </div>
            <div class="overflow-hidden">
                <table class="dark-table">
                    <thead>
                        <tr>
                            <th class="!border-t-transparent">Rank</th>
                            <th class="!border-t-transparent">{{ __('messages.roles.agent') }}</th>
                            <th class="!border-t-transparent">{{ __('messages.dashboard.deals_won') ?? 'Deals Won' }}</th>
                            <th class="!border-t-transparent text-right">{{ __('messages.dashboard.revenue') ?? 'Revenue' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaderboard as $index => $agent)
                        <tr>
                            <td>
                                @if($index === 0)
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-gradient-to-br from-amber-500 to-amber-600 text-amber-950 text-xs font-bold shadow-lg shadow-amber-500/20">
                                    <i class="fas fa-crown"></i>
                                </span>
                                @elseif($index === 1)
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-gradient-to-br from-slate-400 to-slate-500 text-slate-900 text-xs font-bold">
                                    <i class="fas fa-medal"></i>
                                </span>
                                @elseif($index === 2)
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-gradient-to-br from-orange-400 to-orange-500 text-orange-950 text-xs font-bold">
                                    <i class="fas fa-medal"></i>
                                </span>
                                @else
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-slate-700 text-slate-300 text-xs font-semibold">
                                    {{ $index + 1 }}
                                </span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="dark-avatar dark-avatar-sm">
                                        {{ strtoupper(substr($agent->name, 0, 1)) }}
                                    </div>
                                    <span class="text-sm font-medium text-slate-200">{{ $agent->name }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="text-sm text-slate-400">{{ $agent->won_deals }} {{ __('messages.deals.title') }}</span>
                            </td>
                            <td class="text-right">
                                <span class="text-sm font-semibold text-emerald-400">${{ number_format($agent->pipeline_value, 0) }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const revenueLabels = @json($monthlyRevenue['labels'] ?? []);
            const revenueData = @json($monthlyRevenue['values'] ?? []);

            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            
            const gradient = revenueCtx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(6, 182, 212, 0.8)');
            gradient.addColorStop(1, 'rgba(6, 182, 212, 0.1)');

            new Chart(revenueCtx, {
                type: 'bar',
                data: {
                    labels: revenueLabels,
                    datasets: [{
                        label: 'Revenue',
                        data: revenueData,
                        backgroundColor: gradient,
                        borderColor: 'rgb(6, 182, 212)',
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false,
                        fill: true,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleColor: '#f1f5f9',
                            bodyColor: '#94a3b8',
                            padding: 14,
                            cornerRadius: 10,
                            borderColor: '#374151',
                            borderWidth: 1,
                            callbacks: {
                                label: function(context) {
                                    return '$' + context.raw.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(55, 65, 81, 0.5)', drawBorder: false },
                            ticks: { 
                                color: '#64748b',
                                font: { family: "'DM Sans', sans-serif" },
                                callback: function(value) { return '$' + value.toLocaleString(); }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#64748b', font: { family: "'DM Sans', sans-serif" } }
                        }
                    }
                }
            });

            const funnelLabels = @json($pipelineFunnel['labels'] ?? []);
            const funnelData = @json($pipelineFunnel['values'] ?? []);

            const funnelCtx = document.getElementById('funnelChart').getContext('2d');
            new Chart(funnelCtx, {
                type: 'bar',
                data: {
                    labels: funnelLabels,
                    datasets: [{
                        data: funnelData,
                        backgroundColor: [
                            'rgba(6, 182, 212, 0.7)',
                            'rgba(139, 92, 246, 0.7)',
                            'rgba(236, 72, 153, 0.7)',
                            'rgba(245, 158, 11, 0.7)',
                            'rgba(16, 185, 129, 0.7)',
                        ],
                        borderRadius: 8,
                        borderSkipped: false,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleColor: '#f1f5f9',
                            bodyColor: '#94a3b8',
                            padding: 14,
                            cornerRadius: 10,
                            borderColor: '#374151',
                            borderWidth: 1,
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: { color: 'rgba(55, 65, 81, 0.5)', drawBorder: false },
                            ticks: { color: '#64748b', font: { family: "'DM Sans', sans-serif" } }
                        },
                        y: {
                            grid: { display: false },
                            ticks: { color: '#94a3b8', font: { family: "'DM Sans', sans-serif" } }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
@endsection
