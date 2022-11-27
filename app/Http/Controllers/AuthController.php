<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;

class AuthController extends Controller
{

    private $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $token = $this->userService->login( $request );

        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials',
            ], 404);
        }

        $user = $this->userService->getLoggedUser();

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);

    }

    public function register(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = $this->userService->register( $request );

        $token = $this->userService->getUserToken( $user );

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function logout()
    {

        $this->userService->logout();

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);

    }

    public function refresh()
    {

        return response()->json([
            'status' => 'success',
            'user' => $this->userService->getLoggedUser(),
            'authorization' => [
                'token' => $this->userService->refreshToken(),
                'type' => 'bearer',
            ]
        ]);

    }

}
