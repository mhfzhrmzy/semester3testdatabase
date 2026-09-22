<?php

use App\Http\Controllers\Admin\QuizController as AdminQuizController;
use App\Http\Controllers\Admin\SoalController as AdminSoalController;
use App\Http\Controllers\AdminGuruController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\AdminRegisterController;
use App\Http\Controllers\Auth\SiswaLoginController;
use App\Http\Controllers\Auth\SiswaRegisterController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\PenggunaSiswaController;
use App\Http\Controllers\Portal\MateriController as PortalMateriController;
use App\Http\Controllers\Portal\QuizController as PortalQuizController;
use App\Http\Controllers\SuperadminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth('admin')->check()) {
        return auth('admin')->user()->role === 'superadmin'
            ? redirect()->route('superadmin.index')
            : redirect()->route('materi.index');
    }

    if (auth('siswa')->check()) {
        return redirect()->route('portal.materi.index');
    }

    return view('home');
})->name('home');

Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');

Route::get('/login/guru', [AdminLoginController::class, 'create'])->name('admin.login');
Route::post('/login/guru', [AdminLoginController::class, 'store'])->name('admin.login.attempt');
Route::get('/register/guru', [AdminRegisterController::class, 'create'])->name('admin.register');
Route::post('/register/guru', [AdminRegisterController::class, 'store'])->name('admin.register.attempt');
Route::post('/logout/guru', [AdminLoginController::class, 'destroy'])->name('admin.logout');

Route::get('/login/siswa', [SiswaLoginController::class, 'create'])->name('siswa.login');
Route::post('/login/siswa', [SiswaLoginController::class, 'store'])->name('siswa.login.attempt');
Route::get('/register/siswa', [SiswaRegisterController::class, 'create'])->name('siswa.register');
Route::post('/register/siswa', [SiswaRegisterController::class, 'store'])->name('siswa.register.attempt');
Route::post('/logout/siswa', [SiswaLoginController::class, 'destroy'])->name('siswa.logout');

Route::middleware('auth:admin')->group(function () {

    Route::controller(MateriController::class)->group(function () {
        Route::get('/materi', 'index')->name('materi.index');
        Route::post('/materi', 'store')->name('materi.store');
        Route::get('/materi/{materi}', 'show')->name('materi.show');
        Route::get('/materi/{materi}/edit', 'edit')->name('materi.edit');
        Route::put('/materi/{materi}', 'update')->name('materi.update');
        Route::delete('/materi/{materi}', 'destroy')->name('materi.destroy');
    });

    Route::prefix('admin')->name('admin.')->controller(AdminQuizController::class)->group(function () {
        Route::get('/quiz', 'index')->name('quiz.index');
        Route::post('/quiz', 'store')->name('quiz.store');
        Route::delete('/quiz/{quiz}', 'destroy')->name('quiz.destroy');
    });

    Route::prefix('admin')->name('admin.')->controller(AdminSoalController::class)->group(function () {
        Route::get('/quiz/{quiz}/soal', 'index')->name('soal.index');
        Route::post('/quiz/{quiz}/soal', 'store')->name('soal.store');
        Route::post('/quiz/{quiz}/soal/import', 'importCsv')->name('soal.import');
        Route::get('/soal/template', 'downloadTemplate')->name('soal.template');
        Route::get('/soal/{soal}/edit', 'edit')->name('soal.edit');
        Route::put('/soal/{soal}', 'update')->name('soal.update');
        Route::delete('/soal/{soal}', 'destroy')->name('soal.destroy');
    });

    Route::middleware('superadmin')->group(function () {
        Route::controller(AdminGuruController::class)->group(function () {
            Route::get('/admin', 'index')->name('admin.index');
            Route::post('/admin', 'store')->name('admin.store');
            Route::put('/admin/{guru}', 'update')->name('admin.update');
            Route::delete('/admin/{guru}', 'destroy')->name('admin.destroy');
        });

        Route::controller(PenggunaSiswaController::class)->group(function () {
            Route::get('/siswa', 'index')->name('siswa.index');
            Route::post('/siswa', 'store')->name('siswa.store');
            Route::put('/siswa/{siswa}', 'update')->name('siswa.update');
            Route::delete('/siswa/{siswa}', 'destroy')->name('siswa.destroy');
        });

        Route::get('/superadmin', [SuperadminController::class, 'index'])->name('superadmin.index');
    });
});

Route::middleware('auth:siswa')->prefix('portal')->name('portal.')->group(function () {
    Route::get('/materi', [PortalMateriController::class, 'index'])->name('materi.index');
    Route::get('/materi/{materi}', [PortalMateriController::class, 'show'])->name('materi.show');

    Route::get('/materi/{materi}/quiz', [PortalQuizController::class, 'index'])->name('quiz.index');
    Route::get('/quiz/{quiz}', [PortalQuizController::class, 'kerjakan'])->name('quiz.kerjakan');
    Route::post('/quiz/{quiz}', [PortalQuizController::class, 'submit'])->name('quiz.submit');
});
