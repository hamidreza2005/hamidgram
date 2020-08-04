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
//    \Illuminate\Support\Facades\Cache::put('user',\App\User::find(1));
    return ['ok'];
});

// Auth Routes
Route::post('/login','AuthController@login')->name('login');
Route::post('/register','AuthController@register')->name('register');
Route::get('/confirmation/emailConfirmation/{code}','AuthController@emailConfirm')->name('emailConfirmation');
Route::post('/password/reset','AuthController@resetPassword')->name('resetPassword')->middleware('throttle:1,10');

//Route::group(['prefix'=>'posts'],function (){
//    Route::post('/add','PostController@add');
//});
