<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class generatepromptrequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'image' => [
                'required',
                'file',
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:2048',
                'dimensions:width=1024,height=1024',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'image.required' => 'The image field is required.',
            'image.file' => 'The image must be a file.',
            'image.image' => 'The image must be an image.',
            'image.mimes' => 'The image must be a valid image type (jpeg, png, jpg, gif, svg).',
            'image.max' => 'The image may not be greater than 2MB.',
            'image.dimensions' => 'The image must be 1024x1024 pixels.',
        ];
    }
}
