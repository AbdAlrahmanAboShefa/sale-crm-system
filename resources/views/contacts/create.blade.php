@extends('layouts.app')
@section('content')
    <div class="dark-card p-6">
        <form action="{{ route($routePrefix . '.contacts.store') }}" method="POST">
            @csrf
            @if($errors->any())
            <div class="mb-6 dark-toast border-rose-500/30">
                <div class="w-10 h-10 rounded-xl bg-rose-500/20 flex items-center justify-center">
                    <i class="fas fa-exclamation-circle text-rose-400"></i>
                </div>
                <div>
                    <p class="text-rose-300 font-medium">Validation Error</p>
                    <ul class="text-rose-400 text-sm list-disc list-inside">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-300 mb-1">{{ __('messages.contacts.name') }} <span class="text-rose-400">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required class="dark-input w-full">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-300 mb-1">{{ __('messages.contacts.email') }} <span class="text-rose-400">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required class="dark-input w-full">
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-slate-300 mb-1">{{ __('messages.contacts.phone') }}</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="dark-input w-full">
                </div>
                <div>
                    <label for="company" class="block text-sm font-medium text-slate-300 mb-1">{{ __('messages.contacts.company') }}</label>
                    <input type="text" name="company" id="company" value="{{ old('company') }}" class="dark-input w-full">
                </div>
                <div>
                    <label for="website" class="block text-sm font-medium text-slate-300 mb-1">{{ __('messages.common.website') }}</label>
                    <input type="url" name="website" id="website" value="{{ old('website') }}" placeholder="https://" class="dark-input w-full">
                </div>
                <div>
                    <label for="source" class="block text-sm font-medium text-slate-300 mb-1">{{ __('messages.contacts.source') }} <span class="text-rose-400">*</span></label>
                    <select name="source" id="source" required class="dark-select w-full">
                        <option value="">{{ __('messages.common.select_source') }}</option>
                        @foreach(['website', 'referral', 'social', 'cold'] as $source)
                        <option value="{{ $source }}" {{ old('source') == $source ? 'selected' : '' }}>{{ __("messages.contacts.sources." . $source) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-slate-300 mb-1">{{ __('messages.common.status') }} <span class="text-rose-400">*</span></label>
                    <select name="status" id="status" required class="dark-select w-full">
                        @foreach(['Lead', 'Prospect', 'Client', 'Lost', 'Inactive'] as $status)
                        <option value="{{ $status }}" {{ old('status', 'Lead') == $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-slate-300 mb-1">{{ __('messages.common.tags') }}</label>
                <input type="text" name="tags_input" id="tags_input" placeholder="{{ __('messages.common.tags_placeholder') }}" value="{{ old('tags_input', '') }}" class="dark-input w-full">
                <p class="text-xs text-slate-500 mt-1">{{ __('messages.common.separate_tags') }}</p>
                <input type="hidden" name="tags" id="tags_hidden" value="{{ old('tags', '[]') }}">
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route($routePrefix . '.contacts.index') }}" class="dark-btn dark-btn-secondary">{{ __('messages.common.cancel') }}</a>
                <button type="submit" class="dark-btn dark-btn-primary">{{ __('messages.contacts.new_contact') }}</button>
            </div>
        </form>
    </div>
@endsection
