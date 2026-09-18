<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('soals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_materi')->constrained('materis', 'id_materi')->onDelete('cascade');
            $table->string('nip', 20); // otomatis ikut NIP guru pengelola materi
            $table->enum('tipe', ['pretest', 'posttest']);
            $table->text('pertanyaan');
            $table->string('pilihan_a', 500);
            $table->string('pilihan_b', 500);
            $table->string('pilihan_c', 500);
            $table->string('pilihan_d', 500);
            $table->enum('jawaban_benar', ['a', 'b', 'c', 'd']);
            $table->timestamps();

            $table->foreign('nip')->references('nip')->on('admins')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soals');
    }
};