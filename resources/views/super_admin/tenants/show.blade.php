@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>{{ $tenant->name }}</h2>
        <div>
            <a href="{{ route('super_admin.tenants.edit', $tenant) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> {{ __('messages.common.edit') }}
            </a>
            <form action="{{ route('super_admin.tenants.toggleActive', $tenant) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn {{ $tenant->is_active ? 'btn-danger' : 'btn-success' }}">
                    <i class="fas fa-toggle-{{ $tenant->is_active ? 'off' : 'on' }}"></i>
                    {{ $tenant->is_active ? __('messages.common.deactivate') : __('messages.common.activate') }}
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">{{ __('messages.tenants.plan') }}</h5>
                    <span class="badge bg-primary fs-6">{{ ucfirst($tenant->plan) }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">{{ __('messages.tenants.status') }}</h5>
                    @if($tenant->is_active)
                        <span class="badge bg-success">{{ __('messages.common.active') }}</span>
                    @else
                        <span class="badge bg-danger">{{ __('messages.common.inactive') }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">{{ __('messages.tenants.subdomain') }}</h5>
                    <code>{{ $tenant->subdomain }}.crm.app</code>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">{{ __('messages.tenants.trial_ends_at') }}</h5>
                    {{ $tenant->trial_ends_at?->format('Y-m-d') ?? '-' }}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>{{ __('messages.tenants.users') }} ({{ $tenant->users->count() }})</h5>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse($tenant->users as $user)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $user->name }}
                            <span class="badge bg-secondary">{{ $user->roles->first()?->name }}</span>
                        </li>
                    @empty
                        <li class="list-group-item">{{ __('messages.common.no_data') }}</li>
                    @endforelse
                </ul>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>{{ __('messages.tenants.contacts') }} ({{ $tenant->contacts->count() }})</h5>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse($tenant->contacts->take(5) as $contact)
                        <li class="list-group-item">{{ $contact->name }}</li>
                    @empty
                        <li class="list-group-item">{{ __('messages.common.no_data') }}</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>{{ __('messages.tenants.deals') }} ({{ $tenant->deals->count() }})</h5>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse($tenant->deals->take(5) as $deal)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $deal->title }}
                            <span class="badge bg-{{ $deal->stage === 'Won' ? 'success' : ($deal->stage === 'Lost' ? 'danger' : 'warning') }}">
                                {{ $deal->stage }}
                            </span>
                        </li>
                    @empty
                        <li class="list-group-item">{{ __('messages.common.no_data') }}</li>
                    @endforelse
                </ul>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>{{ __('messages.tenants.activities') }} ({{ $tenant->activities->count() }})</h5>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse($tenant->activities->take(5) as $activity)
                        <li class="list-group-item">
                            <span class="badge bg-info me-2">{{ $activity->type }}</span>
                            {{ $activity->note }}
                        </li>
                    @empty
                        <li class="list-group-item">{{ __('messages.common.no_data') }}</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection