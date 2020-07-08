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

Auth::routes();

Route::resource('categories','CategoryController')->middleware('auth');
Route::resource('products','ProductController')->middleware('auth');
