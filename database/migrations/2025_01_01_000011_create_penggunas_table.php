<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penggunas', function (Blueprint $table) {
            $table->string('nisn_pengguna', 20)->primary(); // PK sesuai ERD
            $table->string('nama_lengkap');
            $table->string('email')->unique();
            $table->string('password');
            $table->unsignedInteger('poin')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penggunas');
    }
};