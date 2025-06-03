<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Redis;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AuthTest extends TestCase
{

    public static $firstExec = true;

    public function setUp(): void
    {
        parent::setUp();
        if (self::$firstExec) {
            self::$firstExec = false;
            echo "Clearing database.\n";
            Redis::flushDB();
            Artisan::call('migrate:fresh');
            echo "Database cleaned.\n";
        }
    }

    public function test_01_01_auth_register_with_correct_data(): void
    {
        $response = $this->post(route('auth.register'), [
            'name' => fake()->name(),
            'password' => 'Aa1@Aa1@',
            'email' => fake()->unique()->safeEmail(),
        ]);
        $response->assertStatus(201);
    }

    public function test_01_02_auth_register_with_all_incorrect_data(): void
    {
        $response = $this->post(route('auth.register'), [
            'name' => 'a',
            'password' => 'a',
            'email' => 'a',
        ]);
        $response->assertJson(
            fn(AssertableJson $json) => $json->hasAll([
                'message',
                'errors.name',
                'errors.email',
                'errors.password'
            ])
        );

    }

    public function test_01_03_auth_register_with_incorrect_email(): void
    {
        $invalidEmails = ['a', 'email.@email.com', 'email@email.com.'];

        foreach ($invalidEmails as $invalidEmail) {
            $response = $this->post(route('auth.register'), [
                'name' => fake()->name(),
                'password' => 'Aa1@Aa1@',
                'email' => $invalidEmail,
            ]);
            $response->assertJson(
                fn(AssertableJson $json) => $json->hasAll([
                    'message',
                    'errors.email'
                ])
                    ->missingAll([
                        'errors.name',
                        'errors.password'
                    ])
            );

        }
    }

    public function test_01_04_auth_register_with_incorrect_name(): void
    {
        $invalidNames = ['J', 'Jo', 'Joh', 'John', 'J Doe'];

        foreach ($invalidNames as $invalidName) {
            $response = $this->post(route('auth.register'), [
                'name' => $invalidName,
                'password' => 'Aa1@Aa1@',
                'email' => fake()->unique()->safeEmail(),
            ]);

            $response->assertJson(
                fn(AssertableJson $json) => $json->hasAll([
                    'message',
                    'errors.name'
                ])
                    ->missingAll([
                        'errors.email',
                        'errors.password'
                    ])
            );

        }
    }

    public function test_01_05_auth_register_with_incorrect_password(): void
    {
        $invalidPasswords = ['A', 'Aa', 'Aa1', 'Aa1@', 'Aa1Aa1', 'a1@a1@', 'Aa@Aa@'];

        foreach ($invalidPasswords as $invalidPassword) {
            $response = $this->post(route('auth.register'), [
                'name' => fake()->name(),
                'password' => $invalidPassword,
                'email' => fake()->unique()->safeEmail(),
            ]);

            $response->assertJson(
                fn(AssertableJson $json) => $json->hasAll([
                    'message',
                    'errors.password'
                ])
                    ->missingAll([
                        'errors.email',
                        'errors.name'
                    ])
            );

        }
    }

    public function test_02_01_auth(): void
    {
        $user = User::factory()->create([
            'password' => 'Aa1234@'
        ]);

        $response = $this->post(route('auth.login'), [
            'email' => $user->email,
            'password' => "Aa1234@"
        ]);

        $response->assertStatus(200);

        $response->assertJson(
            fn(AssertableJson $json) => $json->hasAll([
                'id',
                'name',
                'email',
                'auth.token',
                'auth.type',
            ])
        );
    }

    public function test_02_02_auth_invalid_email(): void
    {
        $user = User::factory()->create();

        $response = $this->post(route('auth.login'), [
            'email' => $user->email . "a",
            'password' => "Aa1@aa"
        ]);

        $response->assertJson(["message" => __("auth.user_not_found")]);
    }

    public function test_02_03_auth_invalid_password(): void
    {
        $user = User::factory()->create();

        $response = $this->post(route('auth.login'), [
            'email' => $user->email,
            'password' => "Aa1@ab"
        ]);

        $response->assertJson(["message" => __("auth.user_not_found")]);
    }

    public function test_02_04_auth_not_existing_user(): void
    {
        $user = User::factory()->make();

        $response = $this->post(route('auth.login'), [
            'email' => $user->email,
            'password' => "Aa1@aa"
        ]);

        $response->assertJson(["message" => __("auth.user_not_found")]);
    }

    public function test_03_01_my_data(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('auth.me'));


        $response->assertJson([
            "id" => $user->id,
            "name" => $user->name,
            "email" => $user->email,
        ]);
    }

    public function test_03_01_my_data_with_invalid_user(): void
    {
        $response = $this->get(route('auth.me'));
        $response->assertStatus(401);
    }

    public function test_04_01_logout(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken(name: 'login');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token->plainTextToken
        ])->delete(route('auth.logout'));

        $response->assertStatus(200);
    }

    public function test_04_01_logout_with_invalid_user(): void
    {
        $response = $this->delete(route('auth.logout'));
        $response->assertStatus(401);
    }
}
