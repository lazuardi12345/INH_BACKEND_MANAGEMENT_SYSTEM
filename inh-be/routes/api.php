<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\LembagaKerjasamaController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\AuthController;

// Rute pengguna yang memerlukan autentikasi
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Rute GET (terbuka tanpa autentikasi)
Route::get('/campaign', [CampaignController::class, 'index']);
Route::get('/campaign/{campaign}', [CampaignController::class, 'show']);
Route::get('/mitra', [MitraController::class, 'index']);
Route::get('/mitra/{mitra}', [MitraController::class, 'show']);
Route::get('/lembaga_kerjasama', [LembagaKerjasamaController::class, 'index']);
Route::get('/lembaga_kerjasama/{lembaga_kerjasama}', [LembagaKerjasamaController::class, 'show']);
Route::get('/news', [NewsController::class, 'index']);
Route::get('/news/{news}', [NewsController::class, 'show']);

// Rute POST, PUT, DELETE (memerlukan autentikasi)

    Route::post('/campaign', [CampaignController::class, 'store']);
    Route::put('/campaign/{campaign}', [CampaignController::class, 'update']);
    Route::delete('/campaign/{campaign}', [CampaignController::class, 'destroy']);

    Route::post('/mitra', [MitraController::class, 'store']);
    Route::put('/mitra/{mitra}', [MitraController::class, 'update']);
    Route::delete('/mitra/{mitra}', [MitraController::class, 'destroy']);

    Route::post('/lembaga_kerjasama', [LembagaKerjasamaController::class, 'store']);
    Route::put('/lembaga_kerjasama/{lembaga_kerjasama}', [LembagaKerjasamaController::class, 'update']);
    Route::delete('/lembaga_kerjasama/{lembaga_kerjasama}', [LembagaKerjasamaController::class, 'destroy']);

    Route::post('/news', [NewsController::class, 'store']);
    Route::put('/news/{news}', [NewsController::class, 'update']);
    Route::delete('/news/{news}', [NewsController::class, 'destroy']);


// Rute untuk login dan register (terbuka tanpa autentikasi)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
