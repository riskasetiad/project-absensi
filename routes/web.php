<?php

use App\Http\Controllers\AbsenController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes([
    'register' => false,
]);

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\UserController::class, 'home'])->name('home');

    // Hanya pengguna dengan permission 'read_user' yang bisa mengakses route user
    Route::resource('user', UserController::class)->middleware('can:read_user');

    // Hanya pengguna dengan permission 'read_absen' yang bisa mengakses route absen
    Route::resource('absen', AbsenController::class)->middleware('can:read_absen');

    // Hanya pengguna dengan permission 'read_user' yang bisa mengakses laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index')->middleware('can:read_user');

    // Export Excel dan PDF hanya bisa diakses oleh admin
    Route::get('/laporan/export/excel', [LaporanController::class, 'exportExcel'])->name('laporan.exportExcel')->middleware('can:read_user');
    Route::get('/laporan/export/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.exportPdf')->middleware('can:read_user');
});
