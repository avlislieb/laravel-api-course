<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v1', 'namespace' => 'Api\v1'], function () {
    Route::get('categories/{id}/products', 'CategoryController@products');
    Route::apiResource('categories', 'CategoryController');
    Route::apiResource('products', 'ProductController');
});

