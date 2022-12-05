<?php

namespace Tests\Feature\auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class RegisterTest extends TestCase
{

    use RefreshDatabase;

    public function test_registration()
    {

        $data = [
            'name' => fake()->password,
            'email' => fake()->email,
        ];

        $response = $this->postJson( 'api/register',
            [
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => fake()->password()
            ]
        );

        $this->assertDatabaseHas( 'users', [ 'name' => $data['name'], 'email' => $data['email'] ] );

    }

    public function test_user_exists()
    {

        $user = User::first();
        $data = [
            'name' => $user->name,
            'email' => $user->email,
        ];

        $response = $this->postJson( 'api/register',
            [
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => fake()->password()
            ]
        );

        $response->assertStatus(422);

    }

}
