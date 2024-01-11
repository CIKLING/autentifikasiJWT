<?php

use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\ArtikelKomentarController;

Route::get('/', function () {
    return view('welcome');
});

// Web routes for Artikel
Route::get('/artikel', [ArtikelController::class, 'index']);
Route::get('/artikel/{id}', [ArtikelController::class, 'show']);

// Web routes for ArtikelKomentar
Route::post('/artikel-komentar/{artikel_id}', [ArtikelKomentarController::class, 'store']);
Route::put('/artikel-komentar/{komentar_id}', [ArtikelKomentarController::class, 'update']);
Route::delete('/artikel-komentar/{komentar_id}', [ArtikelKomentarController::class, 'destroy']);
