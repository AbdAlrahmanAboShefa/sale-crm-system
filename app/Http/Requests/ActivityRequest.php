<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tenantId = auth()->user()?->tenant_id;

        return [
            'contact_id' => ['required', 'exists:contacts,id'],
            'deal_id' => ['nullable', 'exists:deals,id'],
            'type' => ['required', 'in:Call,Meeting,Email,Task,Demo'],
            'note' => ['required', 'string'],
            'outcome' => ['nullable', 'in:Positive,Neutral,Negative'],
            'due_date' => ['nullable', 'date'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
            'is_done' => ['boolean'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->any()) {
                return;
            }

            $tenantId = auth()->user()?->tenant_id;
            $contactId = $this->contact_id;
            $dealId = $this->deal_id;

            if ($contactId) {
                $contact = \App\Models\Contact::find($contactId);
                if (! $contact || $contact->tenant_id != $tenantId) {
                    $validator->errors()->add('contact_id', 'The selected contact does not belong to your organization.');
                }
            }

            if ($dealId) {
                $deal = \App\Models\Deal::find($dealId);
                if (! $deal || $deal->tenant_id != $tenantId) {
                    $validator->errors()->add('deal_id', 'The selected deal does not belong to your organization.');
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'contact_id.required' => 'Please select a contact.',
            'type.required' => 'Please select an activity type.',
            'note.required' => 'Please add a note for this activity.',
        ];
    }
}
