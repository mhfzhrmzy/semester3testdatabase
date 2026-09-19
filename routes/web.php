<?php

use App\Http\Controllers\Admin\SoalController as AdminSoalController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\Portal\MateriController as PortalMateriController;
use App\Http\Controllers\Portal\SoalController as PortalSoalController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::controller(AdminController::class)->group(function () {
    Route::get('/admin', 'index')->name('admin.index');
    Route::get('/admin/{admin}/edit', 'edit')->name('admin.edit');
    Route::put('/admin/{admin}', 'update')->name('admin.update');
    Route::post('/admin', 'store')->name('admin.store');
    Route::delete('/admin/{admin}', 'destroy')->name('admin.destroy');
});

Route::controller(PenggunaController::class)->group(function () {
    Route::get('/siswa', 'index')->name('siswa.index');
    Route::post('/siswa', 'store')->name('siswa.store');
    Route::delete('/siswa/{siswa}', 'destroy')->name('siswa.destroy');
});

// Route Kelola Materi (Admin/Guru)
Route::controller(MateriController::class)->group(function () {
    Route::get('/materi', 'index')->name('materi.index');
    Route::post('/materi', 'store')->name('materi.store');
    Route::get('/materi/{materi}/edit', 'edit')->name('materi.edit');
    Route::put('/materi/{materi}', 'update')->name('materi.update');
    Route::delete('/materi/{materi}', 'destroy')->name('materi.destroy');
});

Route::prefix('admin')->name('admin.')->controller(AdminSoalController::class)->group(function () {
    Route::get('/soal', 'index')->name('soal.index');
    Route::get('/soal/create', 'create')->name('soal.create');
    Route::post('/soal', 'store')->name('soal.store');
    Route::get('/soal/{soal}/edit', 'edit')->name('soal.edit');
    Route::put('/soal/{soal}', 'update')->name('soal.update');
    Route::delete('/soal/{soal}', 'destroy')->name('soal.destroy');
});

Route::prefix('portal')->name('portal.')->group(function () {
    Route::get('/materi', [PortalMateriController::class, 'index'])->name('materi.index');
    Route::get('/materi/{materi}', [PortalMateriController::class, 'show'])->name('materi.show');
    Route::get('/materi/{materi}/preview', [PortalMateriController::class, 'preview'])->name('materi.preview');

    Route::get('/materi/{materi}/soal/{tipe}', [PortalSoalController::class, 'kerjakan'])->name('soal.kerjakan');
    Route::post('/materi/{materi}/soal/{tipe}', [PortalSoalController::class, 'submit'])->name('soal.submit');
});