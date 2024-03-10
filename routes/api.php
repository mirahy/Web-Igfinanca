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

//garantindo que as rotas abaixo somente acessem a base matriz atravez da middlaware reconnectdbdefault
Route::middleware(['reconnectdbdefault'])->group(function () {

    Route::post('/login', ['as' => 'user.login', 'uses' => 'Login@auth']);

    Route::middleware(['auth_unique_user_api', 'auth', 'auth_session', 'auth:sanctum'])->group(function () {
        Route::post('/logout', ['as' => 'user.logout', 'uses' => 'Login@logout']);

        /**
         * Routes to users
         *========================================================================
         */
        /**crud user*/
        Route::get('/form-user', ['as' => 'users-p', 'uses' => 'TbCadUsersController@param'])->middleware('accesses_matriz');
        Route::get('/query-user', ['as' => 'query', 'uses' => 'TbCadUsersController@query'])->middleware('accesses_matriz');
        Route::post('/keep-user', ['as' => 'keep', 'uses' => 'TbCadUsersController@keep'])->middleware('accesses_matriz');
        Route::post('/show-user', ['as' => 'show-user', 'uses' => 'TbCadUsersController@show_user'])->middleware('accesses_matriz');
        Route::delete('/destroy-user', ['as' => 'destroy', 'uses' => 'TbCadUsersController@destroy'])->middleware('accesses_matriz');

        /**
         * Routes to dashboard nav closing
         *========================================================================
         */

        /**crud closing*/
        Route::get('/form-closing', ['as' => 'users-p', 'uses' => 'TbClosingsController@param'])->middleware('accesses_matriz');
        Route::get('/query-closing', ['as' => 'query-closing', 'uses' => 'TbClosingsController@query'])->middleware('accesses_matriz');
        Route::post('/keep-closing', ['as' => 'keep-closing', 'uses' => 'TbClosingsController@keep'])->middleware('accesses_matriz');
        Route::post('/show-closing', ['as' => 'show-closing', 'uses' => 'TbClosingsController@show_closing'])->middleware('accesses_matriz');
        Route::delete('/destroy-closing', ['as' => 'destroy-closing', 'uses' => 'TbClosingsController@destroy'])->middleware('accesses_matriz');

    });
    /**Finish group route base matriz authenticated */
});
/**Finish group route base matriz */


//Init group unique users route, user autenticated and reconnect data base seletion on login
Route::middleware(['auth_unique_user_api', 'auth:sanctum', 'reconnect'])->group(function () {

    //Dashboard routes
    Route::post('/dashboard-sum', ['as' => 'dashboard', 'uses' => 'DashboardController@sum']);
    Route::post('/dashboard-saldo', ['as' => 'dashboard', 'uses' => 'DashboardController@saldo']);
    Route::post('/dashboard-pend', ['as' => 'dashboard', 'uses' => 'DashboardController@pend']);

    /**crud lauches*/
    Route::get('/form-launch', ['as' => 'launchs-p', 'uses' => 'TbLaunchController@param'])->middleware('accesses_filial');
    Route::get('/query-launch', ['as' => 'query', 'uses' => 'TbLaunchController@query_DataTables']);
    Route::post('/keep-lauch', ['as' => 'keep-lauch', 'uses' => 'TbLaunchController@keep'])->middleware('accesses_filial');
    Route::post('/show-launch', ['as' => 'show-lauch', 'uses' => 'TbLaunchController@show_launch']);
    Route::delete('/destroy-launch', ['as' => 'destroy-launch', 'uses' => 'TbLaunchController@destroy'])->middleware('accesses_filial');
    /**approvals*/
    Route::post('/aprov-launch', ['as' => 'aprov', 'uses' => 'TbLaunchController@aprov_id'])->middleware('accesses_filial');
});
/**Finish group route base local authenticated */


