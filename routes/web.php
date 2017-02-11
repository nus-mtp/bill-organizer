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

Route::get('/.well-known/acme-challenge/ZTqFS_a5ruqk8hnVCb5TthYe-LSkbDPy3H9PGGJ7X2E', function () {
    $secret = "ZTqFS_a5ruqk8hnVCb5TthYe-LSkbDPy3H9PGGJ7X2E.Ewzm5RMr5gOrbAk0Z-UdHy9LSNh4zxU0bnqgnH_oYiU";
    return File::get("/.well-known/acme-challenge/ZTqFS_a5ruqk8hnVCb5TthYe-LSkbDPy3H9PGGJ7X2E");
});
