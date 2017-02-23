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


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
Route::post('/dashboard/record_issuers', 'UserRecordIssuerController@store');
Route::delete('/dashboard/record_issuers/{record_issuer}', 'UserRecordIssuerController@destroy');
Route::get('/dashboard/record_issuers/{record_issuer}', 'UserRecordIssuerController@show')->name('show_record_issuer');
