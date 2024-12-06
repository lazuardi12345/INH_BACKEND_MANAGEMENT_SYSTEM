<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\LembagaKerjasamaController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KategoryController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\DistribusiProgramController;


// Rute pengguna yang memerlukan autentikasi
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Rute GET (terbuka tanpa autentikasi)
Route::get('/campaign', [CampaignController::class, 'index']);
Route::get('/campaign/{id}', [CampaignController::class, 'show']);
Route::get('/mitra', [MitraController::class, 'index']);
Route::get('/mitra/{id}', [MitraController::class, 'show']);
Route::get('/lembaga_kerjasama', [LembagaKerjasamaController::class, 'index']);
Route::get('/lembaga_kerjasama/{id}', [LembagaKerjasamaController::class, 'show']);
Route::get('/news', [NewsController::class, 'index']);
Route::get('/news/{id}', [NewsController::class, 'show']);
Route::get('/kategory', [KategoryController::class, 'index'])->name('kategory.index');
Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman.index');
Route::get('/distribusi-program', [DistribusiProgramController::class, 'index'])->name('distribusi-program.index');


// Rute POST, PUT, DELETE (memerlukan autentikasi)

    Route::post('/campaign', [CampaignController::class, 'store']);
    Route::put('/campaign/{id}', [CampaignController::class, 'update']);
    Route::delete('/campaign/{id}', [CampaignController::class, 'destroy']);

    Route::post('/mitra', [MitraController::class, 'store']);
    Route::put('/mitra/{id}', [MitraController::class, 'update']);
    Route::delete('/mitra/{id}', [MitraController::class, 'destroy']);

    Route::post('/lembaga_kerjasama', [LembagaKerjasamaController::class, 'store']);
    Route::put('/lembaga_kerjasama/{id}', [LembagaKerjasamaController::class, 'update']);
    Route::delete('/lembaga_kerjasama/{id}', [LembagaKerjasamaController::class, 'destroy']);

    Route::post('/news', [NewsController::class, 'store']);
    Route::put('/news/{id}', [NewsController::class, 'update']);
    Route::delete('/news/{id}', [NewsController::class, 'destroy']);

    // Kategory Routes
    Route::get('/kategory/create', [KategoryController::class, 'create'])->name('kategory.create');
    Route::post('/kategory', [KategoryController::class, 'store'])->name('kategory.store');
    Route::get('/kategory/{id}', [KategoryController::class, 'show'])->name('kategory.show');
    Route::get('/kategory/{id}/edit', [KategoryController::class, 'edit'])->name('kategory.edit');
    Route::put('/kategory/{id}', [KategoryController::class, 'update'])->name('kategory.update');
    Route::delete('/kategory/{id}', [KategoryController::class, 'destroy'])->name('kategory.destroy');

    // Pengumuman Routes
    Route::post('/pengumuman', [PengumumanController::class, 'store'])->name('pengumuman.store');
    Route::get('/pengumuman/{id}', [PengumumanController::class, 'show'])->name('pengumuman.show');
    Route::delete('/pengumuman/{id}', [PengumumanController::class, 'destroy'])->name('pengumuman.destroy');

    // Distribusi Program Routes
    Route::post('/distribusi-program', [DistribusiProgramController::class, 'store'])->name('distribusi-program.store');
    Route::get('/distribusi-program/{id}', [DistribusiProgramController::class, 'show'])->name('distribusi-program.show');
    Route::get('/distribusi-program/{id}/edit', [DistribusiProgramController::class, 'edit'])->name('distribusi-program.edit');
    Route::put('/distribusi-program/{id}', [DistribusiProgramController::class, 'update'])->name('distribusi-program.update');
    Route::delete('/distribusi-program/{id}', [DistribusiProgramController::class, 'destroy'])->name('distribusi-program.destroy');

// Rute untuk login dan register (terbuka tanpa autentikasi)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
