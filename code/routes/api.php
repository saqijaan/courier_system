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
Route::post('/v1/login','ApiController@login');
Route::post('/v1/register','ApiController@registerUser');

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/v1/users','ApiController@users');
    Route::post('/v1/cities','ApiController@getcitiese');
    Route::post('/v1/prices','ApiController@priceList');
    Route::post('/v1/shipments','ApiController@shipmentList');
    Route::post('/v1/user','ApiController@user');
});