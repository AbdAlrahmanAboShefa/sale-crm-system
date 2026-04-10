@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>{{ __('messages.tenants.title') }}</h2>
        <a href="{{ route('super_admin.tenants.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> {{ __('messages.tenants.create') }}
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>{{ __('messages.tenants.name') }}</th>
                        <th>{{ __('messages.tenants.subdomain') }}</th>
                        <th>{{ __('messages.tenants.plan') }}</th>
                        <th>{{ __('messages.tenants.users') }}</th>
                        <th>{{ __('messages.tenants.status') }}</th>
                        <th>{{ __('messages.common.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tenants as $tenant)
                    <tr>
                        <td>{{ $tenant->id }}</td>
                        <td>{{ $tenant->name }}</td>
                        <td><code>{{ $tenant->subdomain }}</code></td>
                        <td>
                            <span class="badge bg-{{ $tenant->plan === 'free' ? 'secondary' : ($tenant->plan === 'pro' ? 'primary' : 'success') }}">
                                {{ ucfirst($tenant->plan) }}
                            </span>
                        </td>
                        <td>{{ $tenant->users_count }}</td>
                        <td>
                            @if($tenant->is_active)
                                <span class="badge bg-success">{{ __('messages.common.active') }}</span>
                            @else
                                <span class="badge bg-danger">{{ __('messages.common.inactive') }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('super_admin.tenants.show', $tenant) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('super_admin.tenants.edit', $tenant) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('super_admin.tenants.destroy', $tenant) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $tenants->links() }}
        </div>
    </div>
</div>
@endsection