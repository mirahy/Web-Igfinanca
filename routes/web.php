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


/**
* Routes to acess home page
*========================================================================
*/

Route::get('/',['uses' => 'Controller@homepage']);

/**
* Routes to user auth
*========================================================================
*/
Route::get('/login',['uses' => 'Controller@telalogin']);
Route::post('/login',['as' =>'user.login', 'uses' => 'DashboardController@auth']);
Route::get('/logout',['as' =>'user.logout', 'uses' => 'DashboardController@logout']);
Route::get('/dashboard',['as' =>'dashboard', 'uses' => 'DashboardController@index'])->middleware('auth')->middleware('auth.unique.user');

/**
* Routes return values dashboard
*========================================================================
*/
Route::get('/sum',['as' =>'sum', 'uses' => 'DashboardController@sum'])->middleware('auth');
Route::get('/pend',['as' =>'pend', 'uses' => 'DashboardController@pend'])->middleware('auth');

/**
* Routes to user register
*========================================================================
*/
Route::get('/register',['uses' => 'TbCadUsersController@register']);


/**
* Routes to user forgot-password
*========================================================================
*/
Route::get('/forgot-password',['uses' => 'TbCadUsersController@forgotPassword']);

/**
* Routes to dashboard nav users
*========================================================================
*/
#Route::get('/user',['as' =>'user.index', 'uses' => 'TbCadUsersController@index']);
Route::resource('user', 'TbCadUsersController');
Route::get('/edit-users', ['as' =>'edit-users', 'uses' => 'TbCadUsersController@query_DataTables'])->middleware('auth');
Route::post('/keep', ['as' =>'keep', 'uses' => 'TbCadUsersController@keep']);
Route::post('/show-user', ['as' =>'show-user', 'uses' => 'TbCadUsersController@show_user'])->middleware('auth');
Route::post('/destroy', ['as' =>'destroy', 'uses' => 'TbCadUsersController@destroy'])->middleware('auth');
Route::get('/autocomplete', ['as' =>'autocomplete', 'uses' => 'TbCadUsersController@autocomplete'])->middleware('auth');


/**
* Routes to dashboard nav lauchs and approvals
*========================================================================
*/

/**entries*/
Route::resource('launch', 'TbLaunchController');
Route::get('/launchs-e', ['as' =>'launchs-e', 'uses' => 'TbLaunchController@index'])->middleware('auth');


/**exits */
Route::get('/launchs-s', ['as' =>'launchs-s', 'uses' => 'TbLaunchController@index_s'])->middleware('auth');


/**approvals*/
Route::get('/apr-l', ['as' =>'apr-l', 'uses' => 'TbLaunchController@index_l'])->middleware('auth');
Route::get('/apr-f', ['as' =>'apr-f', 'uses' => 'TbLaunchController@apr_f'])->middleware('auth');
Route::post('/aprov', ['as' =>'aprov', 'uses' => 'TbLaunchController@aprov_id'])->middleware('auth');

/**crud lauchs*/
Route::get('/query', ['as' =>'query', 'uses' => 'TbLaunchController@query_DataTables'])->middleware('auth');
Route::post('/keep-lauch', ['as' =>'keep-lauch', 'uses' => 'TbLaunchController@keep'])->middleware('auth');
Route::post('/show-launch', ['as' =>'show-lauch', 'uses' => 'TbLaunchController@show_launch'])->middleware('auth');
Route::post('/destroy-launch', ['as' =>'destroy-launch', 'uses' => 'TbLaunchController@destroy'])->middleware('auth');


/**
* Routes to dashboard nav reports
*========================================================================
*/

Route::get('/reports-f', ['as' =>'reports-f', 'uses' => 'TbLaunchController@index_reports'])->middleware('auth');
//Route::get('/closingPDF', ['as' =>'closingPDF', 'uses' => 'PdfController@closing_pdf'])->middleware('auth');
Route::post('/closingPDF', ['as' =>'closingPDF', 'uses' => 'PdfController@closing_pdf'])->middleware('auth');


/**
* Routes to dashboard nav closing
*========================================================================
*/
/**Closings */
Route::get('/closing', ['as' =>'closing', 'uses' => 'TbClosingsController@index'])->middleware('auth');

/**crud closing*/
Route::get('/query-closing', ['as' =>'query-closing', 'uses' => 'TbClosingsController@query_DataTables'])->middleware('auth');
Route::post('/keep-closing', ['as' =>'keep-closing', 'uses' => 'TbClosingsController@keep'])->middleware('auth');
Route::post('/show-closing', ['as' =>'show-closing', 'uses' => 'TbClosingsController@show_closing'])->middleware('auth');
Route::post('/destroy-closing', ['as' =>'destroy-closing', 'uses' => 'TbClosingsController@destroy'])->middleware('auth');