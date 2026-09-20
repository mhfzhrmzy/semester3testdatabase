<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $table = 'quiz';
    protected $primaryKey = 'id_quiz';

    protected $fillable = ['nip', 'id_materi', 'tipe_test', 'poin', 'timer', 'tanggal'];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function adminGuru()
    {
        return $this->belongsTo(AdminGuru::class, 'nip', 'nip');
    }

    public function materi()
    {
        return $this->belongsTo(Materi::class, 'id_materi', 'id_materi');
    }

    public function soal()
    {
        return $this->hasMany(Soal::class, 'id_quiz', 'id_quiz');
    }

    public function leaderboard()
    {
        return $this->hasMany(Leaderboard::class, 'id_quiz', 'id_quiz');
    }
}   