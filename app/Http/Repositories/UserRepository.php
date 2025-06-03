<?php

namespace App\Http\Repositories;

use App\Entities\UserEntity;
use App\Models\User;
use Hash;

class UserRepository
{

    protected function createLoginToken(User $user): User
    {
        $token = $user->createToken('login');
        $user->auth = (object) [
            'token' => $token->plainTextToken,
            'type' => 'Bearer',
        ];

        return $user;
    }

    public function findUserByEmail(string $email): ?UserEntity
    {
        $user = User::where('email', $email)->first();
        if (!$user) {
            return null;
        }
        $this->createLoginToken($user);
        return new UserEntity(model: $user);
    }

    public function create(string $name, string $email, string $password): ?UserEntity
    {
        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->save();

        $this->createLoginToken($user);
        return new UserEntity(model: $user);
    }

}
