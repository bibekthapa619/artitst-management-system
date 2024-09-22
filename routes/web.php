<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;

Route::controller(AuthController::class)->group(function () {

    Route::middleware('guest')->group(function () {
        Route::get('/login', 'showLoginForm')->name('login');
        Route::get('/signup', 'showSignupForm')->name('signup');
    
        Route::post('/login', 'login')->name('login.store');
        Route::post('/signup', 'signup')->name('signup.store');
    });

    Route::post('logout','logout')->name('logout')->middleware('auth');
});

Route::middleware('auth')->group(function(){
    Route::get('/',[HomeController::class, 'index'])->name('home');
});
