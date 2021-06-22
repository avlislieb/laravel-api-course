<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*
Route::get('categories', 'Api\CategoryController@index');
Route::post('categories', 'Api\CategoryController@store');
Route::put('categories/{id}', 'Api\CategoryController@update');
Route::delete('categories/{id}', 'Api\CategoryController@delete');
*/
Route::apiResource('categories', 'Api\CategoryController');
Route::apiResource('products', 'Api\ProductController');
