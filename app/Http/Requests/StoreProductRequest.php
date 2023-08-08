<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'brand_id' => 'required',
        //  exists:brands,id ( must put after brands)
            'actual_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'total_price' => 'required|integer|min:0',
            'unit' => 'required|string|in:kg,pcs,box,set',
            'more_information' => 'required|string',
            // 'user_id' => 'required|exists:users,id',
            'photo' => 'required|string|url', // Assuming 'photo' field stores URLs
        ];
    }
}
