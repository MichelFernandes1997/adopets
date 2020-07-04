<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Api\UsersController;
use Api\ProductsController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::resource('users', UsersController::class);

Route::post('login', 'Api\AuthController@login')->name('login');

Route::group(['middleware' => 'JWT'], function ($router) {
    Route::post('logout', 'Api\AuthController@logout')->name('logout');

    Route::get('/products/search/', 'Api\ProductsController@search')->name('products.search');

    Route::resource('products', ProductsController::class);
});
