<?php

namespace App\Http\Controllers;

use App\Http\Requests\Base\LoginRequest;
use App\Http\Requests\Base\RegisterRequest;
use App\Http\Resource\Admin\RegisterWithTokenRequest;
use App\Http\Resource\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class LoginController extends BaseController
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            User::COLUMN_NAME => $request->name,
            User::COLUMN_EMAIL => $request->email,
            User::COLUMN_PASSWORD => Hash::make($request->password),
        ]);

        $this->setData(UserResource::make($user));
        $this->setMessage('Пользователь успешно создан');
        $this->setStatusCode(Response::HTTP_CREATED);

        return $this->sendResponse();
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where(User::COLUMN_EMAIL, $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Неверные учетные данные',
            ], 401);
        }

        $this->setData(RegisterWithTokenRequest::make($user));

        return $this->sendResponse();
    }
}
