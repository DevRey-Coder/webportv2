<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'brand_id' => 'nullable|exists:brands,id',
            'actual_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'total_price' => 'nullable|integer|min:0',
            'unit' => 'nullable|string|in:kg,pcs,box,set',
            'more_information' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
            'photo' => 'nullable|string|url',
        ];
    }
}
