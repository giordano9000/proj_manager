<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserService
{

    public function login( array $credentials ) : ?string
    {

        return $this->checkCredentials( $credentials );

    }

    private function checkCredentials( array $credentials ) : ?string
    {
        return Auth::attempt( $credentials );
    }

    public function getUserToken( User $user ) : string
    {
        return Auth::login( $user );
    }

    public function getLoggedUser() : User
    {
        return Auth::user();
    }

    public function refreshToken() : string
    {
        return Auth::refresh();
    }

    public function register( array $params ) : User
    {

        return User::create( [
            'name' => $params[ 'name' ],
            'email' => $params[ 'email' ],
            'password' => Hash::make( $params[ 'password' ] ),
        ] );

    }

    public function logout() : void
    {
        Auth::logout();
    }

}
