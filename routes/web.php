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
    Route::middleware(['auth.unique.user', 'auth', 'auth_session'])->group(function () {

        /**
         * Routes to dashboard nav users
         *========================================================================
         */
        #Route::get('/user',['as' =>'user.index', 'uses' => 'TbCadUsersController@index']);
        Route::resource('user', 'TbCadUsersController');
        Route::get('/edit-users', ['as' => 'edit-users', 'uses' => 'TbCadUsersController@query_DataTables'])->middleware('accesses_matriz');
        Route::post('/keep', ['as' => 'keep', 'uses' => 'TbCadUsersController@keep'])->middleware('accesses_matriz');
        Route::post('/show-user', ['as' => 'show-user', 'uses' => 'TbCadUsersController@show_user'])->middleware('accesses_matriz');
        Route::post('/destroy', ['as' => 'destroy', 'uses' => 'TbCadUsersController@destroy'])->middleware('accesses_matriz');



        /**
         * Routes to dashboard nav closing
         *========================================================================
         */
        /**Closings */
        Route::get('/closing', ['as' => 'closing', 'uses' => 'TbClosingsController@index'])->middleware('accesses_matriz');

        /**crud closing*/
        Route::get('/query-closing', ['as' => 'query-closing', 'uses' => 'TbClosingsController@query_DataTables'])->middleware('accesses_matriz');
        Route::post('/keep-closing', ['as' => 'keep-closing', 'uses' => 'TbClosingsController@keep'])->middleware('accesses_matriz');
        Route::post('/show-closing', ['as' => 'show-closing', 'uses' => 'TbClosingsController@show_closing'])->middleware('accesses_matriz');
        Route::post('/destroy-closing', ['as' => 'destroy-closing', 'uses' => 'TbClosingsController@destroy'])->middleware('accesses_matriz');

        /**approvals*/
        Route::get('/apr-f', ['as' => 'apr-f', 'uses' => 'TbLaunchController@apr_f'])->middleware('accesses_matriz');


        /**
         * Routes to dashboard nav roles
         *========================================================================
         */

        /**Roles */
        Route::get('/roles', ['as' => 'roles', 'uses' => 'RoleController@index'])->middleware('accesses_matriz');

        /**crud roles*/
        Route::get('/query-roles', ['as' => 'query-roles', 'uses' => 'RoleController@query_DataTables'])->middleware('accesses_matriz');
        Route::post('/keep-roles', ['as' => 'keep-roles', 'uses' => 'RoleController@keep'])->middleware('accesses_matriz');
        Route::post('/show-roles', ['as' => 'show-roles', 'uses' => 'RoleController@show_roles'])->middleware('accesses_matriz');
        Route::post('/destroy-roles', ['as' => 'destroy-roles', 'uses' => 'RoleController@destroy'])->middleware('accesses_matriz');

        /**
         * Routes to dashboard nav roles
         *========================================================================
         */

        /**Roles */
        Route::get('/permission', ['as' => 'permission', 'uses' => 'PermissionController@index'])->middleware('accesses_matriz');

        /**crud roles*/
        Route::get('/query-permission', ['as' => 'query-permission', 'uses' => 'PermissionController@query_DataTables'])->middleware('accesses_matriz');
        Route::post('/keep-permission', ['as' => 'keep-permission', 'uses' => 'PermissionController@keep'])->middleware('accesses_matriz');
        Route::post('/show-permission', ['as' => 'show-permission', 'uses' => 'PermissionController@show_PermissionController'])->middleware('accesses_matriz');
        Route::post('/destroy-permission', ['as' => 'destroy-permission', 'uses' => 'PermissionController@destroy'])->middleware('accesses_matriz');

        /**
         * Routes to user forgot-password
         *========================================================================
         */
        Route::get('/log', ['uses' => 'LogsController@index'])->middleware('accesses_matriz');
    });
    /**Finish group route base matriz authenticated */
});
/**Finish group route base matriz */


//Init group unique users route, user autenticated and reconnect data base seletion on login
Route::middleware(['auth.unique.user', 'auth', 'auth_session', 'reconnect'])->group(function () {
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
    Route::get('/launchs-e', ['as' => 'launchs-e', 'uses' => 'TbLaunchController@index'])->middleware('accesses_filial');


    /**exits */
    Route::get('/launchs-s', ['as' => 'launchs-s', 'uses' => 'TbLaunchController@index_s'])->middleware('accesses_filial');


    /**consult launches */
    Route::get('/launchs-cl', ['as' => 'launchs-cl', 'uses' => 'TbLaunchController@index_cl']);


    /**approvals*/
    Route::get('/apr-l', ['as' => 'apr-l', 'uses' => 'TbLaunchController@index_l'])->middleware('accesses_filial');
    Route::post('/aprov', ['as' => 'aprov', 'uses' => 'TbLaunchController@aprov_id'])->middleware('accesses_filial');

    /**crud lauches*/
    Route::get('/query', ['as' => 'query', 'uses' => 'TbLaunchController@query_DataTables']);
    Route::post('/keep-lauch', ['as' => 'keep-lauch', 'uses' => 'TbLaunchController@keep'])->middleware('accesses_filial');
    Route::post('/show-launch', ['as' => 'show-lauch', 'uses' => 'TbLaunchController@show_launch']);
    Route::post('/destroy-launch', ['as' => 'destroy-launch', 'uses' => 'TbLaunchController@destroy'])->middleware('accesses_filial');

    /**return name user as input name for modal of launchs*/
    Route::get('/autocomplete', ['as' => 'autocomplete', 'uses' => 'TbCadUsersController@autocomplete']);


    /**
     * Routes to dashboard nav reports
     *========================================================================
     */

    Route::get('/reports-f', ['as' => 'reports-f', 'uses' => 'TbLaunchController@index_reports']);
    //Route::get('/closingPDF', ['as' =>'closingPDF', 'uses' => 'PdfController@closing_pdf']);
    Route::post('/closingPDF', ['as' => 'closingPDF', 'uses' => 'PdfController@closing_pdf']);





    /**Test page */

    Route::get('/teste', function () {
        return view('teste');
    });

    /**Finish test page */
});

/**Finish group route base local authenticated */
