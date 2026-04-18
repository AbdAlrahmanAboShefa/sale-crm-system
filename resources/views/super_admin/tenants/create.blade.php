@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">{{ __('messages.tenants.create') }}</h1>
            <p class="text-slate-400 text-sm mt-1">Create a new tenant for your CRM</p>
        </div>
        <a href="{{ route('super_admin.tenants.index') }}" class="inline-flex items-center gap-2 text-slate-400 hover:text-white font-medium transition-colors">
            <i class="fas fa-arrow-left"></i>
            Back to Tenants
        </a>
    </div>

    {{-- Form Card --}}
    <div class="dark-card max-w-2xl">
        <div class="px-6 py-4 border-b border-[#2d3748] flex items-center gap-3" style="background:rgba(255,255,255,0.02);">
            <i class="fas fa-building" style="color:#22d3ee"></i>
            <h3 class="font-semibold text-white">Tenant Information</h3>
        </div>
        <form action="{{ route('super_admin.tenants.store') }}" method="POST">
            @csrf
            <div class="p-6 space-y-5">

                {{-- Name --}}
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">{{ __('messages.tenants.name') }} *</label>
                    <input type="text" name="name"
                        class="dark-input w-full"
                        placeholder="Enter tenant name" required>
                    @error('name')
                        <p class="mt-2 text-sm" style="color:#fb7185">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Subdomain --}}
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">{{ __('messages.tenants.subdomain') }} *</label>
                    <div class="flex">
                        <input type="text" name="subdomain"
                            class="dark-input flex-1 rounded-r-none border-r-0"
                            placeholder="company" required>
                        <span class="inline-flex items-center px-4 rounded-r-lg text-sm font-mono" style="background:var(--dark-bg-tertiary);border:1px solid var(--dark-border);color:var(--text-muted);border-left:none;">
                            .crm.app
                        </span>
                    </div>
                    <p class="mt-1 text-xs text-slate-500">Only letters, numbers, and hyphens allowed</p>
                    @error('subdomain')
                        <p class="mt-2 text-sm" style="color:#fb7185">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Plan --}}
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">{{ __('messages.tenants.plan') }} *</label>
                    <select name="plan" class="dark-select w-full" required>
                        <option value="free">Free</option>
                        <option value="basic">Basic</option>
                        <option value="pro">Pro</option>
                        <option value="enterprise">Enterprise</option>
                    </select>
                    @error('plan')
                        <p class="mt-2 text-sm" style="color:#fb7185">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Trial End --}}
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">{{ __('messages.tenants.trial_ends_at') }}</label>
                    <input type="datetime-local" name="trial_ends_at" class="dark-input w-full">
                    <p class="mt-1 text-xs text-slate-500">Leave empty if no trial period</p>
                    @error('trial_ends_at')
                        <p class="mt-2 text-sm" style="color:#fb7185">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Actions --}}
            <div class="px-6 py-4 border-t border-[#2d3748] flex items-center justify-end gap-3" style="background:rgba(255,255,255,0.02);">
                <a href="{{ route('super_admin.tenants.index') }}" class="dark-btn dark-btn-secondary">
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
