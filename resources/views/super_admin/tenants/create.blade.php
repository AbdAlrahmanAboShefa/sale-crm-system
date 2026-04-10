@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>{{ __('messages.tenants.create') }}</h2>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('super_admin.tenants.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">{{ __('messages.tenants.name') }}</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ __('messages.tenants.subdomain') }}</label>
                    <div class="input-group">
                        <input type="text" name="subdomain" class="form-control" placeholder="company" required>
                        <span class="input-group-text">.crm.app</span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ __('messages.tenants.plan') }}</label>
                    <select name="plan" class="form-select" required>
                        <option value="free">{{ __('messages.plans.free') }}</option>
                        <option value="basic">{{ __('messages.plans.basic') }}</option>
                        <option value="pro">{{ __('messages.plans.pro') }}</option>
                        <option value="enterprise">{{ __('messages.plans.enterprise') }}</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ __('messages.tenants.trial_ends_at') }}</label>
                    <input type="datetime-local" name="trial_ends_at" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">{{ __('messages.common.save') }}</button>
                <a href="{{ route('super_admin.tenants.index') }}" class="btn btn-secondary">{{ __('messages.common.cancel') }}</a>
            </form>
        </div>
    </div>
</div>
@endsection