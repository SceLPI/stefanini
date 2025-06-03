<?php

namespace Tests\Unit;

use App\Entities\UserEntity;
use App\Http\Repositories\UserRepository;
use App\Http\Services\AuthService;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthUnitTest extends TestCase
{

    private $service;

    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->service = new AuthService(userRepository: new UserRepository);
    }
    public function test_user_login()
    {
        $user = User::factory()->create([
            'password' => bcrypt('Aa1234@')
        ]);

        $userAuthData = $this->service->login(email: $user->email, password: 'Aa1234@');

        $this->assertTrue($userAuthData->id == $user->id);
        $this->assertTrue($userAuthData->name == $user->name);
        $this->assertTrue($userAuthData->email == $user->email);
        $this->assertTrue(Hash::check("Aa1234@", $userAuthData->password));
        $this->assertTrue(!empty($userAuthData->auth->token));
        $this->assertTrue(!empty($userAuthData->auth->type));
    }
    public function test_user_register()
    {
        $user = User::factory()->make([
            'password' => bcrypt('Aa1234@')
        ]);

        $userAuthData = $this->service->register(email: $user->email, password: 'Aa1234@', name: $user->name);

        $this->assertTrue(!empty($userAuthData->id));
        $this->assertTrue($userAuthData->name == $user->name);
        $this->assertTrue($userAuthData->email == $user->email);
        $this->assertTrue(Hash::check("Aa1234@", $userAuthData->password));
        $this->assertTrue(!empty($userAuthData->auth->token));
        $this->assertTrue(!empty($userAuthData->auth->type));
    }
}
