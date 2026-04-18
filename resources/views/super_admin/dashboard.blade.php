@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">{{ __('messages.super_admin.dashboard') }}</h1>
            <p class="text-slate-400 text-sm mt-1">{{ __('messages.dashboard.today_summary') }}</p>
        </div>
        <a href="{{ route('super_admin.payments.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg glass-card hover:border-purple-500/50 transition-colors text-sm">
            <i class="fas fa-chart-line text-purple-400"></i>
            {{ __('messages.payments.dashboard_title', ['default' => 'Payment Dashboard']) }}
        </a>
    </div>

    {{-- Primary Stats Row --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Total Tenants --}}
        <div class="dark-stats-card" style="--card-accent: #3b82f6; --card-accent-secondary: #2563eb; --icon-bg: rgba(59,130,246,0.2); --icon-bg-secondary: rgba(59,130,246,0.05); --icon-color: #60a5fa; --icon-shadow: rgba(59,130,246,0.4);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-400">{{ __('messages.tenants.title') }}</p>
                    <h3 class="text-3xl font-bold mt-1 text-white">{{ $stats['totalTenants'] }}</h3>
                </div>
                <div class="dark-stats-icon">
                    <i class="fas fa-building"></i>
                </div>
            </div>
        </div>

        {{-- Active Tenants --}}
        <div class="dark-stats-card" style="--card-accent: #10b981; --card-accent-secondary: #059669; --icon-bg: rgba(16,185,129,0.2); --icon-bg-secondary: rgba(16,185,129,0.05); --icon-color: #34d399; --icon-shadow: rgba(16,185,129,0.4);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-400">{{ __('messages.common.active') }}</p>
                    <h3 class="text-3xl font-bold mt-1 text-white">{{ $stats['activeTenants'] }}</h3>
                </div>
                <div class="dark-stats-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>

        {{-- Total Users --}}
        <div class="dark-stats-card" style="--card-accent: #8b5cf6; --card-accent-secondary: #7c3aed; --icon-bg: rgba(139,92,246,0.2); --icon-bg-secondary: rgba(139,92,246,0.05); --icon-color: #a78bfa; --icon-shadow: rgba(139,92,246,0.4);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-400">{{ __('messages.users.title') }}</p>
                    <h3 class="text-3xl font-bold mt-1 text-white">{{ $stats['totalUsers'] }}</h3>
                </div>
                <div class="dark-stats-icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>

        {{-- Pipeline Value --}}
        <div class="dark-stats-card" style="--card-accent: #f59e0b; --card-accent-secondary: #d97706; --icon-bg: rgba(245,158,11,0.2); --icon-bg-secondary: rgba(245,158,11,0.05); --icon-color: #fbbf24; --icon-shadow: rgba(245,158,11,0.4);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-400">{{ __('messages.deals.total_value') }}</p>
                    <h3 class="text-2xl font-bold mt-1 text-white">${{ number_format($stats['totalValue'], 0) }}</h3>
                </div>
                <div class="dark-stats-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Secondary Stats Row --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Total Deals --}}
        <div class="dark-stats-card" style="--card-accent: #06b6d4; --card-accent-secondary: #0891b2; --icon-bg: rgba(6,182,212,0.2); --icon-bg-secondary: rgba(6,182,212,0.05); --icon-color: #22d3ee; --icon-shadow: rgba(6,182,212,0.4);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-400">{{ __('messages.deals.title') }}</p>
                    <h3 class="text-2xl font-bold text-white mt-1">{{ $stats['totalDeals'] }}</h3>
                </div>
                <div class="dark-stats-icon">
                    <i class="fas fa-hand-holding-dollar"></i>
                </div>
            </div>
        </div>

        {{-- Total Contacts --}}
        <div class="dark-stats-card" style="--card-accent: #10b981; --card-accent-secondary: #059669; --icon-bg: rgba(16,185,129,0.2); --icon-bg-secondary: rgba(16,185,129,0.05); --icon-color: #34d399; --icon-shadow: rgba(16,185,129,0.4);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-400">{{ __('messages.contacts.title') }}</p>
                    <h3 class="text-2xl font-bold text-white mt-1">{{ $stats['totalContacts'] }}</h3>
                </div>
                <div class="dark-stats-icon">
                    <i class="fas fa-address-book"></i>
                </div>
            </div>
        </div>

        {{-- Won Deals --}}
        <div class="dark-stats-card" style="--card-accent: #10b981; --card-accent-secondary: #059669; --icon-bg: rgba(16,185,129,0.2); --icon-bg-secondary: rgba(16,185,129,0.05); --icon-color: #34d399; --icon-shadow: rgba(16,185,129,0.4);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-400">{{ __('messages.deals.won') }}</p>
                    <h3 class="text-2xl font-bold text-white mt-1">{{ $stats['wonDeals'] }}</h3>
                </div>
                <div class="dark-stats-icon">
                    <i class="fas fa-trophy"></i>
                </div>
            </div>
        </div>

        {{-- Trial Tenants --}}
        <div class="dark-stats-card" style="--card-accent: #f59e0b; --card-accent-secondary: #d97706; --icon-bg: rgba(245,158,11,0.2); --icon-bg-secondary: rgba(245,158,11,0.05); --icon-color: #fbbf24; --icon-shadow: rgba(245,158,11,0.4);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-400">{{ __('messages.tenants.trial') }}</p>
                    <h3 class="text-2xl font-bold text-white mt-1">{{ $stats['trialTenants'] }}</h3>
                </div>
                <div class="dark-stats-icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Two Column: Tenants Table + Activities --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Recent Tenants --}}
        <div class="dark-card">
            <div class="px-5 py-4 border-b border-[#2d3748] flex items-center justify-between">
                <h3 class="font-semibold text-white">{{ __('messages.tenants.title') }}</h3>
                <a href="{{ route('super_admin.tenants.index') }}" class="text-sm text-cyan-400 hover:text-cyan-300 font-medium transition-colors">
                    {{ __('messages.common.view_all') }}
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="dark-table">
                    <thead>
                        <tr>
                            <th class="text-start">{{ __('messages.tenants.name') }}</th>
                            <th class="text-center">{{ __('messages.users.title') }}</th>
                            <th class="text-center">{{ __('messages.contacts.title') }}</th>
                            <th class="text-center">{{ __('messages.deals.title') }}</th>
                            <th class="text-center">{{ __('messages.tenants.status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tenantsWithStats as $tenant)
                        <tr>
                            <td class="text-sm font-medium text-white">{{ $tenant->name }}</td>
                            <td class="text-center text-sm text-slate-400">{{ $tenant->users_count }}</td>
                            <td class="text-center text-sm text-slate-400">{{ $tenant->contacts_count }}</td>
                            <td class="text-center text-sm text-slate-400">{{ $tenant->deals_count }}</td>
                            <td class="text-center">
                                @if($tenant->is_active)
                                    <span class="dark-badge dark-badge-emerald">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        {{ __('messages.common.active') }}
                                    </span>
                                @else
                                    <span class="dark-badge dark-badge-rose">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        {{ __('messages.common.inactive') }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-slate-500 py-8">{{ __('messages.common.no_data') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent Activities --}}
        <div class="dark-card">
            <div class="px-5 py-4 border-b border-[#2d3748]">
                <h3 class="font-semibold text-white">{{ __('messages.activities.recent') }}</h3>
            </div>
            <div class="max-h-96 overflow-y-auto">
                @forelse($recentActivities as $activity)
                <div class="px-5 py-3 border-b border-[#2d3748] hover:bg-white/[0.02] transition-colors">
                    <div class="flex items-start gap-3">
                        <div class="mt-1">
                            @if($activity->type === 'Call')
                                <span class="w-8 h-8 rounded-full flex items-center justify-center" style="background:rgba(16,185,129,0.15)">
                                    <i class="fas fa-phone" style="color:#34d399"></i>
                                </span>
                            @elseif($activity->type === 'Email')
                                <span class="w-8 h-8 rounded-full flex items-center justify-center" style="background:rgba(59,130,246,0.15)">
                                    <i class="fas fa-envelope" style="color:#60a5fa"></i>
                                </span>
                            @elseif($activity->type === 'Meeting')
                                <span class="w-8 h-8 rounded-full flex items-center justify-center" style="background:rgba(139,92,246,0.15)">
                                    <i class="fas fa-users" style="color:#a78bfa"></i>
                                </span>
                            @else
                                <span class="w-8 h-8 rounded-full flex items-center justify-center" style="background:rgba(245,158,11,0.15)">
                                    <i class="fas fa-tasks" style="color:#fbbf24"></i>
                                </span>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-slate-200 truncate">{{ Str::limit($activity->note, 60) }}</p>
                            <div class="flex items-center gap-2 mt-1 flex-wrap">
                                <span class="text-xs text-slate-500">{{ $activity->user?->name ?? 'N/A' }}</span>
                                @if($activity->deal?->tenant)
                                    <span class="text-xs text-cyan-400">• {{ $activity->deal->tenant->name }}</span>
                                @endif
                                <span class="text-xs text-slate-500">• {{ $activity->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-5 py-8 text-center text-slate-500">{{ __('messages.common.no_data') }}</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Recent Deals Table --}}
    <div class="dark-card">
        <div class="px-5 py-4 border-b border-[#2d3748]">
            <h3 class="font-semibold text-white">{{ __('messages.deals.recent') }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="dark-table">
                <thead>
                    <tr>
                        <th class="text-start">{{ __('messages.deals.title') }}</th>
                        <th class="text-start">{{ __('messages.contacts.title') }}</th>
                        <th class="text-start">{{ __('messages.users.title') }}</th>
                        <th class="text-start">{{ __('messages.tenants.name') }}</th>
                        <th class="text-center">{{ __('messages.deals.stage') }}</th>
                        <th class="text-end">{{ __('messages.deals.value') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentDeals as $deal)
                    <tr>
                        <td class="text-sm font-medium text-white">{{ $deal->title }}</td>
                        <td class="text-sm text-slate-400">{{ $deal->contact?->name ?? 'N/A' }}</td>
                        <td class="text-sm text-slate-400">{{ $deal->user?->name ?? 'N/A' }}</td>
                        <td class="text-sm">
                            @if($deal->tenant)
                                <span class="dark-badge dark-badge-violet">{{ $deal->tenant->name }}</span>
                            @else
                                <span class="text-slate-500">N/A</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($deal->stage === 'Won')
                                <span class="dark-badge dark-badge-emerald">{{ $deal->stage }}</span>
                            @elseif($deal->stage === 'Lost')
                                <span class="dark-badge dark-badge-rose">{{ $deal->stage }}</span>
                            @else
                                <span class="dark-badge dark-badge-cyan">{{ $deal->stage }}</span>
                            @endif
                        </td>
                        <td class="text-end text-sm font-semibold text-white">${{ number_format($deal->value, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-slate-500 py-8">{{ __('messages.common.no_data') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
