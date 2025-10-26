<?php

namespace App\Http\Requests\Shop;

use App\Models\Cart;
use App\Models\CartProductPivot;
use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FormRequest
{
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
            Cart::COLUMN_GUEST_ID => 'required|string',
            Cart::RELATION_PRODUCTS => 'required|array',
            Cart::RELATION_PRODUCTS . '.*.' . CartProductPivot::COLUMN_PRODUCT_ID => 'required|exists:products,id',
            Cart::RELATION_PRODUCTS . '.*.' . CartProductPivot::COLUMN_QUANTITY => 'required|integer|min:1',
        ];
    }
}
