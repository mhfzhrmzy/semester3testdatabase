<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $table = 'quiz';
    protected $primaryKey = 'id_quiz';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_quiz', 'nip', 'id_materi', 'tipe_test',
        'poin', 'timer', 'tanggal', 'kunci_jawaban'
    ];

    public function guru()
    {
        return $this->belongsTo(AdminGuru::class, 'nip', 'nip');
    }

    public function materi()
    {
        return $this->belongsTo(Materi::class, 'id_materi', 'id_materi');
    }

    public function leaderboard()
    {
        return $this->hasMany(Leaderboard::class, 'id_quiz', 'id_quiz');
    }
}
