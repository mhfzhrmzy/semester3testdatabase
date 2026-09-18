<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materis', function (Blueprint $table) {
            $table->id('id_materi');
            $table->string('nip', 20); // FK ke admins.nip (guru yang mengelola)
            $table->string('judul_materi');
            $table->text('isi_materi');
            $table->string('upload_file')->nullable();
            $table->timestamps();

            $table->foreign('nip')
                ->references('nip')->on('admins')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materis');
    }
};