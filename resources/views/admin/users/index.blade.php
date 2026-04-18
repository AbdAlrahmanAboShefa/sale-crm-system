@extends('layouts.app')
@section('content')
    @if(session('success'))
    <div class="mb-6 dark-toast border-emerald-500/30">
        <div class="w-10 h-10 rounded-xl bg-emerald-500/20 flex items-center justify-center">
            <i class="fas fa-check text-emerald-400"></i>
        </div>
        <span class="text-emerald-300 font-medium">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 dark-toast border-rose-500/30">
        <div class="w-10 h-10 rounded-xl bg-rose-500/20 flex items-center justify-center">
            <i class="fas fa-exclamation-circle text-rose-400"></i>
        </div>
        <span class="text-rose-300 font-medium">{{ session('error') }}</span>
    </div>
    @endif

    <div class="dark-card overflow-hidden">
        <div class="p-6 border-b border-[#2d3748]">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex-1 max-w-md">
                    <form method="GET" action="{{ route('admin.users.index') }}" class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-500"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('messages.common.search_placeholder') }}"
                            class="dark-input w-full !ps-10">
                    </form>
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-sm text-slate-400">
                        <span class="font-semibold text-slate-200">{{ $userCount }}</span> / {{ $userLimit }} users
                    </div>
                    @if($canAddUser)
                    <a href="{{ route('admin.users.create') }}" class="dark-btn dark-btn-primary">
                        <i class="fas fa-plus"></i>
                        {{ __('messages.users.new_user') }}
                    </a>
                    @else
                    <button disabled class="dark-btn dark-btn-secondary opacity-50 cursor-not-allowed">
                        <i class="fas fa-ban"></i>
                        Limit Reached
                    </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="dark-table">
                <thead>
                    <tr>
                        <th class="!border-t-transparent !bg-slate-800/50">{{ __('messages.users.user') ?? 'User' }}</th>
                        <th class="!border-t-transparent !bg-slate-800/50">{{ __('messages.users.email') }}</th>
                        <th class="!border-t-transparent !bg-slate-800/50">{{ __('messages.users.role') }}</th>
                        <th class="!border-t-transparent !bg-slate-800/50">{{ __('messages.common.status') }}</th>
                        <th class="!border-t-transparent !bg-slate-800/50">{{ __('messages.common.created_at') }}</th>
                        <th class="!border-t-transparent !bg-slate-800/50">{{ __('messages.common.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr class="{{ !$user->is_active ? 'opacity-50' : '' }}">
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="dark-avatar">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span class="font-semibold text-slate-200">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="text-sm text-slate-400">{{ $user->email }}</span>
                        </td>
                        <td>
                            @php
                            $roleBadge = [
                                'Admin' => 'dark-badge-rose',
                                'Manager' => 'dark-badge-cyan',
                                'Agent' => 'dark-badge-emerald',
                            ];
                            @endphp
                            <span class="dark-badge {{ $roleBadge[$user->roles->first()?->name] ?? 'dark-badge-gray' }}">
                                {{ $user->roles->first()?->name ?? __('messages.users.no_role') ?? 'No Role' }}
                            </span>
                        </td>
                        <td>
                            @if($user->is_active)
                            <span class="dark-badge dark-badge-emerald">{{ __('messages.common.active') }}</span>
                            @else
                            <span class="dark-badge dark-badge-gray">{{ __('messages.common.inactive') }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-sm text-slate-500">{{ $user->created_at->format('M j, Y') }}</span>
                        </td>
                        <td>
                            <div class="flex items-center gap-1">
                                <a href="{{ route('admin.users.edit', $user) }}" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-cyan-400 hover:bg-cyan-500/10 transition-all" title="{{ __('messages.common.edit') }}">
                                    <i class="fas fa-pencil"></i>
                                </a>
                                @if(auth()->id() !== $user->id)
                                    @if($user->is_active)
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" x-data="{ showModal: false }" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" @click="showModal = true" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 transition-all" title="{{ __('messages.users.deactivate') }}">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                        <div x-show="showModal" x-transition class="dark-modal-backdrop flex items-center justify-center p-4" style="display: none;">
                                            <div class="dark-modal max-w-md w-full p-6" @click.stop>
                                                <div class="text-center">
                                                    <div class="w-16 h-16 rounded-2xl bg-rose-500/20 flex items-center justify-center mx-auto mb-4">
                                                        <i class="fas fa-exclamation-triangle text-rose-400 text-2xl"></i>
                                                    </div>
                                                    <h3 class="text-xl font-bold text-slate-100 mb-2">{{ __('messages.users.deactivate') }}</h3>
                                                    <p class="text-slate-400 mb-6">{{ __('messages.users.deactivate_warning', ['name' => $user->name]) ?? "Are you sure you want to deactivate {$user->name}?" }}</p>
                                                    <div class="flex justify-center gap-3">
                                                        <button type="button" @click="showModal = false" class="dark-btn dark-btn-secondary">{{ __('messages.common.cancel') }}</button>
                                                        <button type="submit" class="dark-btn bg-rose-600 hover:bg-rose-500 text-white">{{ __('messages.users.deactivate') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    @else
                                    <form action="{{ route('admin.users.activate', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-emerald-400 hover:bg-emerald-500/10 transition-all" title="{{ __('messages.users.activate') }}">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    @endif
                                @else
                                <span class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-600 cursor-not-allowed" title="{{ __('messages.users.cannot_deactivate_self') ?? 'Cannot deactivate yourself' }}">
                                    <i class="fas fa-ban"></i>
                                </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-16">
                            <div class="dark-empty-state">
                                <div class="dark-empty-icon">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-slate-300 mb-2">{{ __('messages.users.no_users') }}</h3>
                                <p class="text-slate-500 mb-6">{{ __('messages.users.get_started') }}</p>
                                <a href="{{ route('admin.users.create') }}" class="dark-btn dark-btn-primary">
                                    <i class="fas fa-plus"></i>
                                    {{ __('messages.users.new_user') }}
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-[#2d3748]">
            <div class="flex items-center justify-between">
                <p class="text-sm text-slate-500">
                    Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} results
                </p>
                <div class="dark-pagination">
                    @if($users->onFirstPage())
                        <span class="opacity-50"><i class="fas fa-chevron-left"></i></span>
                    @else
                        <a href="{{ $users->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a>
                    @endif
                    
                    @foreach($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                        @if($page == $users->currentPage())
                            <span class="active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                    
                    @if($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a>
                    @else
                        <span class="opacity-50"><i class="fas fa-chevron-right"></i></span>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection
