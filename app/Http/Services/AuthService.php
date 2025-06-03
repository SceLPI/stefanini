<?php

namespace App\Http\Services;

use App\Entities\UserEntity;
use App\Http\Repositories\AuthRepository;
use App\Http\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthService
{

    public function __construct(private UserRepository $userRepository)
    {
    }

    public function me(): User
    {
        return request()->user();
    }

    public function login(string $email, string $password): UserEntity
    {

        $user = $this->userRepository->findUserByEmail(email: $email);
        $validHash = Hash::check($password, $user?->password);

        if (!$validHash) {
            abort(404, __('auth.user_not_found'));
        }

        return $user;
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }

    public function register(string $name, string $email, string $password): UserEntity
    {
        return $this->userRepository->create(name: $name, email: $email, password: $password);
    }

}
