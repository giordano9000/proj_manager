<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use PHPUnit\Util\Exception;

class LogoutTest extends TestCase
{

    use RefreshDatabase;

    public function test_logout()
    {

        $token = $this->get_token();
        $response = $this->postJson( 'api/logout' )->withHeaders( [ 'Authorization' => 'Bearer ' . $token ] );
        $json = json_decode($response->getContent());
        $this->assertSame( $json->status, 'success');

    }

    public function test_need_token()
    {

        $response = $this->postJson( 'api/logout' );

        $response->assertStatus(401);

    }

}
