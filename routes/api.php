<?php

use App\Http\Controllers\APIController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\RuangController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(APIController::class)->group(function () {
    Route::get('/alat', 'apigetAlat');
    Route::post('/alat', 'postAlat');
    Route::put('/alat/{id}', 'updateAlat');
    Route::delete('/alat/{id}', 'deleteAlat');

    Route::get('/ruang', 'apigetRuang');
    Route::post('/ruang', 'postAlat');
    Route::put('/ruang/{id}', 'updateAlat');
    Route::delete('/ruang/{id}', 'deleteAlat');

    // Route::get('/ruang', [RuangController::class, 'apigetRuang']);
    // Route::post('/ruang', [RuangController::class, 'postRuang']);
    // Route::put('/ruang/{id}', [RuangController::class, 'updateRuang']);
    // Route::delete('/ruang/{id}', [RuangController::class, 'deleteRuang']);

    // Route::get('/ruang', [RuangController::class, 'apigetRuang']);
    // Route::post('/ruang', [RuangController::class, 'postRuang']);
    // Route::put('/ruang/{id}', [RuangController::class, 'updateRuang']);
    // Route::delete('/ruang/{id}', [RuangController::class, 'deleteRuang']);
});
