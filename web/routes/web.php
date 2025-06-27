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

Route::get('/', function () {
    return view('welcome');
});

// 用户注册接口
Route::get('luckdraw/register', 'LuckdrawController@register');

// 发送验证码接口
Route::get('luckdraw/send_code', 'LuckdrawController@send_code');

