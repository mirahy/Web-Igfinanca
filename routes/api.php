<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/login', ['as' => 'user.login', 'uses' => 'Login@auth']);
Route::get('/logout', ['as' => 'user.logout', 'uses' => 'Login@logout']);

Route::middleware(['auth.unique.user', 'auth', 'auth_session','auth:sanctum', 'reconnect'])->group(function () {
    Route::get('/dashboard', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);
});
