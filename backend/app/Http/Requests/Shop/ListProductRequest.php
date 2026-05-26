<?php

namespace App\Http\Requests\Shop;

use App\Repositories\Base\PaginableContract;
use Illuminate\Foundation\Http\FormRequest;

class ListProductRequest extends FormRequest
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
        return array_merge(PaginableContract::REQUEST_RULES, [
//            'category_id' => 'sometimes|exists:categories,id',
        ]);
    }
}
