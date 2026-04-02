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

    public function messages(): array
    {
        return [
            'contact_id.required' => 'Please select a contact.',
            'type.required' => 'Please select an activity type.',
            'note.required' => 'Please add a note for this activity.',
        ];
    }
}
