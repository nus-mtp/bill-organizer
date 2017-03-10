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


Route::get('/','HomeController@show' );

// remove later
Route::get('/upload', function () {
    return view('modules.upload');
});

// remove later and replace with record-specific urls later
Route::get('/edit', function () {
    return view('dashboard.editrecord');
});


Auth::routes(); // includes routes for login, register, forget password

// only authenticated user can access this route group
Route::group(['prefix'=>'dashboard', 'middleware' => 'auth'], function () {

  Route::get('/', 'DashboardController@index')->name('dashboard');

  Route::get('/record_issuers/{record_issuer}', 'UserRecordIssuerController@show')->name('show_record_issuer');
  Route::post('/record_issuers', 'UserRecordIssuerController@store');
  Route::post('/record_issuers/{record_issuer}/records', 'UserRecordIssuerController@store_record')->name('records');
  Route::delete('/record_issuers/{record_issuer}', 'UserRecordIssuerController@destroy');

  Route::get('/records/{record}', 'RecordController@show')->name('show_record_file');
  Route::get('/records/{record}/download', 'RecordController@download')->name('download_record_file');
  Route::delete('/records/{record}', 'RecordController@destroy')->name('delete_record_file');
  Route::get('/records/{record}/edit', 'RecordController@edit')->name('edit_record'); // show form for edit record
  Route::patch('/records/{record}', 'RecordController@update')->name('update_record'); // update record in database

});
