@extends('layouts.app')
@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route($routePrefix . '.contacts.update', $contact) }}" method="POST">
            @csrf
            @method('PUT')
            @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.contacts.name') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $contact->name) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                    @error('name')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.contacts.email') }} <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email', $contact->email) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                    @error('email')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.contacts.phone') }}</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $contact->phone) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="company" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.contacts.company') }}</label>
                    <input type="text" name="company" id="company" value="{{ old('company', $contact->company) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="website" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.common.website') }}</label>
                    <input type="url" name="website" id="website" value="{{ old('website', $contact->website) }}" placeholder="https://" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="source" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.contacts.source') }} <span class="text-red-500">*</span></label>
                    <select name="source" id="source" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('messages.common.select_source') }}</option>
                        @foreach(['website', 'referral', 'social', 'cold'] as $source)
                        <option value="{{ $source }}" {{ old('source', $contact->source) == $source ? 'selected' : '' }}>{{ __("messages.contacts.sources." . $source) }}</option>
                        @endforeach
                    </select>
                    @error('source')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.common.status') }} <span class="text-red-500">*</span></label>
                    <select name="status" id="status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @foreach(['Lead', 'Prospect', 'Client', 'Lost', 'Inactive'] as $status)
                        <option value="{{ $status }}" {{ old('status', $contact->status) == $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                    @error('status')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.common.tags') }}</label>
                <input type="text" name="tags_input" id="tags_input" placeholder="{{ __('messages.common.tags_placeholder') }}" value="{{ old('tags_input', $contact->tags ? implode(', ', $contact->tags) : '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <p class="text-xs text-gray-500 mt-1">{{ __('messages.common.separate_tags') }}</p>
                <input type="hidden" name="tags" id="tags_hidden" value="{{ old('tags', $contact->tags ? json_encode($contact->tags) : '[]') }}">
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route($routePrefix . '.contacts.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">{{ __('messages.common.cancel') }}</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">{{ __('messages.common.edit') }} {{ __('messages.contacts.title') }}</button>
            </div>
        </form>
    </div>
@endsection
