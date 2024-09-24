<?php

use App\Http\Controllers\ArtistController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MusicController;
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

    Route::prefix('artists')->controller(ArtistController::class)->group(function () {
        Route::get('/', 'index')->name('artists.index');
        Route::get('/create', 'create')->name('artists.create');  
        Route::post('/', 'store')->name('artists.store');
        Route::get('/{userId}/edit', 'edit')->name('artists.edit');
        Route::put('/{userId}', 'update')->name('artists.update');  
        Route::delete('/{userId}', 'destroy')->name('artists.destroy');
        Route::get('/{userId}/music', 'showMusic')->name('artists.show-music');
        Route::get('/import','importForm')->name('artists.import-form');
        Route::post('/import','import')->name('artists.import');
        Route::get('/{userId}', 'show')->name('artists.show');
    });

    Route::prefix('music')->controller(MusicController::class)->group(function(){
        Route::get('/','index')->name('music.index');
        Route::get('/create','create')->name('music.create');
        Route::post('/','store')->name('music.store');
        Route::get('/{id}/edit','edit')->name('music.edit');
        Route::put('/{id}','update')->name('music.update');
        Route::delete('/{id}','destroy')->name('music.destroy');
    });
    
});
