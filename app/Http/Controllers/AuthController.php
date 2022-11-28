<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{

    private $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    /**
     * Login
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login( LoginRequest $request )
    {

        $credentials = $request->validated();

        $token = $this->userService->login( $credentials );

        if ( !$token ) {

            return response()->json( [
                'status' => 'error',
                'message' => 'Invalid credentials',
            ], 404 );

        }

        $user = $this->userService->getLoggedUser();

        return response()->json( [
            'status' => 'success',
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ] );

    }

    /**
     * Register new user
     *
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register( RegisterRequest $request )
    {

        $params = $request->validated();

        $user = $this->userService->register( $params );

        $token = $this->userService->getUserToken( $user );

        return response()->json( [
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ] );
    }

    /**
     * Logout
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {

        $this->userService->logout();

        return response()->json( [
            'status' => 'success',
            'message' => 'Successfully logged out',
        ] );

    }

    /**
     * Refresh token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {

        return response()->json( [
            'status' => 'success',
            'user' => $this->userService->getLoggedUser(),
            'authorization' => [
                'token' => $this->userService->refreshToken(),
                'type' => 'bearer',
            ]
        ] );

    }

}
