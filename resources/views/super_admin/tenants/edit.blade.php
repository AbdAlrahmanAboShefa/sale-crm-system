@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>{{ __('messages.tenants.edit') }}: {{ $tenant->name }}</h2>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('super_admin.tenants.update', $tenant) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">{{ __('messages.tenants.name') }}</label>
                    <input type="text" name="name" class="form-control" value="{{ $tenant->name }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ __('messages.tenants.subdomain') }}</label>
                    <input type="text" name="subdomain" class="form-control" value="{{ $tenant->subdomain }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ __('messages.tenants.plan') }}</label>
                    <select name="plan" class="form-select" required>
                        <option value="free" {{ $tenant->plan === 'free' ? 'selected' : '' }}>{{ __('messages.plans.free') }}</option>
                        <option value="basic" {{ $tenant->plan === 'basic' ? 'selected' : '' }}>{{ __('messages.plans.basic') }}</option>
                        <option value="pro" {{ $tenant->plan === 'pro' ? 'selected' : '' }}>{{ __('messages.plans.pro') }}</option>
                        <option value="enterprise" {{ $tenant->plan === 'enterprise' ? 'selected' : '' }}>{{ __('messages.plans.enterprise') }}</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ __('messages.tenants.trial_ends_at') }}</label>
                    <input type="datetime-local" name="trial_ends_at" class="form-control" value="{{ $tenant->trial_ends_at?->format('Y-m-d\TH:i') }}">
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="is_active" class="form-check-input" id="is_active" {{ $tenant->is_active ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">{{ __('messages.tenants.is_active') }}</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">{{ __('messages.common.save') }}</button>
                <a href="{{ route('super_admin.tenants.index') }}" class="btn btn-secondary">{{ __('messages.common.cancel') }}</a>
            </form>
        </div>
    </div>
</div>
@endsection