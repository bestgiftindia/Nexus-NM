<?php


use App\Http\Controllers\Account\Pronology\PronologyController;
use Illuminate\Support\Facades\Route;

Route::get('/name-report/generate-report/{id}', [PronologyController::class, 'generateReport']);
