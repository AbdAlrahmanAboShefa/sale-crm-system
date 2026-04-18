@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="dark-card overflow-hidden">
        <div class="p-6 border-b border-[#2d3748]">
            <h2 class="text-xl font-semibold text-slate-100">Tenant Settings</h2>
            <p class="text-sm text-slate-500 mt-1">Manage your company settings</p>
        </div>

        @if(session('success'))
        <div class="m-6 dark-toast border-emerald-500/30">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/20 flex items-center justify-center">
                <i class="fas fa-check-circle text-emerald-400"></i>
            </div>
            <span class="text-emerald-300 font-medium">{{ session('success') }}</span>
        </div>
        @endif

        <form action="{{ route('admin.settings.update') }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-slate-300 mb-2">Company Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $tenant->name) }}"
                    class="dark-input w-full" required>
                @error('name')
                <p class="mt-1 text-sm text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Subdomain</label>
                <div class="flex">
                    <input type="text" value="{{ $tenant->subdomain }}" disabled
                        class="dark-input flex-1 !bg-slate-800/50 !text-slate-400 rounded-l-lg">
                    <span class="inline-flex items-center px-4 py-2.5 bg-slate-800/50 border border-l-0 border-[#2d3748] rounded-r-lg text-slate-400 text-sm">
                        .crm.app
                    </span>
                </div>
                <p class="mt-1 text-xs text-slate-500">Subdomain cannot be changed</p>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="dark-btn dark-btn-primary">
                    <i class="fas fa-save"></i>
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    <div class="dark-card overflow-hidden">
        <div class="p-6 border-b border-[#2d3748]">
            <h3 class="text-lg font-semibold text-slate-100">Subscription Details</h3>
        </div>
        <div class="p-6 space-y-6">
            <div class="flex items-center justify-between">
                <span class="text-sm text-slate-400">Current Plan</span>
                @php
                $planBadge = [
                    'free' => 'dark-badge-gray',
                    'basic' => 'dark-badge-cyan',
                    'pro' => 'dark-badge-violet',
                    'enterprise' => 'dark-badge-amber',
                ];
                @endphp
                <span class="dark-badge {{ $planBadge[$tenant->plan] ?? 'dark-badge-gray' }} capitalize">
                    {{ $tenant->plan }}
                </span>
            </div>

            <div class="flex items-center justify-between">
                <span class="text-sm text-slate-400">Status</span>
                @if($tenant->isOnTrial())
                <div class="flex items-center gap-2">
                    <i class="fas fa-clock text-amber-400"></i>
                    <span class="text-sm font-medium text-amber-400">
                        Trial ends in {{ (int)now()->diffInDays($tenant->trial_ends_at) }} days
                    </span>
                </div>
                @else
                <div class="flex items-center gap-2">
                    <i class="fas fa-check-circle text-emerald-400"></i>
                    <span class="text-sm font-medium text-emerald-400">Active</span>
                </div>
                @endif
            </div>

            <div class="pt-2">
                <a href="{{ route('billing.upgrade') }}" class="btn-primary inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold text-white text-sm">
                    <i class="fas fa-crown"></i>
                    <span>{{ __('messages.billing.upgrade_btn', ['default' => 'Upgrade Plan']) }}</span>
                </a>
            </div>

            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-slate-400">User Quota</span>
                    <span class="text-sm font-medium text-slate-200">{{ $tenant->getUserCount() }} / {{ $tenant->getUserLimit() }} users</span>
                </div>
                <div class="dark-progress-bar">
                    @php $userPercent = min(100, ($tenant->getUserCount() / $tenant->getUserLimit()) * 100); @endphp
                    <div class="dark-progress-fill bg-gradient-to-r from-cyan-500 to-violet-500" style="width: {{ $userPercent }}%"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
