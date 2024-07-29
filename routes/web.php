<?php

use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LiveChat\MessageController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

require_once __DIR__.'/admin.php';

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/profile', [HomeController::class, 'profile'])->name('profile');

//auth routes
Route::get('/login', [LoginController::class, 'showLogin'])->name('showLogin');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('pusher-test', [MessageController::class, 'index']);
Route::group(['prefix' => 'live-chat'], function () {
    Route::post('/messages', [MessageController::class, 'store']);
});

Route::get('/chat-test', 'App\Http\Controllers\PusherController@index');
Route::post('/broadcast', 'App\Http\Controllers\PusherController@broadcast');
Route::post('/receive', 'App\Http\Controllers\PusherController@receive');

Route::get('/chat-test', [MessageController::class, 'index']);
Route::post('/live-chat/messages', [MessageController::class, 'store']);
