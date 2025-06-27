<?php

use Illuminate\Http\Request;

// 获取活动列表
Route::get('admin/activitylist', 'AdminController@activitylist');

// 中奖详情接口
Route::get('admin/activityinfo', 'AdminController@activityinfo');

// 导出中奖数据
Route::get('admin/downloadactivityinfo', 'AdminController@downloadactivityinfo');

// 活动数据下载
Route::get('admin/downloadactivity', 'AdminController@downloadactivity');

// 添加活动数据
Route::post('admin/addactivity', 'AdminController@addactivity');
Route::post('admin/addactivity_dc', 'AdminController@addactivity_dc');
Route::post('admin/addactivity_rt', 'AdminController@addactivity_rt');

// 添加活动数据（逐条）
Route::post('admin/addactivity_every', 'AdminController@addactivity_every');

// 黑名单列表
Route::get('admin/backlist', 'AdminController@backlist');

// 添加黑名单
Route::post('admin/addbacklist', 'AdminController@addbacklist');

// 删除黑名单
Route::post('admin/delbacklist', 'AdminController@delbacklist');

// 获取城市列表
Route::get('admin/city_list', 'AdminController@city_list');

Route::post('admin/city_save', 'AdminController@city_save');


// 住宿人员列表
Route::get('admin/guest_list', 'AdminController@guest_list');
Route::get('admin/designated_room', 'AdminController@designated_room');

// 修改抽奖场次
Route::post('admin/editNext', 'AdminController@editNext');

// 获取城市列表
Route::get('admin/getProduct', 'AdminController@getProduct');
// 获取城市列表
Route::post('admin/saveProduct', 'AdminController@saveProduct');
// 获取城市列表
Route::post('admin/saveProductNew', 'AdminController@saveProductNew');
// 获取城市列表
Route::post('admin/addProduct', 'AdminController@addProduct');
Route::post('admin/deleteProduct', 'AdminController@deleteProduct');
// 上传文件
Route::post('admin/updatepic', 'AdminController@updatepic');
// 重置抽奖
Route::post('admin/resettingDraw', 'AdminController@resettingDraw');
Route::post('admin/saveParze', 'AdminController@saveParze');
Route::post('admin/addCity', 'AdminController@addCity');
Route::post('admin/deletePrize', 'AdminController@deletePrize');


// 高德美月报相关接口
Route::post('admin/addData1', 'AdminController@addData1');
Route::post('admin/addData2', 'AdminController@addData2');
Route::post('admin/addData3', 'AdminController@addData3');
Route::post('admin/addZans', 'AdminController@addZans');
Route::post('admin/getData3', 'AdminController@getData3');
Route::post('admin/getData4', 'AdminController@getData4');
Route::get('admin/getMenus', 'AdminController@getMenus');
Route::post('admin/clearData', 'AdminController@clearData');
Route::post('admin/getUser', 'AdminController@getUser');