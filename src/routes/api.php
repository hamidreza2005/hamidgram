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

Route::get('/', function (Request $request) {
    return asset('storage/2020/default.png');
});

Route::post('/login','AuthController@login')->name('login');
Route::post('/register','AuthController@register')->name('register');

Route::get('/confirmation/emailConfirmation/{code}','AuthController@emailConfirm')->middleware('guest:api')->name('emailConfirmation');

