<?php

use Illuminate\Support\Facades\Route;

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
    return view('category');
})->middleware('auth')->name('home');

//Auth::routes();
Route::get('/login','Auth\LoginController@showLoginForm')->name('login');
Route::post('/login','Auth\LoginController@login');
Route::post('/logout','Auth\LoginController@logout')->name('logout')->middleware('auth');

Route::resource('categories','CategoryController')->middleware('auth');
Route::get('/categories_all','CategoryController@getAll')->middleware('auth');
Route::resource('products','ProductController')->middleware('auth');
Route::post('update_product/{product}','ProductController@update')->middleware('auth');
