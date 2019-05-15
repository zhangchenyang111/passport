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
	phpinfo();
    //return view('welcome');
});
//Route::any('rester','Index@rester');
//Route::any('phpinfo','Index@phpinfo');

//Route::any('loginToken','Index@loginToken');
//
//
//Route::resource('goods',GoodsController::class);
Route::any('login','Index@login');
Route::any('logindo','Index@logindo');
