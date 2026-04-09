@extends('layouts.app')
@section('content')
    <div class="max-w-2xl">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-xl font-semibold text-gray-800">{{ __('messages.users.new_user') }}</h2>
                <p class="text-sm text-gray-500 mt-1">{{ __('messages.users.create_description') ?? 'Add a new user to the system' }}</p>
            </div>

            <form action="{{ route('admin.users.store') }}" method="POST" class="p-6">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.users.name') }}</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                            placeholder="{{ __('messages.users.name_placeholder') ?? 'Enter full name' }}">
                        @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.users.email') }}</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                            placeholder="user@example.com">
                        @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.users.password') }}</label>
                        <input type="password" name="password" id="password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror"
                            placeholder="{{ __('messages.users.password_placeholder') ?? 'Minimum 8 characters' }}">
                        @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.users.role') }}</label>
                        <select name="role" id="role"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('role') border-red-500 @enderror">
                            <option value="">{{ __('messages.users.select_role') ?? 'Select a role' }}</option>
                            <option value="Admin" {{ old('role') == 'Admin' ? 'selected' : '' }}>{{ __('messages.roles.admin') }}</option>
                            <option value="Manager" {{ old('role') == 'Manager' ? 'selected' : '' }}>{{ __('messages.roles.manager') }}</option>
                            <option value="Agent" {{ old('role') == 'Agent' ? 'selected' : '' }}>{{ __('messages.roles.agent') }}</option>
                        </select>
                        @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-100">
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                        {{ __('messages.common.cancel') }}
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save {{ app()->getLocale() === 'ar' ? 'ms-2 me-0' : 'me-2' }}"></i>
                        {{ __('messages.users.create_user') ?? 'Create User' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
