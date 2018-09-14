<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//直播相关
Route::group(["namespace" => 'Live'], function () {
    Route::any("/", function () {
        return redirect('/index.html');
    });
    Route::get('/index.html',"LiveController@lives");//首页
    Route::get('/match',"LiveController@live");//首页
    Route::get('/bj', 'LiveController@bj');//设置播放器活动
    Route::any('/bj2', 'LiveController@bj2');//设置播放器活动
});

Route::get('/normal','NormalTalkController@index');
Route::post('/normal/add','NormalTalkController@add');

Route::get('/post', 'NormalTalkController@add');//设置播放器活动
Route::get('/chat', 'NormalTalkController@index');//设置播放器活动

Route::get('/forspider/article/{id}', 'Article\ArticleController@detailHTML');

Route::get('/news/', 'Article\ArticleController@index');

