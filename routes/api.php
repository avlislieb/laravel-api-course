<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group([
    'prefix' => 'v1',
    'namespace' => 'Api\v1',
    'middleware' => 'auth:api',
], function () {

    Route::get('categories/{id}/products', 'CategoryController@products');
    Route::apiResource('categories', 'CategoryController');
    Route::apiResource('products', 'ProductController');
});

Route::post('auth', 'Auth\AuthApiController@authenticate');
Route::post('me', 'Auth\AuthApiController@getAuthenticatedUser');
Route::post('auth-refresh', 'Auth\AuthApiController@refreshToken');
