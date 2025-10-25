<?php

namespace App\Http\Requests\Admin;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            Product::COLUMN_TITLE => 'nullable|string|max:255',
            Product::COLUMN_DESCRIPTION => 'nullable|string|max:1024',
            Product::COLUMN_PRICE => 'nullable|integer|min:0',
            Product::COLUMN_IMAGE => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            Product::RELATION_CATEGORIES => 'nullable|array',
            Product::RELATION_CATEGORIES . '.*' => 'sometimes|exists:categories,id',
        ];
    }
}
