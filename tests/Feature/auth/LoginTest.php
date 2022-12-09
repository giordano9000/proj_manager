<?php

namespace Tests\Feature;

use Faker\Core\Uuid;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{

    use RefreshDatabase;

    public function test_valid_credentials()
    {

        $user = User::inRandomOrder()->first();

        $response = $this->postJson('api/login',
            [
                'email' => $user->email,
                'password' => 'password'
            ]
        );

        $response->assertStatus(200);

    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_invalid_credentials()
    {

        $response = $this->postJson('api/login',
            [
                'email' => fake()->email(),
                'password' => fake()->password()
            ]
        );

        $response->assertStatus(404);

    }

}
