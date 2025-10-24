<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\LoginRequest;
use App\Http\Requests\Admin\RegisterRequest;
use App\Http\Resource\Admin\RegisterWithTokenRequest;
use App\Http\Resource\Admin\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class LoginController extends BaseController
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $this->setData(UserResource::make($user));
        $this->setMessage('Пользователь успешно создан');
        $this->setStatusCode(Response::HTTP_CREATED);

        return $this->sendResponse();
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Неверные учетные данные',
            ], 401);
        }

        $this->setData(RegisterWithTokenRequest::make($user));

        return $this->sendResponse();
    }
}
