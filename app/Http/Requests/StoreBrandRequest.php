<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:255|unique:brands,name',
            'slug'        => 'nullable|string|unique:brands,slug',
            'description' => 'nullable|string',
            'website'     => 'nullable|url',
        ];
    }
}
