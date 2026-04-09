@extends('layouts.app')
@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route($routePrefix . '.activities.store') }}" method="POST">
            @csrf
            @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside text-sm">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="contact_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.contacts.title') }} <span class="text-red-500">*</span></label>
                    <select name="contact_id" id="contact_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('messages.activities.select_contact') ?? 'Select Contact' }}</option>
                        @foreach($contacts as $contact)
                        <option value="{{ $contact->id }}" {{ old('contact_id') == $contact->id ? 'selected' : '' }}>{{ $contact->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="deal_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.deals.deal') }}</label>
                    <select name="deal_id" id="deal_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('messages.common.none') ?? 'None' }}</option>
                        @foreach($deals as $deal)
                        <option value="{{ $deal->id }}" {{ old('deal_id') == $deal->id ? 'selected' : '' }}>{{ $deal->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.activities.type') }} <span class="text-red-500">*</span></label>
                    <select name="type" id="type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('messages.activities.select_type') ?? 'Select Type' }}</option>
                        @foreach(['Call', 'Meeting', 'Email', 'Task', 'Demo'] as $type)
                        <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="outcome" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.activities.outcome') ?? 'Outcome' }}</label>
                    <select name="outcome" id="outcome" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('messages.activities.select_outcome') ?? 'Select Outcome' }}</option>
                        @foreach(['Positive', 'Neutral', 'Negative'] as $outcome)
                        <option value="{{ $outcome }}" {{ old('outcome') == $outcome ? 'selected' : '' }}>{{ $outcome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.activities.due_date') }}</label>
                    <input type="datetime-local" name="due_date" id="due_date" value="{{ old('due_date') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="duration_minutes" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.activities.duration') ?? 'Duration (minutes)' }}</label>
                    <input type="number" name="duration_minutes" id="duration_minutes" value="{{ old('duration_minutes') }}" min="1" placeholder="30" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="mt-6">
                <label for="note" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.activities.note') }} <span class="text-red-500">*</span></label>
                <textarea name="note" id="note" rows="4" required placeholder="{{ __('messages.activities.note_placeholder') ?? 'Activity details...' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('note') }}</textarea>
            </div>

            <div class="mt-6">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_done" value="1" {{ old('is_done') ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="{{ app()->getLocale() === 'ar' ? 'ms-2 me-0' : 'ml-2' }} text-sm text-gray-700">{{ __('messages.activities.mark_completed') ?? 'Mark as completed' }}</span>
                </label>
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route($routePrefix . '.activities.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">{{ __('messages.common.cancel') }}</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">{{ __('messages.activities.save_activity') ?? 'Save Activity' }}</button>
            </div>
        </form>
    </div>
@endsection
