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
        <label for="type" class="block text-sm font-medium text-slate-300 mb-1">{{ __('messages.activities.type') }} *</label>
        <select name="type" id="type" required class="dark-select w-full">
            <option value="">{{ __('messages.activities.select_type') ?? 'Select Type' }}</option>
            @foreach(['Call', 'Meeting', 'Email', 'Task', 'Demo'] as $type)
                <option value="{{ $type }}" {{ old('type', $activity->type ?? '') == $type ? 'selected' : '' }}>
                    {{ $type }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="contact_id" class="block text-sm font-medium text-slate-300 mb-1">{{ __('messages.contacts.title') }} *</label>
        <select name="contact_id" id="contact_id" required class="dark-select w-full">
            <option value="">{{ __('messages.activities.select_contact') ?? 'Select Contact' }}</option>
            @foreach($contacts as $contact)
                <option value="{{ $contact->id }}" {{ old('contact_id', $activity->contact_id ?? '') == $contact->id ? 'selected' : '' }}>
                    {{ $contact->name }} ({{ $contact->company ?? __('messages.contacts.no_company') ?? 'No company' }})
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="deal_id" class="block text-sm font-medium text-slate-300 mb-1">{{ __('messages.deals.deal') }}</label>
        <select name="deal_id" id="deal_id" class="dark-select w-full">
            <option value="">{{ __('messages.common.none') ?? 'None' }}</option>
            @foreach($deals as $deal)
                <option value="{{ $deal->id }}" {{ old('deal_id', $activity->deal_id ?? '') == $deal->id ? 'selected' : '' }}>
                    {{ $deal->title }} - {{ $deal->stage }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="due_date" class="block text-sm font-medium text-slate-300 mb-1">{{ __('messages.activities.due_date') }} *</label>
        <input type="datetime-local" name="due_date" id="due_date" required
            value="{{ old('due_date', isset($activity->due_date) ? $activity->due_date->format('Y-m-d\TH:i') : '') }}"
            class="dark-input w-full">
    </div>

    <div>
        <label for="duration_minutes" class="block text-sm font-medium text-slate-300 mb-1">{{ __('messages.activities.duration') ?? 'Duration (minutes)' }}</label>
        <input type="number" name="duration_minutes" id="duration_minutes" 
            value="{{ old('duration_minutes', $activity->duration_minutes ?? 30) }}"
            class="dark-input w-full">
    </div>

    <div>
        <label for="outcome" class="block text-sm font-medium text-slate-300 mb-1">{{ __('messages.activities.outcome') ?? 'Outcome' }}</label>
        <select name="outcome" id="outcome" class="dark-select w-full">
            <option value="">{{ __('messages.activities.select_outcome') ?? 'Select Outcome' }}</option>
            @foreach(['Positive', 'Neutral', 'Negative'] as $outcome)
                <option value="{{ $outcome }}" {{ old('outcome', $activity->outcome ?? '') == $outcome ? 'selected' : '' }}>
                    {{ $outcome }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-span-2">
        <label for="note" class="block text-sm font-medium text-slate-300 mb-1">{{ __('messages.activities.notes') }} *</label>
        <textarea name="note" id="note" rows="4" required
            class="dark-input w-full"
            placeholder="{{ __('messages.activities.note_placeholder') ?? 'Enter activity details...' }}">{{ old('note', $activity->note ?? '') }}</textarea>
    </div>

    <div class="col-span-2">
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="is_done" id="is_done" value="1" 
                {{ old('is_done', $activity->is_done ?? false) ? 'checked' : '' }}
                class="w-5 h-5 rounded border-slate-600 bg-slate-700 text-cyan-500 focus:ring-cyan-500 focus:ring-offset-slate-800">
            <span class="text-sm font-medium text-slate-300">{{ __('messages.activities.mark_completed') ?? 'Mark as completed' }}</span>
        </label>
    </div>
</div>
