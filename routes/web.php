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
  Route::get('/login', ['as' => 'login','uses' => 'HomeController@login']);
  Route::get('/register', 'HomeController@register');
  Route::get('/logout', ['uses' => 'Auth\LoginController@logout']);
  Route::get('/verifyemail/{token}', 'Auth\RegisterController@verify');
  Route::get('/verify/{id}', ['as' => 'verify', 'uses' => 'Auth\RegisterController@verify']);
  Route::get('/transaction/success', ['as' => 'success_transaction', 'uses' => 'CRUDController@success']);
  //post
  Route::post('/registration', ['as' => 'user_register', 'uses' => 'Auth\RegisterController@register']);
  Route::post('/login-user',['as' => 'user_login', 'uses' => 'Auth\LoginController@loginProcess']);
  Route::post('/cart/transaction', ['as' => 'cart_transaction', 'uses' => 'CRUDController@transaction']);
  Route::post('/cart', ['as' => 'cart', 'uses' => 'CRUDController@store']);
  Route::post('/update-cart', ['as' => 'update_cart','uses' => 'CRUDController@update']);
});
