<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'company' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'source' => ['required', 'in:website,referral,social,cold'],
            'status' => ['required', 'in:Lead,Prospect,Client,Lost,Inactive'],
            'tags' => ['nullable', 'string'],
            'tags.*' => ['string', 'max:50'],
            'custom_fields' => ['nullable', 'array'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Contact name is required.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'source.required' => 'Please select a contact source.',
            'status.required' => 'Please select a contact status.',
            'website.url' => 'Please enter a valid website URL.',
        ];
    }
}
