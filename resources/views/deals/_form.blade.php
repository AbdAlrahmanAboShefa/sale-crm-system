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
        <label for="title" class="block text-sm font-medium text-slate-300 mb-1">{{ __('messages.deals.deal_title') }} *</label>
        <input type="text" name="title" id="title" value="{{ old('title', $deal->title ?? '') }}" required
            class="dark-input w-full">
    </div>

    <div>
        <label for="contact_id" class="block text-sm font-medium text-slate-300 mb-1">{{ __('messages.deals.contact') }} *</label>
        <select name="contact_id" id="contact_id" required
            class="dark-select w-full">
            <option value="">{{ __('messages.common.select_status') ?? 'Select' }}</option>
            @foreach($contacts as $contact)
                <option value="{{ $contact->id }}" {{ old('contact_id', $deal->contact_id ?? '') == $contact->id ? 'selected' : '' }}>
                    {{ $contact->name }} ({{ $contact->company ?? '-' }})
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="value" class="block text-sm font-medium text-slate-300 mb-1">{{ __('messages.deals.value') }} *</label>
        <div class="flex gap-2">
            <select name="currency" id="currency" class="dark-select w-24">
                <option value="USD" {{ old('currency', $deal->currency ?? 'USD') == 'USD' ? 'selected' : '' }}>USD</option>
                <option value="EUR" {{ old('currency', $deal->currency ?? 'USD') == 'EUR' ? 'selected' : '' }}>EUR</option>
                <option value="GBP" {{ old('currency', $deal->currency ?? 'USD') == 'GBP' ? 'selected' : '' }}>GBP</option>
            </select>
            <input type="number" name="value" id="value" value="{{ old('value', $deal->value ?? '') }}" required step="0.01" min="0"
                class="dark-input w-full">
        </div>
    </div>

    <div>
        <label for="stage" class="block text-sm font-medium text-slate-300 mb-1">{{ __('messages.deals.stage') }} *</label>
        <select name="stage" id="stage" required
            class="dark-select w-full">
            @foreach(['New', 'Contacted', 'Qualified', 'Proposal', 'Negotiation', 'Won', 'Lost'] as $stage)
                <option value="{{ $stage }}" {{ old('stage', $deal->stage ?? 'New') == $stage ? 'selected' : '' }}>
                    {{ __("messages.deals.stages." . strtolower($stage)) }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="probability" class="block text-sm font-medium text-slate-300 mb-1">{{ __('messages.deals.probability') ?? 'Probability (%)' }}</label>
        <input type="number" name="probability" id="probability" value="{{ old('probability', $deal->probability ?? 0) }}" min="0" max="100"
            class="dark-input w-full">
    </div>

    <div>
        <label for="expected_close_date" class="block text-sm font-medium text-slate-300 mb-1">{{ __('messages.deals.close_date') }}</label>
        <input type="date" name="expected_close_date" id="expected_close_date" 
            value="{{ old('expected_close_date', isset($deal->expected_close_date) ? $deal->expected_close_date->format('Y-m-d') : '') }}"
            class="dark-input w-full">
    </div>

    @if($users->count() > 0)
    <div>
        <label for="user_id" class="block text-sm font-medium text-slate-300 mb-1">{{ __('messages.deals.assigned_to') }} *</label>
        <select name="user_id" id="user_id" required
            class="dark-select w-full">
            @foreach($users as $u)
                <option value="{{ $u->id }}" {{ old('user_id', $deal->user_id ?? '') == $u->id ? 'selected' : '' }}>
                    {{ $u->name }}
                </option>
            @endforeach
        </select>
    </div>
    @endif

    <div class="md:col-span-2">
        <label for="lost_reason" class="block text-sm font-medium text-slate-300 mb-1">{{ __('messages.deals.lost_reason') ?? 'Lost Reason' }}</label>
        <textarea name="lost_reason" id="lost_reason" rows="2"
            class="dark-input w-full"
            placeholder="{{ __('messages.deals.lost_reason_placeholder') ?? 'Enter reason if deal is lost' }}">{{ old('lost_reason', $deal->lost_reason ?? '') }}</textarea>
    </div>
</div>
