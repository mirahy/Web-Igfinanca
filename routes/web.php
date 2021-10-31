<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//garantindo que as rotas abaixo somente acessem a base matriz atravez da middlaware reconnectdbdefault
Route::middleware(['reconnectdbdefault'])->group(function () {

    /**
     * Routes to acess home page
     *========================================================================
     */
    Route::get('/', ['uses' => 'Controller@homepage']);


    /**
     * Routes to user auth
     *========================================================================
     */
    Route::get('/login', ['uses' => 'Controller@telalogin']);
    Route::post('/login', ['as' => 'user.login', 'uses' => 'DashboardController@auth']);
    Route::get('/logout', ['as' => 'user.logout', 'uses' => 'DashboardController@logout']);

    /**
     * Routes to user register
     *========================================================================
     */
    Route::get('/register', ['uses' => 'TbCadUsersController@register']);


    /**
     * Routes to user forgot-password
     *========================================================================
     */
    Route::get('/forgot-password', ['uses' => 'TbCadUsersController@forgotPassword']);


    //Init group unique users route and user authenticated
    Route::middleware(['auth.unique.user', 'auth'])->group(function () {

        /**
         * Routes to dashboard nav users
         *========================================================================
         */
        #Route::get('/user',['as' =>'user.index', 'uses' => 'TbCadUsersController@index']);
        Route::resource('user', 'TbCadUsersController');
        Route::get('/edit-users', ['as' => 'edit-users', 'uses' => 'TbCadUsersController@query_DataTables']);
        Route::post('/keep', ['as' => 'keep', 'uses' => 'TbCadUsersController@keep']);
        Route::post('/show-user', ['as' => 'show-user', 'uses' => 'TbCadUsersController@show_user']);
        Route::post('/destroy', ['as' => 'destroy', 'uses' => 'TbCadUsersController@destroy']);
        Route::get('/autocomplete', ['as' => 'autocomplete', 'uses' => 'TbCadUsersController@autocomplete']);


        /**
         * Routes to dashboard nav closing
         *========================================================================
         */
        /**Closings */
        Route::get('/closing', ['as' => 'closing', 'uses' => 'TbClosingsController@index']);

        /**crud closing*/
        Route::get('/query-closing', ['as' => 'query-closing', 'uses' => 'TbClosingsController@query_DataTables']);
        Route::post('/keep-closing', ['as' => 'keep-closing', 'uses' => 'TbClosingsController@keep']);
        Route::post('/show-closing', ['as' => 'show-closing', 'uses' => 'TbClosingsController@show_closing']);
        Route::post('/destroy-closing', ['as' => 'destroy-closing', 'uses' => 'TbClosingsController@destroy']);

        /**Finish group route base matriz authenticated */
    });

    /**Finish group route base matriz */
});


//Init group unique users route, user autenticated and reconnect data base seletion on login
Route::middleware(['auth.unique.user', 'auth', 'reconnect'])->group(function () {
    Route::get('/dashboard', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);

    /**
     * Routes return values dashboard
     *========================================================================
     */
    Route::get('/sum', ['as' => 'sum', 'uses' => 'DashboardController@sum']);
    Route::get('/init', ['as' => 'init', 'uses' => 'DashboardController@saldo']);
    Route::get('/balance', ['as' => 'balance', 'uses' => 'DashboardController@saldo']);
    Route::get('/pend', ['as' => 'pend', 'uses' => 'DashboardController@pend']);


    /**
     * Routes to dashboard nav lauchs and approvals
     *========================================================================
     */

    /**entries*/
    Route::resource('launch', 'TbLaunchController');
    Route::get('/launchs-e', ['as' => 'launchs-e', 'uses' => 'TbLaunchController@index']);


    /**exits */
    Route::get('/launchs-s', ['as' => 'launchs-s', 'uses' => 'TbLaunchController@index_s']);


    /**consult launches */
    Route::get('/launchs-cl', ['as' => 'launchs-cl', 'uses' => 'TbLaunchController@index_cl']);


    /**approvals*/
    Route::get('/apr-l', ['as' => 'apr-l', 'uses' => 'TbLaunchController@index_l']);
    Route::get('/apr-f', ['as' => 'apr-f', 'uses' => 'TbLaunchController@apr_f']);
    Route::post('/aprov', ['as' => 'aprov', 'uses' => 'TbLaunchController@aprov_id']);

    /**crud lauches*/
    Route::get('/query', ['as' => 'query', 'uses' => 'TbLaunchController@query_DataTables']);
    Route::post('/keep-lauch', ['as' => 'keep-lauch', 'uses' => 'TbLaunchController@keep']);
    Route::post('/show-launch', ['as' => 'show-lauch', 'uses' => 'TbLaunchController@show_launch']);
    Route::post('/destroy-launch', ['as' => 'destroy-launch', 'uses' => 'TbLaunchController@destroy']);


    /**
     * Routes to dashboard nav reports
     *========================================================================
     */

    Route::get('/reports-f', ['as' => 'reports-f', 'uses' => 'TbLaunchController@index_reports']);
    //Route::get('/closingPDF', ['as' =>'closingPDF', 'uses' => 'PdfController@closing_pdf']);
    Route::post('/closingPDF', ['as' => 'closingPDF', 'uses' => 'PdfController@closing_pdf']);



    /**Finish group route base local authenticated */

    /**Test page */

    Route::get('/teste', function () {
        return view('teste');
    });

    /**Finish test page */
});
