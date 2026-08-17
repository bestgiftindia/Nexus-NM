<?php

use App\Helpers\Image;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OtherController;
use App\Http\Controllers\Auth\AuthController;

Route::middleware('guest')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('/login', 'loginForm')->name('login');

        Route::post('/login/authenticate', 'authenticate')->name('login.authenticate');
        Route::get('/login/verify', 'otp_page')->name('login.otp');
        Route::post('/login/otp/verify', 'otp_verify')->name('login.otp.verify');
        Route::get('/login/otp/resend', 'resendOtp')->name('login.otp.resend');

        Route::get('/register', 'registerForm')->name('register');
    });
});

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/about', 'about')->name('about');
    Route::get('/contact', 'contact')->name('contact');
    Route::get('/help', 'help')->name('help');
    Route::get('/privacy-policy', 'privacy')->name('privacy');
    Route::get('/terms-conditions', 'terms')->name('terms');
    Route::get('/refund-policy', 'refund')->name('refund');
});

Route::controller(OtherController::class)->group(function () {
    Route::get('/states/lists', 'states_lists')->name('states');
    Route::get('/cities/lists', 'cities_lists')->name('cities');
});


Route::get('/image/{path}/sx{width}w/{folder}/{filename}', function ($path, $width, $folder, $filename) {
    $binary = Image::getBinary($path, $width, $folder, $filename);
    return response($binary, 200)
        ->header('Content-Type', 'image/avif')
        ->header('Cache-Control', 'public, max-age=31536000');
})->name('image.resize');
