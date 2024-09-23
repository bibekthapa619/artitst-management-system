<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;

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

    Route::prefix('users')->controller(UserController::class)->group(function () {
        Route::get('/', 'index')->name('users.index');
        Route::get('/create', 'create')->name('users.create');  
        Route::post('/', 'store')->name('users.store');
        Route::get('/{id}/edit', 'edit')->name('users.edit');
        Route::put('/{id}', 'update')->name('users.update');  
        Route::delete('/{id}', 'destroy')->name('users.destroy');
        Route::get('/{id}', 'show')->name('users.show');
    });
    
});
