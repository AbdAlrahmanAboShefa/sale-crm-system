@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">{{ __('messages.super_admin.dashboard') }}</h2>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>{{ __('messages.tenants.title') }}</h5>
                    <h3>{{ \App\Models\Tenant::count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>{{ __('messages.common.active') }}</h5>
                    <h3>{{ \App\Models\Tenant::where('is_active', true)->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5>{{ __('messages.tenants.trial_ends_at') }}</h5>
                    <h3>{{ \App\Models\Tenant::whereNotNull('trial_ends_at')->where('trial_ends_at', '>', now())->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>{{ __('messages.users.title') }}</h5>
                    <h3>{{ \App\Models\User::count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>{{ __('messages.tenants.title') }}</h5>
                    <a href="{{ route('super_admin.tenants.index') }}" class="btn btn-primary btn-sm">
                        {{ __('messages.common.view_all') }}
                    </a>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ __('messages.tenants.name') }}</th>
                                <th>{{ __('messages.tenants.subdomain') }}</th>
                                <th>{{ __('messages.tenants.plan') }}</th>
                                <th>{{ __('messages.tenants.status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\Tenant::latest()->take(5)->get() as $tenant)
                            <tr>
                                <td>{{ $tenant->name }}</td>
                                <td><code>{{ $tenant->subdomain }}</code></td>
                                <td>{{ ucfirst($tenant->plan) }}</td>
                                <td>
                                    @if($tenant->is_active)
                                        <span class="badge bg-success">{{ __('messages.common.active') }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ __('messages.common.inactive') }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection