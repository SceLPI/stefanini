<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterFormRequest;
use App\Http\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private AuthService $service)
    {
    }

    public function me(): JsonResponse
    {
        return response()->json($this->service->me());
    }

    public function login(Request $request): JsonResponse
    {
        return response()->json($this->service->login(email: $request->email, password: $request->password));
    }

    public function logout(Request $request): JsonResponse
    {
        $this->service->logout(user: $request->user());

        return response()->json([
            'message' => __(key: 'auth.logged_out_success'),
        ], status: 200);
    }

    public function register(RegisterFormRequest $registerFormRequest): JsonResponse
    {
        return response()->json(
            $this->service->register(name: $registerFormRequest->name, email: $registerFormRequest->email, password: $registerFormRequest->password),
            status: 201
        );
    }
}
