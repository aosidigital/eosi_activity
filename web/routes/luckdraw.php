<?php

use Illuminate\Http\Request;

// 注册接口
Route::get('luckdraw/register', 'LuckdrawController@register');

// 获取用户信息
Route::post('luckdraw/getuserinfo', 'LuckdrawController@getuserinfo');

// 获取活动信息
Route::get('luckdraw/getinfo', 'LuckdrawController@getinfo');

// 是否已加入中奖
Route::post('luckdraw/isjoin', 'LuckdrawController@isjoin');

// 添加会员活动记录
Route::post('luckdraw/activitylog', 'LuckdrawController@activitylog');

// 中奖历史
Route::post('luckdraw/list', 'LuckdrawController@list');

// pc抽奖接口
Route::post('luckdraw/operation', 'LuckdrawController@operation');

// pc开始抽奖接口
Route::post('luckdraw/startpc', 'LuckdrawController@startpc');
Route::post('luckdraw/startpc_send', 'LuckdrawController@startpc_send');

// pc开始抽奖接口定采
Route::post('luckdraw/startpc_dc', 'LuckdrawController@startpc_dc');
Route::post('luckdraw/startpc_send_dc', 'LuckdrawController@startpc_send_dc');

// pc开始抽奖接口瑞瑅
Route::post('luckdraw/startpc_rt', 'LuckdrawController@startpc_rt');
Route::post('luckdraw/startpc_send_rt', 'LuckdrawController@startpc_send_rt');

// 退出签到
Route::post('luckdraw/signout', 'LuckdrawController@signout');


//核销奖品
Route::post('luckdraw/exchanged', 'LuckdrawController@exchanged');

//核销奖品
Route::post('luckdraw/download', 'LuckdrawController@download');


// pc开始抽奖接口（新）
Route::post('luckdraw/startpc_new', 'LuckdrawController@startpc_new');


// 验证活动有效性
Route::post('luckdraw/very_activity', 'LuckdrawController@very_activity');

Route::post('luckdraw/selectWinnings', 'LuckdrawController@selectWinnings');

