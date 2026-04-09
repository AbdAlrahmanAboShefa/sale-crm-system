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
        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.activities.type') }} *</label>
        <select name="type" id="type" required
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="">{{ __('messages.activities.select_type') ?? 'Select Type' }}</option>
            @foreach(['Call', 'Meeting', 'Email', 'Task', 'Demo'] as $type)
                <option value="{{ $type }}" {{ old('type', $activity->type ?? '') == $type ? 'selected' : '' }}>
                    {{ $type }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="contact_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.contacts.title') }} *</label>
        <select name="contact_id" id="contact_id" required
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="">{{ __('messages.activities.select_contact') ?? 'Select Contact' }}</option>
            @foreach($contacts as $contact)
                <option value="{{ $contact->id }}" {{ old('contact_id', $activity->contact_id ?? '') == $contact->id ? 'selected' : '' }}>
                    {{ $contact->name }} ({{ $contact->company ?? __('messages.contacts.no_company') ?? 'No company' }})
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="deal_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.deals.deal') }}</label>
        <select name="deal_id" id="deal_id"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="">{{ __('messages.common.none') ?? 'None' }}</option>
            @foreach($deals as $deal)
                <option value="{{ $deal->id }}" {{ old('deal_id', $activity->deal_id ?? '') == $deal->id ? 'selected' : '' }}>
                    {{ $deal->title }} - {{ $deal->stage }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.activities.due_date') }} *</label>
        <input type="datetime-local" name="due_date" id="due_date" required
            value="{{ old('due_date', isset($activity->due_date) ? $activity->due_date->format('Y-m-d\TH:i') : '') }}"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
    </div>

    <div>
        <label for="duration_minutes" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.activities.duration') ?? 'Duration (minutes)' }}</label>
        <input type="number" name="duration_minutes" id="duration_minutes" 
            value="{{ old('duration_minutes', $activity->duration_minutes ?? 30) }}"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
    </div>

    <div>
        <label for="outcome" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.activities.outcome') ?? 'Outcome' }}</label>
        <select name="outcome" id="outcome"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="">{{ __('messages.activities.select_outcome') ?? 'Select Outcome' }}</option>
            @foreach(['Positive', 'Neutral', 'Negative'] as $outcome)
                <option value="{{ $outcome }}" {{ old('outcome', $activity->outcome ?? '') == $outcome ? 'selected' : '' }}>
                    {{ $outcome }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-span-2">
        <label for="note" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.activities.notes') }} *</label>
        <textarea name="note" id="note" rows="4" required
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            placeholder="{{ __('messages.activities.note_placeholder') ?? 'Enter activity details...' }}">{{ old('note', $activity->note ?? '') }}</textarea>
    </div>

    <div class="col-span-2">
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="is_done" id="is_done" value="1" 
                {{ old('is_done', $activity->is_done ?? false) ? 'checked' : '' }}
                class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
            <span class="text-sm font-medium text-gray-700">{{ __('messages.activities.mark_completed') ?? 'Mark as completed' }}</span>
        </label>
    </div>
</div>
