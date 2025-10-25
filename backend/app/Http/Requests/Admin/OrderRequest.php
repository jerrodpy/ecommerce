<?php

namespace App\Http\Requests\Admin;

use App\Enums\Status;
use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            Order::COLUMN_STATUS => ['nullable', Rule::enum(Status::class)],
            Order::COLUMN_COMMENTS => 'nullable|string',
        ];
    }
}
