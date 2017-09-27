<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::get('/welcome',function (){
   return view('welcome');
});
Route::get('/category','View\BookController@toCategory');
Route::get('/login','View\MemberController@toLogin');
Route::get('/register', 'View\MemberController@toRegister');
Route::get('/service/validateCode', 'Service\ValidateController@create');
Route::get('/service/validate_phone/send','Service\ValidateController@sendSms');
Route::post('/service/register','Service\MemberController@register');
Route::get('/service/validate_email','Service\ValidateController@validateEmail');
Route::post('/service/login','Service\MemberController@login');
Route::get('/category/parent_id/{parent_id}','Service\BookController@getCategoryByParentId');
Route::get('/product/category_id/{category_id}', 'View\BookController@toProduct');
Route::get('/pdt_detail/product_id/{product_id}', 'View\BookController@getProductById');
Route::get('/add_cart/product_id/{product_id}', 'Service\CartController@addCart');
Route::get('/cart', 'View\CartController@toCart');
