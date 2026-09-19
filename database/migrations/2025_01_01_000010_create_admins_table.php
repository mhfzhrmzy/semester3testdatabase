<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            // unsignedBigInteger dipakai (bukan integer biasa) karena
            // NIP bisa sampai 18 digit -- integer biasa cuma sanggup
            // sampai ~10 digit (max 4.294.967.295).
            $table->unsignedBigInteger('nip')->primary(); // PK sesuai ERD
            $table->string('nama_lengkap');
            $table->string('password');
            $table->string('foto_profile')->nullable();
            $table->timestamps();
        });

        // Batasan (Check Constraint): panjang NIP harus 8-18 digit.
        // "Hanya boleh angka" otomatis terjamin karena kolomnya sudah
        // bertipe integer (unsignedBigInteger) -- MySQL akan menolak
        // input yang mengandung huruf/simbol sejak dari tipe datanya,
        // jadi REGEXP tidak diperlukan lagi di sini.
        // Silakan sesuaikan angka '8' (minimal) dan '18' (maksimal) sesuai
        // kebutuhan NIP Anda.
        DB::statement("
            ALTER TABLE `admins`
            ADD CONSTRAINT `chk_admins_nip_length`
            CHECK (CHAR_LENGTH(CAST(`nip` AS CHAR)) = 18)
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};