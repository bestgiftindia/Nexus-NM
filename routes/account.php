<?php

use App\Http\Controllers\Account\OtherController;
use App\Http\Controllers\Account\PermissionController;
use App\Http\Controllers\Account\RoleController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Account\HomeController;
use App\Http\Controllers\Account\User\UserController;
use App\Http\Controllers\Account\ProfileController;
use App\Http\Controllers\Account\SocialMediaController;
use App\Http\Controllers\Account\NotificationController;


require base_path('routes/software.php');

/// Logout Management
Route::controller(AuthController::class)->group(function () {
    Route::get('/logout', 'logout')->name('logout');
});

/// Dashboard Management
Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('/');
    Route::get('/', 'index')->name('dashboard');
});

/// User Management
Route::controller(UserController::class)->prefix('users')->name('users.')->group(function () {

    Route::get('/', 'index')->name('index')->middleware('permission:user-list');
    Route::get('/lists/data', 'list_data')->name('lists.data')->middleware('permission:user-list');

    Route::get('/create', 'create')->name('create')->middleware('permission:user-create');
    Route::post('/', 'store')->name('store')->middleware('permission:user-create');
    Route::get('/{user}/edit', 'edit')->name('edit')->middleware('permission:user-edit');
    Route::patch('/{user}', 'update')->name('update')->middleware('permission:user-edit');
    Route::post('/publish', 'changeStatus')->name('publish')->middleware('permission:user-edit');
    Route::delete('/{user}', 'destroy')->name('destroy')->middleware('permission:user-delete');
});

/// Role Management
Route::prefix('roles')->name('roles.')->group(function () {
    Route::get('/', [RoleController::class, 'index'])->middleware('permission:role-list')->name('index');
    Route::get('/create', [RoleController::class, 'create'])->middleware('permission:role-create')->name('create');
    Route::post('/', [RoleController::class, 'store'])->middleware('permission:role-create')->name('store');

    Route::get('/{role}/edit', [RoleController::class, 'edit'])->middleware('permission:role-edit')->name('edit');
    Route::patch('/{role}', [RoleController::class, 'update'])->middleware('permission:role-edit')->name('update');
    Route::delete('/{role}', [RoleController::class, 'destroy'])->middleware('permission:role-delete')->name('destroy');
    Route::post('publish', [RoleController::class, 'changeStatus'])->name('publish')->middleware('permission:role-edit');

    Route::get('lists/data', [RoleController::class, 'list_data'])->name('lists.data')->middleware('permission:role-list');
});

/// Permission Management
Route::prefix('permissions')->name('permissions.')->group(function () {
    Route::get('/', [PermissionController::class, 'index'])->middleware('permission:permission-list')->name('index');
    Route::get('/create', [PermissionController::class, 'create'])->middleware('permission:permission-create')->name('create');
    Route::post('/', [PermissionController::class, 'store'])->middleware('permission:permission-create')->name('store');

    Route::get('/{permission}/edit', [PermissionController::class, 'edit'])->middleware('permission:permission-edit')->name('edit');
    Route::patch('/{permission}', [PermissionController::class, 'update'])->middleware('permission:permission-edit')->name('update');
    Route::delete('/{permission}', [PermissionController::class, 'destroy'])->middleware('permission:permission-delete')->name('destroy');

    Route::post('publish', [PermissionController::class, 'changeStatus'])->name('publish')->middleware('permission:permission-edit');
    Route::get('lists/data', [PermissionController::class, 'list_data'])->name('lists.data')->middleware('permission:permission-list');
});


/// Profile Management
Route::controller(ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::patch('/update', 'update')->name('update');
});

/// Social Media Management
Route::controller(SocialMediaController::class)->prefix('social-media')->name('socialMedia.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/update', 'update')->name('update');
});

/// Notification Management
Route::controller(NotificationController::class)->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', 'index')->name('index');
});

/// Other Management
Route::controller(OtherController::class)->group(function () {
    Route::get('/login-history', 'login_history')->name('login-history.index');
    Route::get('/login-history/data', 'login_history_data')->name('login-history.data');
});

