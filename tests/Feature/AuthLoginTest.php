<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AuthLoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {

        $response = $this->post('api/login',
            [
                'email' => User::inRandomOrder()->get()->first()->email,
                'password' => 'password'
            ]
        );

        $response->assertStatus(200);

    }
}
