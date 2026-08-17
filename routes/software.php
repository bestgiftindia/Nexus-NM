<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Account\Loshugrid\LoshugridController;
use App\Http\Controllers\Account\Mobile\AdvanceController as AdvanceMobileController;
use App\Http\Controllers\Account\Mobile\BasicController as BasicMobileController;
use App\Http\Controllers\Account\Pronology\PronologyController;
use App\Http\Controllers\Account\ChildReport\ChildController;
use App\Http\Controllers\Account\Relationship\RelationshipController;


/// Loshugrid Management
Route::controller(LoshugridController::class)->prefix('loshugrid')->name('loshugrid.')->group(function () {
    Route::get('/','index')->name('index')->middleware('permission:loshugrid-create');
    Route::post('/store','store')->name('store')->middleware('permission:loshugrid-create');

    Route::get('/lists','lists')->name('lists')->middleware('permission:loshugrid-list');
    Route::get('/lists/data','listdata')->name('lists.data')->middleware('permission:loshugrid-list');

    Route::get('/{loshugrid}/info', 'info')->name('info')->middleware('permission:loshugrid-info');

    Route::get('/{loshugrid}/edit', 'edit')->name('edit')->middleware('permission:loshugrid-edit');
    Route::patch('/{loshugrid}/update','update')->name('update')->middleware('permission:loshugrid-edit');

    Route::delete('/{loshugrid}', 'destroy')->name('destroy')->middleware('permission:loshugrid-delete');

    Route::get('/{loshugrid}/generate/pdf', 'generate')->name('generate.pdf');

});

/// Child Report
    Route::controller(ChildController::class)
        ->name('child.')->prefix('child')->group(function () {
            Route::get('/create', 'index')->name('index');
            Route::get('/get-list', 'getlist')->name('get.list');
            Route::get('/', 'list')->name('list');
            Route::post('/store', 'store')->name('store');
            Route::get('/{child}/information', 'information')->name('info');
            Route::get('/{child}/edit', 'edit')->name('edit');
            Route::put('/{child}/update', 'update')->name('update');
            Route::delete('/{child}/delete', 'delete')->name('delete');
            Route::get('/{child}/generate/pdf', 'generate_pdf')->name('generate.pdf');
        });

    /// Relationship Report
    Route::controller(RelationshipController::class)
        ->prefix('relationship')->name('relationship.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/store', 'store')->name('store');
            Route::get('/list', 'list')->name('list');
            Route::get('/datatable', 'getlist')->name('get.list');
            Route::get('/{relationship}/information', 'information')->name('info');
            Route::get('/{relationship}/edit', 'edit')->name('edit');
            Route::put('/{relationship}/update', 'update')->name('update');
            Route::delete('/{relationship}/delete', 'destroy')->name('destroy');

            Route::get('/{relationship}/generate/pdf', 'generate_pdf')->name('generate.pdf');
        });

/// Pronology Management
Route::controller(PronologyController::class)->prefix('pronology')->name('pronology.')->group(function () {
    Route::get('/','index')->name('index');
});

/// Basic Mobile Management
Route::controller(BasicMobileController::class)->prefix('mobile/basic')->name('mobile.basic.')->group(function () {
    Route::get('/','index')->name('index');
});

/// Advance Mobile Management
Route::controller(AdvanceMobileController::class)->prefix('mobile/advance')->name('mobile.advance.')->group(function () {
    Route::get('/','index')->name('index');
});
