@if($errors->any())
    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        <ul class="list-disc list-inside text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.contacts.name') }} *</label>
        <input type="text" name="name" id="name" value="{{ old('name', $contact->name ?? '') }}" required
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
    </div>

    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.contacts.email') }} *</label>
        <input type="email" name="email" id="email" value="{{ old('email', $contact->email ?? '') }}" required
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
    </div>

    <div>
        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.contacts.phone') }}</label>
        <input type="text" name="phone" id="phone" value="{{ old('phone', $contact->phone ?? '') }}"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
    </div>

    <div>
        <label for="company" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.contacts.company') }}</label>
        <input type="text" name="company" id="company" value="{{ old('company', $contact->company ?? '') }}"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
    </div>

    <div>
        <label for="website" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.common.website') }}</label>
        <input type="url" name="website" id="website" value="{{ old('website', $contact->website ?? '') }}" placeholder="https://"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
    </div>

    <div>
        <label for="source" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.contacts.source') }} *</label>
        <select name="source" id="source" required
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="">{{ __('messages.common.select_source') }}</option>
            @foreach(['website', 'referral', 'social', 'cold'] as $source)
                <option value="{{ $source }}" {{ old('source', $contact->source ?? '') == $source ? 'selected' : '' }}>
                    {{ __("messages.contacts.sources." . $source) }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.common.status') }} *</label>
        <select name="status" id="status" required
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            @foreach(['Lead', 'Prospect', 'Client', 'Lost', 'Inactive'] as $status)
                <option value="{{ $status }}" {{ old('status', $contact->status ?? 'Lead') == $status ? 'selected' : '' }}>
                    {{ $status }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="mt-6">
    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.common.tags') }}</label>
    <input type="text" name="tags_input" id="tags_input" placeholder="{{ __('messages.common.tags_placeholder') }}"
        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        value="{{ old('tags_input', isset($contact) && is_array($contact->tags) ? implode(', ', $contact->tags) : '') }}">
    <p class="text-xs text-gray-500 mt-1">{{ __('messages.common.separate_tags') }}</p>
    <input type="hidden" name="tags" id="tags_hidden" value="{{ old('tags', isset($contact) && is_array($contact->tags) ? json_encode($contact->tags) : '[]') }}">
</div>
