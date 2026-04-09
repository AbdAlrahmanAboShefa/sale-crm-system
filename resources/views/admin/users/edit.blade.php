@extends('layouts.app')
@section('content')
    <div class="max-w-2xl">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-xl font-semibold text-gray-800">{{ __('messages.users.edit_user') }}</h2>
                <p class="text-sm text-gray-500 mt-1">{{ __('messages.users.edit_description') ?? 'Update user information and role' }}</p>
            </div>

            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.users.name') }}</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                            placeholder="{{ __('messages.users.name_placeholder') ?? 'Enter full name' }}">
                        @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.users.email') }}</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                            placeholder="user@example.com">
                        @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.users.password') }} <span class="text-gray-400 font-normal">({{ __('messages.users.leave_blank') ?? 'leave blank to keep current' }})</span></label>
                        <input type="password" name="password" id="password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror"
                            placeholder="{{ __('messages.users.password_placeholder_edit') ?? 'Leave blank to keep current password' }}">
                        @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.users.role') }}</label>
                        <select name="role" id="role"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('role') border-red-500 @enderror">
                            <option value="Admin" {{ old('role', $user->roles->first()?->name) == 'Admin' ? 'selected' : '' }}>{{ __('messages.roles.admin') }}</option>
                            <option value="Manager" {{ old('role', $user->roles->first()?->name) == 'Manager' ? 'selected' : '' }}>{{ __('messages.roles.manager') }}</option>
                            <option value="Agent" {{ old('role', $user->roles->first()?->name) == 'Agent' ? 'selected' : '' }}>{{ __('messages.roles.agent') }}</option>
                        </select>
                        @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-semibold text-lg">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">{{ __('messages.users.account_status') ?? 'Account Status' }}</p>
                                <p class="font-medium {{ $user->is_active ? 'text-emerald-600' : 'text-gray-500' }}">
                                    {{ $user->is_active ? __('messages.common.active') : __('messages.common.inactive') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-100">
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                        {{ __('messages.common.cancel') }}
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save {{ app()->getLocale() === 'ar' ? 'ms-2 me-0' : 'me-2' }}"></i>
                        {{ __('messages.users.update_user') ?? 'Update User' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
