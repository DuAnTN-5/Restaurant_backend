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
        return true; // Đặt thành true để cho phép request
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:product_categories,id', // Chỉ định bảng product_categories
            'slug' => 'required|string|unique:products,slug',
            'price' => 'required|numeric|min:0',
            'main_ingredients' => 'required|string',
            'product_code' => 'required|string|unique:products,product_code',
            'image_url' => 'nullable|url',
            'description' => 'nullable|string',
        ];
    }
}
