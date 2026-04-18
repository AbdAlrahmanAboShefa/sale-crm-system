@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500/20 to-pink-500/20 flex items-center justify-center">
                <i class="fas fa-credit-card text-purple-400 text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-white">{{ __('messages.payments.dashboard_title', ['default' => 'Payment Dashboard']) }}</h1>
                <p class="text-slate-400 text-sm mt-1">{{ __('messages.payments.dashboard_subtitle', ['default' => 'Monitor revenue, transactions, and subscription health']) }}</p>
            </div>
        </div>

        {{-- Date Range Selector --}}
        <div class="flex items-center gap-2">
            <a href="{{ route('super_admin.payments.dashboard', ['days' => 7]) }}" class="px-3 py-1.5 rounded-lg text-sm transition-colors {{ $days === 7 ? 'bg-purple-500/20 text-purple-400' : 'text-slate-400 hover:bg-white/5' }}">
                7d
            </a>
            <a href="{{ route('super_admin.payments.dashboard', ['days' => 30]) }}" class="px-3 py-1.5 rounded-lg text-sm transition-colors {{ $days === 30 ? 'bg-purple-500/20 text-purple-400' : 'text-slate-400 hover:bg-white/5' }}">
                30d
            </a>
            <a href="{{ route('super_admin.payments.dashboard', ['days' => 90]) }}" class="px-3 py-1.5 rounded-lg text-sm transition-colors {{ $days === 90 ? 'bg-purple-500/20 text-purple-400' : 'text-slate-400 hover:bg-white/5' }}">
                90d
            </a>
        </div>
    </div>

    {{-- Primary Revenue Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Total Revenue --}}
        <div class="dark-stats-card" style="--card-accent: #10b981; --card-accent-secondary: #059669; --icon-bg: rgba(16,185,129,0.2); --icon-bg-secondary: rgba(16,185,129,0.05); --icon-color: #34d399; --icon-shadow: rgba(16,185,129,0.4);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-400">{{ __('messages.payments.total_revenue', ['default' => 'Total Revenue']) }}</p>
                    <h3 class="text-3xl font-bold mt-1 text-white">${{ number_format($totalRevenue, 2) }}</h3>
                    <p class="text-xs text-slate-500 mt-1">{{ __('messages.payments.last_days', ['default' => "Last {$days} days"]) }}</p>
                </div>
                <div class="dark-stats-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
        </div>

        {{-- Successful Transactions --}}
        <div class="dark-stats-card" style="--card-accent: #3b82f6; --card-accent-secondary: #2563eb; --icon-bg: rgba(59,130,246,0.2); --icon-bg-secondary: rgba(59,130,246,0.05); --icon-color: #60a5fa; --icon-shadow: rgba(59,130,246,0.4);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-400">{{ __('messages.payments.successful_transactions', ['default' => 'Successful Payments']) }}</p>
                    <h3 class="text-3xl font-bold mt-1 text-white">{{ number_format($successfulTransactions) }}</h3>
                    <p class="text-xs text-slate-500 mt-1">{{ $successRate }}% {{ __('messages.payments.success_rate', ['default' => 'success rate']) }}</p>
                </div>
                <div class="dark-stats-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>

        {{-- Active Subscriptions --}}
        <div class="dark-stats-card" style="--card-accent: #8b5cf6; --card-accent-secondary: #7c3aed; --icon-bg: rgba(139,92,246,0.2); --icon-bg-secondary: rgba(139,92,246,0.05); --icon-color: #a78bfa; --icon-shadow: rgba(139,92,246,0.4);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-400">{{ __('messages.payments.active_subscriptions', ['default' => 'Active Subscriptions']) }}</p>
                    <h3 class="text-3xl font-bold mt-1 text-white">{{ $activeSubscriptions }}</h3>
                    <p class="text-xs text-slate-500 mt-1">{{ $freeTenants }} {{ __('messages.payments.free_tenants', ['default' => 'on free plan']) }}</p>
                </div>
                <div class="dark-stats-icon">
                    <i class="fas fa-crown"></i>
                </div>
            </div>
        </div>

        {{-- Failed Transactions --}}
        <div class="dark-stats-card" style="--card-accent: #ef4444; --card-accent-secondary: #dc2626; --icon-bg: rgba(239,68,68,0.2); --icon-bg-secondary: rgba(239,68,68,0.05); --icon-color: #f87171; --icon-shadow: rgba(239,68,68,0.4);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-400">{{ __('messages.payments.failed_transactions', ['default' => 'Failed Payments']) }}</p>
                    <h3 class="text-3xl font-bold mt-1 text-white">{{ $failedTransactions }}</h3>
                    <p class="text-xs text-slate-500 mt-1">{{ $pendingTransactions }} {{ __('messages.payments.pending', ['default' => 'pending']) }}</p>
                </div>
                <div class="dark-stats-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Secondary Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Refunded Amount --}}
        <div class="dark-stats-card" style="--card-accent: #f59e0b; --card-accent-secondary: #d97706; --icon-bg: rgba(245,158,11,0.2); --icon-bg-secondary: rgba(245,158,11,0.05); --icon-color: #fbbf24; --icon-shadow: rgba(245,158,11,0.4);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-400">{{ __('messages.payments.refunded_amount', ['default' => 'Refunded Amount']) }}</p>
                    <h3 class="text-2xl font-bold mt-1 text-white">${{ number_format($refundedAmount, 2) }}</h3>
                </div>
                <div class="dark-stats-icon">
                    <i class="fas fa-undo"></i>
                </div>
            </div>
        </div>

        {{-- Churned This Month --}}
        <div class="dark-stats-card" style="--card-accent: #ec4899; --card-accent-secondary: #db2777; --icon-bg: rgba(236,72,153,0.2); --icon-bg-secondary: rgba(236,72,153,0.05); --icon-color: #f472b6; --icon-shadow: rgba(236,72,153,0.4);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-400">{{ __('messages.payments.churned', ['default' => 'Churned This Month']) }}</p>
                    <h3 class="text-2xl font-bold mt-1 text-white">{{ $churnedThisMonth }}</h3>
                </div>
                <div class="dark-stats-icon">
                    <i class="fas fa-user-minus"></i>
                </div>
            </div>
        </div>

        {{-- Total Transactions --}}
        <div class="dark-stats-card" style="--card-accent: #06b6d4; --card-accent-secondary: #0891b2; --icon-bg: rgba(6,182,212,0.2); --icon-bg-secondary: rgba(6,182,212,0.05); --icon-color: #22d3ee; --icon-shadow: rgba(6,182,212,0.4);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-400">{{ __('messages.payments.total_transactions', ['default' => 'Total Transactions']) }}</p>
                    <h3 class="text-2xl font-bold mt-1 text-white">{{ number_format($totalTransactions) }}</h3>
                </div>
                <div class="dark-stats-icon">
                    <i class="fas fa-exchange-alt"></i>
                </div>
            </div>
        </div>

        {{-- Idempotency Cache Hits --}}
        <div class="dark-stats-card" style="--card-accent: #14b8a6; --card-accent-secondary: #0d9488; --icon-bg: rgba(20,184,166,0.2); --icon-bg-secondary: rgba(20,184,166,0.05); --icon-color: #2dd4bf; --icon-shadow: rgba(20,184,166,0.4);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-400">{{ __('messages.payments.idempotency_hits', ['default' => 'Idempotency Cache Hits']) }}</p>
                    <h3 class="text-2xl font-bold mt-1 text-white">{{ $cachedResponses }}</h3>
                    <p class="text-xs text-slate-500 mt-1">{{ $idempotencyHits }} {{ __('messages.payments.total_keys', ['default' => 'total keys']) }}</p>
                </div>
                <div class="dark-stats-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Revenue Chart + Gateway Distribution --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Daily Revenue Chart --}}
        <div class="dark-card lg:col-span-2">
            <div class="px-5 py-4 border-b border-[#2d3748]">
                <h3 class="font-semibold text-white">{{ __('messages.payments.daily_revenue', ['default' => 'Daily Revenue (Last 30 Days)']) }}</h3>
            </div>
            <div class="p-5">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>

        {{-- Revenue by Gateway --}}
        <div class="dark-card">
            <div class="px-5 py-4 border-b border-[#2d3748]">
                <h3 class="font-semibold text-white">{{ __('messages.payments.revenue_by_gateway', ['default' => 'Revenue by Gateway']) }}</h3>
            </div>
            <div class="p-5 space-y-4">
                @forelse($revenueByGateway as $gateway => $data)
                <div class="flex items-center justify-between p-3 rounded-lg bg-white/[0.02]">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500/20 to-purple-500/20 flex items-center justify-center">
                            <i class="fab fa-{{ $gateway }} text-blue-400"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-white capitalize">{{ $gateway }}</p>
                            <p class="text-xs text-slate-500">{{ $data['count'] }} transactions</p>
                        </div>
                    </div>
                    <p class="text-lg font-bold text-emerald-400">${{ number_format($data['total'], 2) }}</p>
                </div>
                @empty
                <div class="text-center text-slate-500 py-8">{{ __('messages.common.no_data', ['default' => 'No data available']) }}</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Plan Distribution + Revenue by Plan --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Plan Distribution Chart --}}
        <div class="dark-card">
            <div class="px-5 py-4 border-b border-[#2d3748]">
                <h3 class="font-semibold text-white">{{ __('messages.payments.plan_distribution', ['default' => 'Plan Distribution']) }}</h3>
            </div>
            <div class="p-5">
                <canvas id="planChart" height="100"></canvas>
            </div>
        </div>

        {{-- Revenue by Plan --}}
        <div class="dark-card">
            <div class="px-5 py-4 border-b border-[#2d3748]">
                <h3 class="font-semibold text-white">{{ __('messages.payments.revenue_by_plan', ['default' => 'Revenue by Plan']) }}</h3>
            </div>
            <div class="p-5 space-y-4">
                @php
                    $planColors = [
                        'free' => 'gray',
                        'basic' => 'blue',
                        'pro' => 'purple',
                        'enterprise' => 'amber',
                    ];
                    $planIcons = [
                        'free' => 'fa-box',
                        'basic' => 'fa-rocket',
                        'pro' => 'fa-gem',
                        'enterprise' => 'fa-building',
                    ];
                @endphp
                @forelse($planDistribution as $plan)
                <div class="flex items-center justify-between p-3 rounded-lg bg-white/[0.02]">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-{{ $planColors[$plan->plan] ?? 'gray' }}-500/20 to-{{ $planColors[$plan->plan] ?? 'gray' }}-500/10 flex items-center justify-center">
                            <i class="fas {{ $planIcons[$plan->plan] ?? 'fa-box' }} text-{{ $planColors[$plan->plan] ?? 'gray' }}-400"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-white capitalize">{{ $plan->plan }}</p>
                            <p class="text-xs text-slate-500">{{ $plan->count }} {{ __('messages.payments.subscribers', ['default' => 'subscribers']) }}</p>
                        </div>
                    </div>
                    <p class="text-lg font-bold text-emerald-400">${{ number_format($plan->total_revenue ?? 0, 2) }}</p>
                </div>
                @empty
                <div class="text-center text-slate-500 py-8">{{ __('messages.common.no_data', ['default' => 'No data available']) }}</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Top Paying Tenants --}}
    <div class="dark-card">
        <div class="px-5 py-4 border-b border-[#2d3748] flex items-center justify-between">
            <h3 class="font-semibold text-white">{{ __('messages.payments.top_paying_tenants', ['default' => 'Top Paying Tenants']) }}</h3>
            <a href="{{ route('super_admin.tenants.index') }}" class="text-sm text-cyan-400 hover:text-cyan-300 font-medium transition-colors">
                {{ __('messages.common.view_all', ['default' => 'View All']) }}
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="dark-table">
                <thead>
                    <tr>
                        <th class="text-start">{{ __('messages.tenants.name', ['default' => 'Tenant']) }}</th>
                        <th class="text-start">{{ __('messages.tenants.plan', ['default' => 'Plan']) }}</th>
                        <th class="text-center">{{ __('messages.payments.total_paid', ['default' => 'Total Paid']) }}</th>
                        <th class="text-center">{{ __('messages.tenants.status', ['default' => 'Status']) }}</th>
                        <th class="text-center">{{ __('messages.common.created_at', ['default' => 'Created']) }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topTenants as $tenant)
                    <tr>
                        <td class="text-sm font-medium text-white">{{ $tenant->name }}</td>
                        <td class="text-sm capitalize">
                            <span class="dark-badge dark-badge-violet">{{ $tenant->plan }}</span>
                        </td>
                        <td class="text-center text-sm font-semibold text-emerald-400">
                            ${{ number_format($tenant->total_paid ?? 0, 2) }}
                        </td>
                        <td class="text-center">
                            @if($tenant->is_active)
                                <span class="dark-badge dark-badge-emerald">
                                    <i class="fas fa-check-circle"></i>
                                    {{ __('messages.common.active', ['default' => 'Active']) }}
                                </span>
                            @else
                                <span class="dark-badge dark-badge-rose">
                                    <i class="fas fa-times-circle"></i>
                                    {{ __('messages.common.inactive', ['default' => 'Inactive']) }}
                                </span>
                            @endif
                        </td>
                        <td class="text-center text-sm text-slate-400">
                            {{ $tenant->created_at->format('M d, Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-slate-500 py-8">{{ __('messages.common.no_data', ['default' => 'No data available']) }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Recent Transactions --}}
    <div class="dark-card">
        <div class="px-5 py-4 border-b border-[#2d3748]">
            <h3 class="font-semibold text-white">{{ __('messages.payments.recent_transactions', ['default' => 'Recent Transactions']) }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="dark-table">
                <thead>
                    <tr>
                        <th class="text-start">{{ __('messages.payments.date', ['default' => 'Date']) }}</th>
                        <th class="text-start">{{ __('messages.payments.tenant', ['default' => 'Tenant']) }}</th>
                        <th class="text-start">{{ __('messages.payments.type', ['default' => 'Type']) }}</th>
                        <th class="text-start">{{ __('messages.payments.gateway', ['default' => 'Gateway']) }}</th>
                        <th class="text-center">{{ __('messages.payments.status', ['default' => 'Status']) }}</th>
                        <th class="text-end">{{ __('messages.payments.amount', ['default' => 'Amount']) }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTransactions as $transaction)
                    <tr>
                        <td class="text-sm text-slate-400">
                            {{ $transaction->paid_at?->format('M d, Y H:i') ?? $transaction->created_at->format('M d, Y H:i') }}
                        </td>
                        <td class="text-sm font-medium text-white">
                            {{ $transaction->tenant?->name ?? 'N/A' }}
                        </td>
                        <td class="text-sm capitalize text-slate-300">
                            {{ $transaction->type }}
                        </td>
                        <td class="text-sm">
                            <span class="inline-flex items-center gap-2">
                                <i class="fab fa-{{ $transaction->gateway }} text-lg"></i>
                                <span class="capitalize">{{ $transaction->gateway }}</span>
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge {{ $transaction->status_badge_class }}">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </td>
                        <td class="text-end text-sm font-semibold text-white">
                            {{ $transaction->formatted_amount }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-slate-500 py-8">{{ __('messages.common.no_data', ['default' => 'No transactions found']) }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_keys($chartData)) !!},
            datasets: [{
                label: '{{ __("messages.payments.revenue", ["default" => "Revenue"]) }}',
                data: {!! json_encode(array_column($chartData, 'total')) !!},
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 0,
                pointHoverRadius: 6,
                pointHoverBackgroundColor: '#10b981',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    titleColor: '#e2e8f0',
                    bodyColor: '#e2e8f0',
                    borderColor: 'rgba(100, 116, 139, 0.3)',
                    borderWidth: 1,
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            return '$' + context.parsed.y.toFixed(2);
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(255, 255, 255, 0.05)' },
                    ticks: { color: '#94a3b8', maxTicksLimit: 10 }
                },
                y: {
                    grid: { color: 'rgba(255, 255, 255, 0.05)' },
                    ticks: {
                        color: '#94a3b8',
                        callback: function(value) {
                            return '$' + value;
                        }
                    }
                }
            }
        }
    });

    // Plan Distribution Chart
    const planCtx = document.getElementById('planChart').getContext('2d');
    const planChart = new Chart(planCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($planDistribution->pluck('plan')->toArray()) !!},
            datasets: [{
                data: {!! json_encode($planDistribution->pluck('count')->toArray()) !!},
                backgroundColor: [
                    'rgba(107, 114, 128, 0.8)',
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(168, 85, 247, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                ],
                borderColor: [
                    'rgba(107, 114, 128, 1)',
                    'rgba(59, 130, 246, 1)',
                    'rgba(168, 85, 247, 1)',
                    'rgba(245, 158, 11, 1)',
                ],
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: '#94a3b8', padding: 20 }
                },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    titleColor: '#e2e8f0',
                    bodyColor: '#e2e8f0',
                    borderColor: 'rgba(100, 116, 139, 0.3)',
                    borderWidth: 1,
                    padding: 12,
                    cornerRadius: 8,
                }
            }
        }
    });
</script>
@endpush
@endsection
