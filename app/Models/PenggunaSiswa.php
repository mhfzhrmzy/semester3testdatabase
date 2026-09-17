<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
application Model;

class PenggunaSiswa extends Model
{
    use HasFactory;

    protected $table = 'pengguna_siswa';
    protected $primaryKey = 'nisn_pengguna';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['nisn_pengguna', 'nama_lengkap', 'email', 'password', 'poin'];

    protected $hidden = ['password'];

    public function leaderboard()
    {
        return $this->hasMany(Leaderboard::class, 'nisn_pengguna', 'nisn_pengguna');
    }
}
