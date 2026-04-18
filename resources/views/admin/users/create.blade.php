@extends('layouts.app')
@section('content')
    <div class="max-w-2xl">
        <div class="dark-card overflow-hidden">
            <div class="p-6 border-b border-[#2d3748]">
                <h2 class="text-xl font-semibold text-slate-100">{{ __('messages.users.new_user') }}</h2>
                <p class="text-sm text-slate-500 mt-1">{{ __('messages.users.create_description') ?? 'Add a new user to the system' }}</p>
            </div>

            <div class="px-6 pt-4">
                <div class="flex items-center justify-between text-sm mb-2">
                    <span class="text-slate-400">User Usage</span>
                    <span class="font-semibold text-slate-200">{{ $userCount }} / {{ $userLimit }}</span>
                </div>
                <div class="dark-progress-bar">
                    @php $percentage = min(100, ($userCount / $userLimit) * 100); @endphp
                    <div class="dark-progress-fill bg-gradient-to-r from-cyan-500 to-violet-500" style="width: {{ $percentage }}%"></div>
                </div>
                <p class="text-xs text-slate-500 mt-2">Plan: <span class="font-semibold text-cyan-400 capitalize">{{ auth()->user()->tenant->plan }}</span></p>
            </div>

            <form action="{{ route('admin.users.store') }}" method="POST" class="p-6">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-300 mb-1">{{ __('messages.users.name') }}</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="dark-input w-full"
                            placeholder="{{ __('messages.users.name_placeholder') ?? 'Enter full name' }}">
                        @error('name')
                        <p class="mt-1 text-sm text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-300 mb-1">{{ __('messages.users.email') }}</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            class="dark-input w-full"
                            placeholder="user@example.com">
                        @error('email')
                        <p class="mt-1 text-sm text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-300 mb-1">{{ __('messages.users.password') }}</label>
                        <input type="password" name="password" id="password"
                            class="dark-input w-full"
                            placeholder="{{ __('messages.users.password_placeholder') ?? 'Minimum 8 characters' }}">
                        @error('password')
                        <p class="mt-1 text-sm text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-slate-300 mb-1">{{ __('messages.users.role') }}</label>
                        <select name="role" id="role" class="dark-select w-full">
                            <option value="">{{ __('messages.users.select_role') ?? 'Select a role' }}</option>
                            <option value="Admin" {{ old('role') == 'Admin' ? 'selected' : '' }}>{{ __('messages.roles.admin') }}</option>
                            <option value="Manager" {{ old('role') == 'Manager' ? 'selected' : '' }}>{{ __('messages.roles.manager') }}</option>
                            <option value="Agent" {{ old('role') == 'Agent' ? 'selected' : '' }}>{{ __('messages.roles.agent') }}</option>
                        </select>
                        @error('role')
                        <p class="mt-1 text-sm text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-[#2d3748]">
                    <a href="{{ route('admin.users.index') }}" class="dark-btn dark-btn-secondary">
                        {{ __('messages.common.cancel') }}
                    </a>
                    <button type="submit" class="dark-btn dark-btn-primary">
                        <i class="fas fa-save"></i>
                        {{ __('messages.users.create_user') ?? 'Create User' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
