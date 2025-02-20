<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'StaticPagesController@home')->name('home');
Route::get('/help', 'StaticPagesController@help')->name('help');
Route::get('/about', 'StaticPagesController@about')->name('about');

Route::get('signup', 'UsersController@create')->name('signup');
Route::resource('users', 'UsersController');

// 展示登录表单
Route::get('login', 'SessionsController@create')->name('login');
// 创建新会话（登录）
Route::post('login', 'SessionsController@store')->name('login');
// 销毁会话 (退出登录)
Route::delete('logout', 'SessionsController@destroy')->name('logout');
// http://lravel-blog.local/signup/confirm/{token}
Route::get('signup/confirm/{token}', 'UsersController@confirmEmail')->name('confirm_email');

// 展示重置密码的邮箱发送页面
Route::get('password/reset', 'PasswordController@showLinkRequestForm')->name('password.request');
// 提交邮箱地址，发送重置密码邮件
Route::post('password/email', 'PasswordController@sendResetLinkEmail')->name('password.email');
// 显示更新密码的表单
Route::get('password/reset/{token}', 'PasswordController@showResetForm')->name('password.reset');
// 提交更新密码的表单, 重置密码
Route::post('password/reset', 'PasswordController@reset')->name('password.update');
// 微博相关操作
Route::resource('statuses', 'StatusesController', ['only' => ['store', 'destroy']]);
