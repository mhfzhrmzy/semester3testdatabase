<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengguna_siswa', function (Blueprint $table) {
            $table->string('nisn', 10)->primary(); // 10 digit, diisi manual
            $table->string('nama_lengkap', 60);
            $table->enum('kelas', ['10', '11', '12']);
            $table->enum('jurusan', [
                'Teknik Alat Berat',
                'Teknik Kendaraan Ringan',
                'Teknik Sepeda Motor',
                'Teknik Pemesinan',
                'Teknik Instalasi Listrik',
                'Teknik Pembangkit Listrik',
                'Teknik Mekatronika',
                'Teknik Audio Video',
                'Teknik Komputer & Jaringan',
                'Teknik Konstruksi & Perumahan',
                'Desain Permodelan & Informasi Bangunan',
                'Desain Komunikasi Visual',
            ]);
            $table->string('password');
            $table->unsignedInteger('poin')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengguna_siswa');
    }
};
