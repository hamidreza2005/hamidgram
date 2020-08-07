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
    dd(auth()->user());
    return ['ok'];
})->middleware('auth:api');

// Auth Routes
Route::post('/login','AuthController@login')->name('login');
Route::post('/register','AuthController@register')->name('register');
Route::post('/password/reset','AuthController@resetPassword')->name('resetPassword')->middleware('throttle:1,10');
Route::post('/logout','AuthController@logout')->name('logout')->middleware('auth:api');
Route::get('/@{username}','UserController@profile')->name('show.profile');
Route::group(['prefix'=>'confirmation'],function (){
    Route::get('/emailConfirmation/{code}','AuthController@emailConfirm')->name('emailConfirmation');
    Route::get('/twostepverification/{code}','AuthController@twoStepVerification')->name('twoStepVerification');
});

Route::group(['prefix'=>'posts'],function (){
    Route::post('/','PostController@add')->name('add.post');
    Route::delete('/{id}','PostController@remove')->name('remove.post');
    Route::get('/{id}','PostController@view')->name('show.post');
    Route::put('/{id}','PostController@update')->name('update.post');
    Route::get('/getcomments/{id}','PostController@getComments')->name('show.comments.for.posts');
});

Route::group(['prefix'=>'comments'],function (){
   Route::post('/{postId}/{parentId?}',"CommentController@add")->name('add.comment');
   Route::delete('/{commentId}',"CommentController@remove")->name('remove.comment');
   Route::put('/{commentId}',"CommentController@update")->name('update.comment');
   Route::get('/{commentId}',"CommentController@view")->name('show.comment');
});

Route::group(['prefix'=>'report'],function (){
   Route::post('/{postId}','ReportController@index')->name('report post');
});
