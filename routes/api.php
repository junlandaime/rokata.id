<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Ecommerce\CartController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('city', 'App\Http\Controllers\Ecommerce\CartController@getCity');
Route::get('city', [CartController::class, 'getCity']);
Route::get('district', 'App\Http\Controllers\Ecommerce\CartController@getDistrict');
Route::post('cost', 'App\Http\Controllers\Ecommerce\CartController@getCourier');

