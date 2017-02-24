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

Route::get('/dashboard/record_issuers/{record_issuer}', 'UserRecordIssuerController@show')->name('show_record_issuer');
Route::post('/dashboard/record_issuers', 'UserRecordIssuerController@store');
Route::post('/dashboard/record_issuers/{record_issuer}/records', 'UserRecordIssuerController@store_record')->name('records');
Route::delete('/dashboard/record_issuers/{record_issuer}', 'UserRecordIssuerController@destroy');

Route::get('/dashboard/records/{record}', 'RecordController@show')->name('show_record_file');
Route::get('/dashboard/records/{record}/download', 'RecordController@download')->name('download_record_file');

