<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => 'sometimes|string|max:255|unique:brands,name,' . $this->route('brand'),
            'slug'        => 'sometimes|string|unique:brands,slug,' . $this->route('brand'),
            'description' => 'nullable|string',
            'website'     => 'nullable|url',
        ];
    }
}
