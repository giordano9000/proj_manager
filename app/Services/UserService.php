<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserService
{

    public function login( Request $request ): ?string
    {

        $credentials = $request->only(['email', 'password']);

        return $this->checkCredentials( $credentials );

    }

    private function checkCredentials( $credentials ): ?string
    {
        return Auth::attempt( $credentials );
    }

    public function getUserToken( User $user )
    {
        return Auth::login($user);
    }

    public function getLoggedUser(): User
    {
        return Auth::user();
    }

    public function refreshToken(): string
    {
        return Auth::refresh();
    }

    public function register( Request $request ): User
    {

        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

    }

    public function logout(): void
    {
        Auth::logout();
    }

}
