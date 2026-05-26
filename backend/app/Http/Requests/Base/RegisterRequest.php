<?php

namespace App\Http\Requests\Base;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
//            User::COLUMN_NAME => 'required|string|max:255',
            User::COLUMN_EMAIL => 'required|string|email|max:255|unique:users',
            User::COLUMN_PASSWORD => 'required|string|min:8',
        ];
    }
}
