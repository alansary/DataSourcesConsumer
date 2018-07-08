<?php

use Illuminate\Http\Request;

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

Route::group(['prefix' => 'data_sources'], function() {
	Route::post('create', 'DataSourcesController@create');
	Route::get('all', 'DataSourcesController@all');
});

Route::group(['prefix' => 'consumers'], function() {
	Route::get('getProducts', 'ConsumersController@getProducts');
});