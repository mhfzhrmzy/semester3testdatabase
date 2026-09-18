<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Admin (Guru) Table
        Schema::create('admin_guru', function (Blueprint $table) {
            $table->string('nip')->primary();
            $table->string('nama_lengkap');
            $table->string('password');
            $table->string('foto_profile')->nullable();
            $table->timestamps();
        });

        // 2. Pengguna (Siswa) Table
        Schema::create('pengguna_siswa', function (Blueprint $table) {
            $table->string('nisn_pengguna')->primary();
            $table->string('nama_lengkap');
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('poin')->default(0);
            $table->timestamps();
        });

        // 3. Materi Table
        Schema::create('materi', function (Blueprint $table) {
            $table->string('id_materi')->primary();
            $table->string('nip');
            $table->string('judul_materi');
            $table->text('isi_materi');
            $table->string('upload_file')->nullable();
            $table->timestamps();

            $table->foreign('nip')->references('nip')->on('admin_guru')->onDelete('cascade');
        });

        // 4. Quiz Table
        Schema::create('quiz', function (Blueprint $table) {
            $table->string('id_quiz')->primary();
            $table->string('nip');
            $table->string('id_materi');
            $table->enum('tipe_test', ['pre_test', 'post_test']);
            $table->integer('poin')->default(0);
            $table->integer('timer')->comment('in minutes');
            $table->date('tanggal');
            $table->text('kunci_jawaban');
            $table->timestamps();

            $table->foreign('nip')->references('nip')->on('admin_guru')->onDelete('cascade');
            $table->foreign('id_materi')->references('id_materi')->on('materi')->onDelete('cascade');
        });

        // 5. Leaderboard Table
        Schema::create('leaderboard', function (Blueprint $table) {
            $table->string('id_leaderboard')->primary();
            $table->string('nisn_pengguna');
            $table->string('id_quiz');
            $table->integer('total_poin')->default(0);
            $table->integer('peringkat')->nullable();
            $table->timestamps();

            $table->foreign('nisn_pengguna')->references('nisn_pengguna')->on('pengguna_siswa')->onDelete('cascade');
            $table->foreign('id_quiz')->references('id_quiz')->on('quiz')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leaderboard');
        Schema::dropIfExists('quiz');
        Schema::dropIfExists('materi');
        Schema::dropIfExists('pengguna_siswa');
        Schema::dropIfExists('admin_guru');
    }
};
