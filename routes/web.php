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


// Route::get('/', function () {
//     return view('welcome');
// })->middleware('guest');


Route::get('/','HomeController@show' )->middleware('guest');

// remove later
Route::get('/upload', function () {
    return view('modules.upload');
});

// remove later and replace with record-specific urls later
Route::get('/edit', function () {
    return view('dashboard.editrecord');
});


/**
 * Below is an expansion of Auth::routes(). Since we want to disable a few of the built-in routes, I'll just expand
 * the routes manually here
 */
// Authentication Routes...
//Route::get('login', 'Auth\LoginController@showLoginForm')
Route::post('login', 'Auth\LoginController@login')->name('login');;
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
//Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register')->name('register');;

// Password Reset Routes...
//Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
//Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
//Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
//Route::post('password/reset', 'Auth\ResetPasswordController@reset');

// only authenticated user can access this route group
Route::group(['prefix'=>'dashboard', 'middleware' => 'auth'], function () {

    Route::get('/', 'DashboardController@index')->name('dashboard');

    Route::get('/record_issuers/{record_issuer}', 'RecordIssuerController@show')->name('show_record_issuer');
    Route::post('/record_issuers', 'RecordIssuerController@store');
    Route::post('/record_issuers/{record_issuer}/upload_record', 'RecordIssuerController@upload_record_file')->name('upload_record_file');
    Route::delete('/record_issuers/{record_issuer}', 'RecordIssuerController@destroy');

    Route::get('/records/{record}', 'RecordController@show')->name('show_record_file');
    Route::get('/records/{record}/download', 'RecordController@download')->name('download_record_file');
    Route::delete('/records/{record}', 'RecordController@destroy')->name('delete_record_file');
//    Route::get('/records/{record}/edit', 'RecordController@edit')->name('edit_record'); // show form for edit record
//    Route::put('/records/{record}', 'RecordController@update')->name('update_record'); // update record in database

    Route::get('/records/{record}/template', 'RecordController@add_template')->name('add_template');
    Route::post('/records/{record}/template', 'RecordController@store_template')->name('store_template');
    Route::post('/records/{record}/values', 'RecordController@confirm_values')->name('confirm_values');

    Route::get('/record_pages/{record_page}', 'RecordPageController@show')->name('show_record_page');
});

// stats routes
Route::group(['prefix'=>'stats', 'middleware' => 'auth'], function () {
    Route::get('/{record_issuer}', [
        'as'         => 'showAllTimeStats',
        'uses'     => 'StatsController@index'
    ]);

    Route::get('/{record_issuer}/{month}', [
        'as'         => 'showAllTimeStats',
        'uses'     => 'StatsController@show'
    ]);
});
