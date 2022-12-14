<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller( AuthController::class )->group( function () {

    Route::post( 'login', 'login' );
    Route::post( 'register', 'register' );
    Route::post( 'logout', 'logout' )->middleware( 'auth:api' );
    Route::post( 'refresh', 'refresh' )->middleware( 'auth:api' );

} );

Route::controller( ProjectController::class )->middleware( 'auth:api' )->group( function () {

    Route::get( 'projects', 'index' );
    Route::post( 'projects', 'store' );
    Route::get( 'projects/{project}', 'show' );
    Route::patch( 'projects/{project}', 'update' );
    Route::patch( 'projects/{project}/{status}', 'changeStatus' )->whereIn( 'status', \App\Enums\Status::getValues() );

} );

Route::controller( TaskController::class )->middleware( 'auth:api' )->group( function () {

    Route::get( 'projects/{project}/tasks', 'index' );
    Route::post( 'projects/{project}/tasks', 'store' );
    Route::get( 'projects/{project}/tasks/{task}', 'show' );
    Route::patch( 'projects/{project}/tasks/{task}', 'update' );
    Route::patch( 'projects/{project}/tasks/{task}/{status}', 'changeStatus' )->whereIn( 'status', \App\Enums\TaskStatus::getValues() );

} );

Route::fallback( function () {

    return response()->json( [
        'message' => 'Invalid URL.'
    ], 404 );

} );
