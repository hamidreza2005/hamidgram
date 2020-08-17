<?php

use Illuminate\Support\Facades\Route;

// Auth Routes
Route::post('/login','AuthController@login')->name('login');
Route::post('/register','AuthController@register')->name('register');
Route::post('/password/reset','AuthController@resetPassword')->name('resetPassword')->middleware('throttle:1,10');
Route::post('/logout','AuthController@logout')->name('logout')->middleware('auth:api');
Route::get('/@{username}','UserController@profile')->name('show.profile');
Route::get('/explore','HomeController@explore')->name('explore');
Route::group(['prefix'=>'confirmation'],function (){
    Route::get('/emailConfirmation/{code}','AuthController@emailConfirm')->name('emailConfirmation');
    Route::get('/twostepverification/{code}','AuthController@twoStepVerification')->name('twoStepVerification');
});

Route::group(['prefix'=>'users'],function (){
   Route::delete('/deleteaccount',"UserController@delete")->name('delete.account');
   Route::get('/getnotifications/unread',"UserController@getUnreadNotifications")->name('get.unread.notifications');
   Route::get('/getnotifications/all',"UserController@getAllNotifications")->name('get.all.notifications');
   Route::post('/change_profile_picture',"UserController@changeProfilePicture")->name('change.profile.picture')->middleware('throttle:2,10');
   Route::get('/history',"UserController@history")->name('user.history');
   Route::put('/edit/profile',"UserController@editProfile")->name('user.edit.profile');
   Route::put('/edit/settings',"UserController@editSettings")->name('user.edit.settings');
   Route::post('/follow/{userId}',"UserController@follow")->name('follow.user');
   Route::delete('/unfollow/{userId}',"UserController@unfollow")->name('unfollow.user');
   Route::get('/search',"UserController@search")->name('search.user');
});

Route::group(['prefix'=>'posts'],function (){
    Route::post('/','PostController@add')->name('add.post');
    Route::delete('/{id}','PostController@remove')->name('remove.post');
    Route::get('/{id}','PostController@view')->name('show.post');
    Route::put('/{id}','PostController@update')->name('update.post');
    Route::get('/getcomments/{id}','PostController@getComments')->name('show.comments.for.posts');
    Route::post('/like/{postId}','PostController@like')->name('like.post');
    Route::post('/like/retake/{postId}','PostController@retakeLike')->name('retake.like');
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

Route::group(['prefix'=>'admin'],function (){
   Route::delete('/users/delete/{userId}','AdminController@deleteUser')->name('delete.user.by.admin');
   Route::delete('/posts/delete/{postId}','AdminController@deletepost')->name('delete.post.by.admin');
   Route::put('/users/edit/type/{userId}','AdminController@raiseUser')->name('edit.user.type.by.admin');
   Route::put('/users/edit/profile/{userId}','AdminController@editProfile')->name('edit.user.profile.by.admin');
   Route::put('/users/edit/setting/{userId}','AdminController@editSetting')->name('edit.user.setting.by.admin');
   Route::put('/set/config','AdminController@SetConfig')->name('set.config.by.admin');
});
