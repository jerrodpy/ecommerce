<?php

namespace App\Http\Requests\Base;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            User::COLUMN_EMAIL => 'required|exists:users,email',
            User::COLUMN_PASSWORD => 'required|string|min:8',
        ];
    }
}
