<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leaderboard extends Model
{
    use HasFactory;

    protected $table = 'leaderboard';
    protected $primaryKey = 'id_leaderboard';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id_leaderboard', 'nisn_pengguna', 'id_quiz', 'total_poin', 'peringkat'];

    public function siswa()
    {
        return $this->belongsTo(PenggunaSiswa::class, 'nisn_pengguna', 'nisn_pengguna');
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'id_quiz', 'id_quiz');
    }
}
