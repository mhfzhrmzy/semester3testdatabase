<?php

use App\Http\Controllers\Admin\MateriController as AdminMateriController;
use App\Http\Controllers\Admin\SoalController as AdminSoalController;
use App\Http\Controllers\Siswa\MateriController as SiswaMateriController;
use App\Http\Controllers\Siswa\SoalController as SiswaSoalController;
use Illuminate\Support\Facades\Route;

// Halaman awal -> arahkan ke daftar materi siswa untuk testing cepat
Route::get('/', function () {
    return redirect()->route('siswa.materi.index');
});

/*
|--------------------------------------------------------------------------
| AREA ADMIN (guru) - CRUD Materi & Soal
| NOTE: untuk testing cepat, middleware auth/role sengaja di-nonaktifkan
| di bawah (dikomentari). Aktifkan setelah sistem login siap.
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
// ->middleware(['auth', 'role:admin'])   // aktifkan setelah ada login

    Route::resource('materi', AdminMateriController::class);

    Route::get('soal', [AdminSoalController::class, 'index'])->name('soal.index');
    Route::get('soal/create', [AdminSoalController::class, 'create'])->name('soal.create');
    Route::post('soal', [AdminSoalController::class, 'store'])->name('soal.store');
    Route::get('soal/{soal}/edit', [AdminSoalController::class, 'edit'])->name('soal.edit');
    Route::put('soal/{soal}', [AdminSoalController::class, 'update'])->name('soal.update');
    Route::delete('soal/{soal}', [AdminSoalController::class, 'destroy'])->name('soal.destroy');
});

/*
|--------------------------------------------------------------------------
| AREA SISWA (pengguna) - lihat materi, kerjakan pretest/posttest
|--------------------------------------------------------------------------
*/
Route::prefix('siswa')->name('siswa.')->group(function () {
// ->middleware(['auth', 'role:siswa'])   // aktifkan setelah ada login

    Route::get('materi', [SiswaMateriController::class, 'index'])->name('materi.index');
    Route::get('materi/{materi}', [SiswaMateriController::class, 'show'])->name('materi.show');

    Route::get('materi/{materi}/soal/{tipe}', [SiswaSoalController::class, 'kerjakan'])->name('soal.kerjakan');
    Route::post('materi/{materi}/soal/{tipe}', [SiswaSoalController::class, 'submit'])->name('soal.submit');
});
