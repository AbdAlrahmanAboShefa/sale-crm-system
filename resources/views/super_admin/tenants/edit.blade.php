@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="dark-avatar text-lg" style="background:linear-gradient(135deg, #3b82f6, #8b5cf6);">
                {{ strtoupper(substr($tenant->name, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-2xl font-bold text-white">{{ __('messages.tenants.edit') }}</h1>
                <p class="text-slate-400 text-sm">Update tenant information</p>
            </div>
        </div>
        <a href="{{ route('super_admin.tenants.show', $tenant) }}" class="inline-flex items-center gap-2 text-slate-400 hover:text-white font-medium transition-colors">
            <i class="fas fa-arrow-left"></i>
            Back to Tenant
        </a>
    </div>

    {{-- Form Card --}}
    <div class="dark-card max-w-2xl">
        <div class="px-6 py-4 border-b border-[#2d3748] flex items-center justify-between" style="background:rgba(255,255,255,0.02);">
            <div class="flex items-center gap-3">
                <i class="fas fa-building" style="color:#22d3ee"></i>
                <h3 class="font-semibold text-white">Tenant Information</h3>
            </div>
            @if($tenant->is_active)
                <span class="dark-badge dark-badge-emerald">
                    <i class="fas fa-check-circle mr-1"></i> Active
                </span>
            @else
                <span class="dark-badge dark-badge-rose">
                    <i class="fas fa-times-circle mr-1"></i> Inactive
                </span>
            @endif
        </div>
        <form action="{{ route('super_admin.tenants.update', $tenant) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="p-6 space-y-5">

                {{-- Name --}}
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">{{ __('messages.tenants.name') }} *</label>
                    <input type="text" name="name" value="{{ $tenant->name }}"
                        class="dark-input w-full" required>
                    @error('name')
                        <p class="mt-2 text-sm" style="color:#fb7185">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Subdomain --}}
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">{{ __('messages.tenants.subdomain') }} *</label>
                    <div class="flex">
                        <input type="text" name="subdomain" value="{{ $tenant->subdomain }}"
                            class="dark-input flex-1 rounded-r-none border-r-0 font-mono" required>
                        <span class="inline-flex items-center px-4 rounded-r-lg text-sm font-mono" style="background:var(--dark-bg-tertiary);border:1px solid var(--dark-border);color:var(--text-muted);border-left:none;">
                            .crm.app
                        </span>
                    </div>
                    @error('subdomain')
                        <p class="mt-2 text-sm" style="color:#fb7185">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Plan --}}
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">{{ __('messages.tenants.plan') }} *</label>
                    <select name="plan" class="dark-select w-full" required>
                        <option value="free" {{ $tenant->plan === 'free' ? 'selected' : '' }}>Free</option>
                        <option value="basic" {{ $tenant->plan === 'basic' ? 'selected' : '' }}>Basic</option>
                        <option value="pro" {{ $tenant->plan === 'pro' ? 'selected' : '' }}>Pro</option>
                        <option value="enterprise" {{ $tenant->plan === 'enterprise' ? 'selected' : '' }}>Enterprise</option>
                    </select>
                    @error('plan')
                        <p class="mt-2 text-sm" style="color:#fb7185">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Trial End --}}
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">{{ __('messages.tenants.trial_ends_at') }}</label>
                    <input type="datetime-local" name="trial_ends_at"
                        value="{{ $tenant->trial_ends_at?->format('Y-m-d\TH:i') }}"
                        class="dark-input w-full">
                    @error('trial_ends_at')
                        <p class="mt-2 text-sm" style="color:#fb7185">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Active Toggle --}}
                <div class="pt-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" id="is_active"
                            class="w-5 h-5 rounded accent-cyan-500"
                            {{ $tenant->is_active ? 'checked' : '' }}>
                        <div>
                            <span class="text-sm font-medium text-slate-300">{{ __('messages.tenants.is_active') }}</span>
                            <p class="text-xs text-slate-500">Enable or disable tenant access</p>
                        </div>
                    </label>
                    @error('is_active')
                        <p class="mt-2 text-sm" style="color:#fb7185">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Actions --}}
            <div class="px-6 py-4 border-t border-[#2d3748] flex items-center justify-end gap-3" style="background:rgba(255,255,255,0.02);">
                <a href="{{ route('super_admin.tenants.show', $tenant) }}" class="dark-btn dark-btn-secondary">
                    {{ __('messages.common.cancel') }}
                </a>
                <button type="submit" class="dark-btn dark-btn-primary flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    {{ __('messages.common.save') }}
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
