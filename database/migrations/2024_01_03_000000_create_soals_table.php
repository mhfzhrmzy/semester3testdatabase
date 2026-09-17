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
            $table->foreignId('materi_id')->constrained('materis')->onDelete('cascade');
            $table->enum('tipe', ['pretest', 'posttest']);
            $table->text('pertanyaan');
            $table->string('pilihan_a', 500);
            $table->string('pilihan_b', 500);
            $table->string('pilihan_c', 500);
            $table->string('pilihan_d', 500);
            $table->enum('jawaban_benar', ['a', 'b', 'c', 'd']);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soals');
    }
};
