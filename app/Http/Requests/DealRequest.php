<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DealRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'contact_id' => ['required', 'exists:contacts,id'],
            'user_id' => ['required', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'value' => ['required', 'numeric', 'min:0'],
            'currency' => ['sometimes', 'string', 'size:3'],
            'stage' => ['required', 'in:New,Contacted,Qualified,Proposal,Negotiation,Won,Lost'],
            'probability' => ['required', 'integer', 'min:0', 'max:100'],
            'expected_close_date' => ['nullable', 'date', 'after:today'],
            'lost_reason' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'contact_id.required' => 'Please select a contact.',
            'contact_id.exists' => 'Selected contact does not exist.',
            'user_id.required' => 'Please select a deal owner.',
            'title.required' => 'Deal title is required.',
            'value.required' => 'Deal value is required.',
            'stage.required' => 'Please select a stage.',
            'probability.required' => 'Probability is required.',
            'probability.min' => 'Probability must be between 0 and 100.',
            'probability.max' => 'Probability must be between 0 and 100.',
            'expected_close_date.after' => 'Expected close date must be in the future.',
        ];
    }
}
