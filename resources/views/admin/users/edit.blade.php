@extends('layouts.app')
@section('content')
    <div class="max-w-2xl">
        <div class="dark-card overflow-hidden">
            <div class="p-6 border-b border-[#2d3748]">
                <h2 class="text-xl font-semibold text-slate-100">{{ __('messages.users.edit_user') }}</h2>
                <p class="text-sm text-slate-500 mt-1">{{ __('messages.users.edit_description') ?? 'Update user information and role' }}</p>
            </div>

            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-300 mb-1">{{ __('messages.users.name') }}</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                            class="dark-input w-full"
                            placeholder="{{ __('messages.users.name_placeholder') ?? 'Enter full name' }}">
                        @error('name')
                        <p class="mt-1 text-sm text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-300 mb-1">{{ __('messages.users.email') }}</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                            class="dark-input w-full"
                            placeholder="user@example.com">
                        @error('email')
                        <p class="mt-1 text-sm text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-300 mb-1">
                            {{ __('messages.users.password') }}
                            <span class="text-slate-500 font-normal">({{ __('messages.users.leave_blank') ?? 'leave blank to keep current' }})</span>
                        </label>
                        <input type="password" name="password" id="password" class="dark-input w-full"
                            placeholder="{{ __('messages.users.password_placeholder_edit') ?? 'Leave blank to keep current password' }}">
                        @error('password')
                        <p class="mt-1 text-sm text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-slate-300 mb-1">{{ __('messages.users.role') }}</label>
                        <select name="role" id="role" class="dark-select w-full">
                            <option value="Admin" {{ old('role', $user->roles->first()?->name) == 'Admin' ? 'selected' : '' }}>{{ __('messages.roles.admin') }}</option>
                            <option value="Manager" {{ old('role', $user->roles->first()?->name) == 'Manager' ? 'selected' : '' }}>{{ __('messages.roles.manager') }}</option>
                            <option value="Agent" {{ old('role', $user->roles->first()?->name) == 'Agent' ? 'selected' : '' }}>{{ __('messages.roles.agent') }}</option>
                        </select>
                        @error('role')
                        <p class="mt-1 text-sm text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="bg-slate-800/50 rounded-xl p-4 border border-[#2d3748]">
                        <div class="flex items-center gap-4">
                            <div class="dark-avatar text-lg">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm text-slate-500">{{ __('messages.users.account_status') ?? 'Account Status' }}</p>
                                <p class="font-medium {{ $user->is_active ? 'text-emerald-400' : 'text-slate-500' }}">
                                    {{ $user->is_active ? __('messages.common.active') : __('messages.common.inactive') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-[#2d3748]">
                    <a href="{{ route('admin.users.index') }}" class="dark-btn dark-btn-secondary">
                        {{ __('messages.common.cancel') }}
                    </a>
                    <button type="submit" class="dark-btn dark-btn-primary">
                        <i class="fas fa-save"></i>
                        {{ __('messages.users.update_user') ?? 'Update User' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
