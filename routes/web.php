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
    return view('index');
});
Route::group(['middleware' => 'web'], function () {
  Route::get('/', ['as' => 'giftcard','uses' => 'HomeController@index']);
  Route::get('/clear-cart',['as' => 'clear_cart','uses' => 'HomeController@clearCart']);
  Route::get('/delete-cart/{id}',['as' => 'delete_cart','uses' => 'HomeController@deleteCart']);
  Route::get('/edit-cart/{id}', ['as' => 'edit_cart','uses' => 'HomeController@edit']);
  Route::get('/confirm', 'HomeController@confirm');
  //post
  Route::post('/cart', ['as' => 'cart', 'uses' => 'HomeController@store']);
});
