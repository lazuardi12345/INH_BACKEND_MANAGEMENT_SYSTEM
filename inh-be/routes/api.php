<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\LembagaKerjasamaController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::resource('campaign', CampaignController::class);
Route::resource('mitra', MitraController::class);
Route::resource('lembaga_kerjasama', LembagaKerjasamaController::class);