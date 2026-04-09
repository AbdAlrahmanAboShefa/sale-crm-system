@extends('layouts.app')
@section('content')
    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg flex items-center">
        <i class="fas fa-check-circle {{ app()->getLocale() === 'ar' ? 'ms-2 me-0' : 'me-2' }}"></i>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center">
        <i class="fas fa-exclamation-circle {{ app()->getLocale() === 'ar' ? 'ms-2 me-0' : 'me-2' }}"></i>
        {{ session('error') }}
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex-1 max-w-md">
                    <form method="GET" action="{{ route('admin.users.index') }}" class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('messages.common.search_placeholder') }}"
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </form>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 rounded-lg text-sm font-medium text-white hover:bg-blue-700 shadow-sm transition-colors">
                        <i class="fas fa-plus {{ app()->getLocale() === 'ar' ? 'ms-2 me-0' : 'me-2' }}"></i>
                        {{ __('messages.users.new_user') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">
                        <th class="px-6 py-4">{{ __('messages.users.user') ?? 'User' }}</th>
                        <th class="px-6 py-4">{{ __('messages.users.email') }}</th>
                        <th class="px-6 py-4">{{ __('messages.users.role') }}</th>
                        <th class="px-6 py-4">{{ __('messages.common.status') }}</th>
                        <th class="px-6 py-4">{{ __('messages.common.created_at') }}</th>
                        <th class="px-6 py-4">{{ __('messages.common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                    <tr class="{{ !$user->is_active ? 'bg-gray-50 text-gray-400' : '' }} hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span class="font-medium text-gray-900">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            @php
                            $roleColors = [
                                'Admin' => 'bg-red-100 text-red-700',
                                'Manager' => 'bg-blue-100 text-blue-700',
                                'Agent' => 'bg-emerald-100 text-emerald-700',
                            ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $roleColors[$user->roles->first()?->name] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $user->roles->first()?->name ?? __('messages.users.no_role') ?? 'No Role' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($user->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                {{ __('messages.common.active') }}
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                {{ __('messages.common.inactive') }}
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $user->created_at->format('M j, Y') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="p-2 text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="{{ __('messages.common.edit') }}">
                                    <i class="fas fa-pencil"></i>
                                </a>
                                @if(auth()->id() !== $user->id)
                                    @if($user->is_active)
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" x-data="{ showModal: false }" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" @click="showModal = true" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="{{ __('messages.users.deactivate') }}">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                        <div x-show="showModal" x-transition class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                                            <div class="flex items-center justify-center min-h-screen px-4">
                                                <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity" @click="showModal = false"></div>
                                                <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full p-6 z-10">
                                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('messages.users.deactivate') }}</h3>
                                                    <p class="text-gray-600 mb-6">{{ __('messages.users.deactivate_warning', ['name' => $user->name]) ?? "Are you sure you want to deactivate {$user->name}? They will no longer be able to log in." }}</p>
                                                    <div class="flex justify-end gap-3">
                                                        <button type="button" @click="showModal = false" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">{{ __('messages.common.cancel') }}</button>
                                                        <button type="submit" class="px-4 py-2 bg-red-600 text-white hover:bg-red-700 rounded-lg">{{ __('messages.users.deactivate') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    @else
                                    <form action="{{ route('admin.users.activate', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="p-2 text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="{{ __('messages.users.activate') }}">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    @endif
                                @else
                                <span class="p-2 text-gray-300 cursor-not-allowed" title="{{ __('messages.users.cannot_deactivate_self') ?? 'Cannot deactivate yourself' }}">
                                    <i class="fas fa-ban"></i>
                                </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-user-shield text-gray-300 text-5xl mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-1">{{ __('messages.users.no_users') }}</h3>
                                <p class="text-gray-500 mb-4">{{ __('messages.users.get_started') }}</p>
                                <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 rounded-lg text-sm font-medium text-white hover:bg-blue-700">
                                    <i class="fas fa-plus {{ app()->getLocale() === 'ar' ? 'ms-2 me-0' : 'me-2' }}"></i>
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
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $users->withQueryString()->links() }}
        </div>
        @endif
    </div>
@endsection
