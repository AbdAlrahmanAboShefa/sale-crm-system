@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="dark-avatar text-xl" style="background:linear-gradient(135deg, #f59e0b, #ef4444);width:56px;height:56px;border-radius:14px;">
                {{ strtoupper(substr($tenant->name, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-2xl font-bold text-white">{{ $tenant->name }}</h1>
                <p class="text-slate-500 text-sm">Tenant ID: {{ $tenant->id }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('super_admin.tenants.edit', $tenant) }}" class="dark-btn dark-btn-secondary inline-flex items-center gap-2">
                <i class="fas fa-edit"></i>
                {{ __('messages.common.edit') }}
            </a>
            <form action="{{ route('super_admin.tenants.toggleActive', $tenant) }}" method="POST" class="inline">
                @csrf
                @method('PUT')
                <button type="submit" class="dark-btn inline-flex items-center gap-2" style="{{ $tenant->is_active ? 'background:rgba(244,63,94,0.15);color:#fb7185;border:1px solid rgba(244,63,94,0.2);' : 'background:rgba(16,185,129,0.15);color:#34d399;border:1px solid rgba(16,185,129,0.2);' }}">
                    <i class="fas fa-toggle-{{ $tenant->is_active ? 'off' : 'on' }}"></i>
                    {{ $tenant->is_active ? __('messages.common.deactivate') : __('messages.common.activate') }}
                </button>
            </form>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="flex items-center gap-3 px-4 py-3 rounded-xl border" style="background:rgba(16,185,129,0.08);border-color:rgba(16,185,129,0.2);color:#34d399;">
            <i class="fas fa-check-circle"></i>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Info Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Plan --}}
        <div class="dark-stats-card" style="--card-accent: #3b82f6; --card-accent-secondary: #2563eb; --icon-bg: rgba(59,130,246,0.2); --icon-bg-secondary: rgba(59,130,246,0.05); --icon-color: #60a5fa; --icon-shadow: rgba(59,130,246,0.4);">
            <div class="flex items-center justify-between mb-3">
                <div class="dark-stats-icon">
                    <i class="fas fa-gem"></i>
                </div>
                <span class="text-xs font-medium px-2 py-1 rounded-full" style="background:rgba(255,255,255,0.08);color:var(--text-secondary);">Plan</span>
            </div>
            <h3 class="text-2xl font-bold text-white capitalize">{{ $tenant->plan }}</h3>
            <p class="text-sm mt-1" style="color:var(--text-muted)">Subscription Plan</p>
        </div>

        {{-- Status --}}
        <div class="dark-stats-card" style="--card-accent: {{ $tenant->is_active ? '#10b981' : '#f43f5e' }}; --card-accent-secondary: {{ $tenant->is_active ? '#059669' : '#e11d48' }}; --icon-bg: {{ $tenant->is_active ? 'rgba(16,185,129,0.2)' : 'rgba(244,63,94,0.2)' }}; --icon-bg-secondary: {{ $tenant->is_active ? 'rgba(16,185,129,0.05)' : 'rgba(244,63,94,0.05)' }}; --icon-color: {{ $tenant->is_active ? '#34d399' : '#fb7185' }}; --icon-shadow: {{ $tenant->is_active ? 'rgba(16,185,129,0.4)' : 'rgba(244,63,94,0.4)' }};">
            <div class="flex items-center justify-between mb-3">
                <div class="dark-stats-icon">
                    <i class="fas fa-circle"></i>
                </div>
                <span class="text-xs font-medium px-2 py-1 rounded-full" style="background:rgba(255,255,255,0.08);color:var(--text-secondary);">Status</span>
            </div>
            <h3 class="text-xl font-bold text-white">{{ $tenant->is_active ? 'Active' : 'Inactive' }}</h3>
            <p class="text-sm mt-1" style="color:var(--text-muted)">{{ $tenant->is_active ? 'Running smoothly' : 'Suspended' }}</p>
        </div>

        {{-- Subdomain --}}
        <div class="dark-stats-card" style="--card-accent: #8b5cf6; --card-accent-secondary: #7c3aed; --icon-bg: rgba(139,92,246,0.2); --icon-bg-secondary: rgba(139,92,246,0.05); --icon-color: #a78bfa; --icon-shadow: rgba(139,92,246,0.4);">
            <div class="flex items-center justify-between mb-3">
                <div class="dark-stats-icon">
                    <i class="fas fa-globe"></i>
                </div>
                <span class="text-xs font-medium px-2 py-1 rounded-full" style="background:rgba(255,255,255,0.08);color:var(--text-secondary);">Domain</span>
            </div>
            <h3 class="text-lg font-bold text-white truncate">{{ $tenant->subdomain }}.crm.app</h3>
            <p class="text-sm mt-1" style="color:var(--text-muted)">Subdomain</p>
        </div>

        {{-- Trial --}}
        <div class="dark-stats-card" style="--card-accent: #f59e0b; --card-accent-secondary: #d97706; --icon-bg: rgba(245,158,11,0.2); --icon-bg-secondary: rgba(245,158,11,0.05); --icon-color: #fbbf24; --icon-shadow: rgba(245,158,11,0.4);">
            <div class="flex items-center justify-between mb-3">
                <div class="dark-stats-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <span class="text-xs font-medium px-2 py-1 rounded-full" style="background:rgba(255,255,255,0.08);color:var(--text-secondary);">Trial</span>
            </div>
            <h3 class="text-xl font-bold text-white">{{ $tenant->trial_ends_at ? $tenant->trial_ends_at->format('M d, Y') : 'N/A' }}</h3>
            <p class="text-sm mt-1" style="color:var(--text-muted)">{{ $tenant->trial_ends_at?->diffForHumans() ?? 'No trial period' }}</p>
        </div>
    </div>

    {{-- Two Column: Users + Contacts --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Users --}}
        <div class="dark-card">
            <div class="px-5 py-4 border-b border-[#2d3748] flex items-center justify-between">
                <h3 class="font-semibold text-white flex items-center gap-2">
                    <i class="fas fa-users" style="color:#60a5fa"></i>
                    {{ __('messages.tenants.users') }} ({{ $tenant->users->count() }})
                </h3>
            </div>
            <div class="divide-y divide-[#2d3748]">
                @forelse($tenant->users as $user)
                <div class="px-5 py-3 flex items-center justify-between hover:bg-white/[0.02] transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-semibold" style="background:linear-gradient(135deg, #64748b, #475569);">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-sm text-white">{{ $user->name }}</p>
                            <p class="text-xs text-slate-500">{{ $user->email }}</p>
                        </div>
                    </div>
                    <span class="dark-badge dark-badge-gray">
                        {{ $user->roles->first()?->name ?? 'No Role' }}
                    </span>
                </div>
                @empty
                <div class="px-5 py-8 text-center text-slate-500">
                    <i class="fas fa-users text-2xl mb-2" style="color:var(--dark-border)"></i>
                    <p>No users found</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Contacts --}}
        <div class="dark-card">
            <div class="px-5 py-4 border-b border-[#2d3748] flex items-center justify-between">
                <h3 class="font-semibold text-white flex items-center gap-2">
                    <i class="fas fa-address-book" style="color:#34d399"></i>
                    {{ __('messages.tenants.contacts') }} ({{ $tenant->contacts->count() }})
                </h3>
            </div>
            <div class="divide-y divide-[#2d3748]">
                @forelse($tenant->contacts->take(5) as $contact)
                <div class="px-5 py-3 hover:bg-white/[0.02] transition-colors">
                    <p class="font-medium text-sm text-white">{{ $contact->name }}</p>
                    <p class="text-xs text-slate-500">{{ $contact->email }}</p>
                </div>
                @empty
                <div class="px-5 py-8 text-center text-slate-500">
                    <i class="fas fa-address-book text-2xl mb-2" style="color:var(--dark-border)"></i>
                    <p>No contacts found</p>
                </div>
                @endforelse
                @if($tenant->contacts->count() > 5)
                <div class="px-5 py-3 text-center">
                    <span class="text-xs text-slate-500">+ {{ $tenant->contacts->count() - 5 }} more</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Two Column: Deals + Activities --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Deals --}}
        <div class="dark-card">
            <div class="px-5 py-4 border-b border-[#2d3748] flex items-center justify-between">
                <h3 class="font-semibold text-white flex items-center gap-2">
                    <i class="fas fa-hand-holding-dollar" style="color:#a78bfa"></i>
                    {{ __('messages.tenants.deals') }} ({{ $tenant->deals->count() }})
                </h3>
            </div>
            <div class="divide-y divide-[#2d3748]">
                @forelse($tenant->deals->take(5) as $deal)
                <div class="px-5 py-3 flex items-center justify-between hover:bg-white/[0.02] transition-colors">
                    <div>
                        <p class="font-medium text-sm text-white">{{ $deal->title }}</p>
                        <p class="text-xs text-slate-500">${{ number_format($deal->value, 2) }}</p>
                    </div>
                    @if($deal->stage === 'Won')
                        <span class="dark-badge dark-badge-emerald">
                            <i class="fas fa-trophy mr-1"></i> Won
                        </span>
                    @elseif($deal->stage === 'Lost')
                        <span class="dark-badge dark-badge-rose">
                            <i class="fas fa-times mr-1"></i> Lost
                        </span>
                    @else
                        <span class="dark-badge dark-badge-cyan">{{ $deal->stage }}</span>
                    @endif
                </div>
                @empty
                <div class="px-5 py-8 text-center text-slate-500">
                    <i class="fas fa-hand-holding-dollar text-2xl mb-2" style="color:var(--dark-border)"></i>
                    <p>No deals found</p>
                </div>
                @endforelse
                @if($tenant->deals->count() > 5)
                <div class="px-5 py-3 text-center">
                    <span class="text-xs text-slate-500">+ {{ $tenant->deals->count() - 5 }} more</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Activities --}}
        <div class="dark-card">
            <div class="px-5 py-4 border-b border-[#2d3748] flex items-center justify-between">
                <h3 class="font-semibold text-white flex items-center gap-2">
                    <i class="fas fa-clock" style="color:#fbbf24"></i>
                    {{ __('messages.tenants.activities') }} ({{ $tenant->activities->count() }})
                </h3>
            </div>
            <div class="divide-y divide-[#2d3748]">
                @forelse($tenant->activities->take(5) as $activity)
                <div class="px-5 py-3 hover:bg-white/[0.02] transition-colors">
                    <div class="flex items-center gap-2 mb-1">
                        @if($activity->type === 'Call')
                            <span class="w-6 h-6 rounded-full flex items-center justify-center" style="background:rgba(16,185,129,0.15)">
                                <i class="fas fa-phone" style="color:#34d399;font-size:10px"></i>
                            </span>
                        @elseif($activity->type === 'Email')
                            <span class="w-6 h-6 rounded-full flex items-center justify-center" style="background:rgba(59,130,246,0.15)">
                                <i class="fas fa-envelope" style="color:#60a5fa;font-size:10px"></i>
                            </span>
                        @elseif($activity->type === 'Meeting')
                            <span class="w-6 h-6 rounded-full flex items-center justify-center" style="background:rgba(139,92,246,0.15)">
                                <i class="fas fa-users" style="color:#a78bfa;font-size:10px"></i>
                            </span>
                        @else
                            <span class="w-6 h-6 rounded-full flex items-center justify-center" style="background:rgba(245,158,11,0.15)">
                                <i class="fas fa-tasks" style="color:#fbbf24;font-size:10px"></i>
                            </span>
                        @endif
                        <span class="text-xs text-slate-500">{{ $activity->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-sm text-slate-300 line-clamp-1">{{ Str::limit($activity->note, 60) }}</p>
                </div>
                @empty
                <div class="px-5 py-8 text-center text-slate-500">
                    <i class="fas fa-clock text-2xl mb-2" style="color:var(--dark-border)"></i>
                    <p>No activities found</p>
                </div>
                @endforelse
                @if($tenant->activities->count() > 5)
                <div class="px-5 py-3 text-center">
                    <span class="text-xs text-slate-500">+ {{ $tenant->activities->count() - 5 }} more</span>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
