@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">{{ __('messages.tenants.title') }}</h1>
            <p class="text-slate-400 text-sm mt-1">Manage your tenants and their subscriptions</p>
        </div>
        <a href="{{ route('super_admin.tenants.create') }}" class="dark-btn dark-btn-primary inline-flex items-center gap-2">
            <i class="fas fa-plus"></i>
            {{ __('messages.tenants.create') }}
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="flex items-center gap-3 px-4 py-3 rounded-xl border" style="background:rgba(16,185,129,0.08);border-color:rgba(16,185,129,0.2);color:#34d399;">
            <i class="fas fa-check-circle"></i>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Tenants Table --}}
    <div class="dark-card">
        <div class="overflow-x-auto">
            <table class="dark-table">
                <thead>
                    <tr>
                        <th class="text-start">{{ __('messages.tenants.name') }}</th>
                        <th class="text-start">{{ __('messages.tenants.subdomain') }}</th>
                        <th class="text-start">{{ __('messages.tenants.plan') }}</th>
                        <th class="text-center">{{ __('messages.users.title') }}</th>
                        <th class="text-center">{{ __('messages.tenants.status') }}</th>
                        <th class="text-center">{{ __('messages.common.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tenants as $tenant)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="dark-avatar" style="background:linear-gradient(135deg, #3b82f6, #8b5cf6);">
                                    {{ strtoupper(substr($tenant->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-sm text-white">{{ $tenant->name }}</p>
                                    <p class="text-xs text-slate-500">ID: {{ $tenant->id }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <code class="px-2 py-1 rounded text-sm font-mono" style="background:rgba(255,255,255,0.04);color:var(--text-secondary);">{{ $tenant->subdomain }}.crm.app</code>
                        </td>
                        <td>
                            @if($tenant->plan === 'free')
                                <span class="dark-badge dark-badge-gray">
                                    <i class="fas fa-gift mr-1"></i> Free
                                </span>
                            @elseif($tenant->plan === 'basic')
                                <span class="dark-badge dark-badge-cyan">
                                    <i class="fas fa-rocket mr-1"></i> Basic
                                </span>
                            @elseif($tenant->plan === 'pro')
                                <span class="dark-badge dark-badge-violet">
                                    <i class="fas fa-crown mr-1"></i> Pro
                                </span>
                            @else
                                <span class="dark-badge dark-badge-amber">
                                    <i class="fas fa-building mr-1"></i> Enterprise
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full font-semibold text-sm" style="background:rgba(6,182,212,0.12);color:#22d3ee;">
                                {{ $tenant->users_count }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($tenant->is_active)
                                <span class="dark-badge dark-badge-emerald">
                                    <i class="fas fa-check-circle mr-1"></i> {{ __('messages.common.active') }}
                                </span>
                            @else
                                <span class="dark-badge dark-badge-rose">
                                    <i class="fas fa-times-circle mr-1"></i> {{ __('messages.common.inactive') }}
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('super_admin.tenants.show', $tenant) }}" class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors" style="background:rgba(6,182,212,0.1);color:#22d3ee;" onmouseover="this.style.background='rgba(6,182,212,0.2)'" onmouseout="this.style.background='rgba(6,182,212,0.1)'" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('super_admin.tenants.edit', $tenant) }}" class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors" style="background:rgba(245,158,11,0.1);color:#fbbf24;" onmouseover="this.style.background='rgba(245,158,11,0.2)'" onmouseout="this.style.background='rgba(245,158,11,0.1)'" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('super_admin.tenants.destroy', $tenant) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this tenant?')" class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors" style="background:rgba(244,63,94,0.1);color:#fb7185;" onmouseover="this.style.background='rgba(244,63,94,0.2)'" onmouseout="this.style.background='rgba(244,63,94,0.1)'" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12">
                            <div class="flex flex-col items-center">
                                <div class="dark-empty-icon">
                                    <i class="fas fa-building"></i>
                                </div>
                                <p class="text-slate-500 mb-3">No tenants found</p>
                                <a href="{{ route('super_admin.tenants.create') }}" class="text-cyan-400 hover:text-cyan-300 text-sm transition-colors">Create your first tenant</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($tenants->hasPages())
        <div class="px-6 py-4 border-t border-[#2d3748]">
            {{ $tenants->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
