<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\AuthController;

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

Route::prefix('v1')->group(function() {
    Route::prefix('auth')->group(function() {
        Route::post('/signin', 'App\Http\Controllers\API\AuthController@signin')->name('signin');
        Route::post('/signup', 'App\Http\Controllers\API\AuthController@signup')->name('signup');
        Route::get('/signout', 'App\Http\Controllers\API\AuthController@signout')->name('signout');
    });

    Route::group(['middleware' => ['auth:api'], 'prefix' => 'book'], function() {
        Route::get('/', 'App\Http\Controllers\API\BookController@index')->name('ShowAllBooks');
        Route::post('/add', 'App\Http\Controllers\API\BookController@store')->name('AddNewBooks');
        Route::get('/show/{id}', 'App\Http\Controllers\API\BookController@show')->name('ShowBooksByID');
        Route::post('/edit/{id}', 'App\Http\Controllers\API\BookController@edit')->name('EditBooks');
        Route::get('/delete/{id}', 'App\Http\Controllers\API\BookController@destroy')->name('DeleteBooks');
    });
});