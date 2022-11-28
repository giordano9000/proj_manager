<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use PHPUnit\Util\Exception;

class AuthLogoutTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {

        $user = User::inRandomOrder()->get()->first();
        $response = $this->post( 'api/login',
            [
                'email' => $user->email,
                'password' => 'password'
            ]
        );

        $response->assertStatus( 200 );
        $token = $response->decodeResponseJson()['authorization']['token'];

        $response = $this->post( 'api/logout' )
            ->withHeaders( [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ] );

        $response = json_decode($response->getContent());
        throw_if( $response->status === 'success' , new Exception('error'));

    }
}
