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

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', ['as' => 'user.logout', 'uses' => 'Login@logout']);

    //Dashboard routes
    Route::post('/dashboard-sum', ['as' => 'dashboard', 'uses' => 'DashboardController@sum']);
    Route::post('/dashboard-saldo', ['as' => 'dashboard', 'uses' => 'DashboardController@saldo']);
    Route::post('/dashboard-pend', ['as' => 'dashboard', 'uses' => 'DashboardController@pend']);

    //Launches routes
    Route::get('/launches-param', ['as' => 'launchs-e', 'uses' => 'TbLaunchController@index'])->middleware('accesses_filial');
    
    Route::get('/query', ['as' => 'query', 'uses' => 'TbLaunchController@query_DataTables']);
});
