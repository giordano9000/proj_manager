<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\User as UserModel;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $seed = true;

    /**
     * Send request to login and return token
     *
     * @param null $userId
     * @return mixed
     */
    protected function get_token( $userId = NULL )
    {

        if ( !$userId ) {
            $user = UserModel::inRandomOrder()->first();
        } else {
            $user = UserModel::find( $userId );
        }

        $response = $this->postJson( 'api/login',
            [
                'email' => $user->email,
                'password' => 'password'
            ]
        );

        return $response->json( 'authorization.token' );

    }

    /**
     * Return array with authorization header
     *
     * @param $token
     * @return string[]
     */
    protected function get_auth_header( $token )
    {
        return [ 'Authorization' => 'Bearer ' . $token ];
    }

}
