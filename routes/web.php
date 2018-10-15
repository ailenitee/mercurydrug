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
  Route::get('/clear-cart',['as' => 'clear_cart','uses' => 'CRUDController@clearCart']);
  Route::get('/delete-cart/{id}',['as' => 'delete_cart','uses' => 'CRUDController@deleteCart']);
  Route::get('/edit-cart/{id}', ['as' => 'edit_cart','uses' => 'HomeController@edit']);
  Route::get('/confirm', 'HomeController@confirm');
  Route::get('/checkout', 'HomeController@checkout');
  Route::post('/cart/transaction', ['as' => 'cart_transaction', 'uses' => 'CardController@transaction']);
  //post
  Route::post('/cart', ['as' => 'cart', 'uses' => 'CRUDController@store']);
  Route::post('/update-cart', ['as' => 'update_cart','uses' => 'CRUDController@update']);
});
