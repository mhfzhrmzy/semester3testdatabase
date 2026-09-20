<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class PenggunaSiswa extends Authenticatable
{
    use Notifiable;

    protected $table = 'pengguna_siswa';
    protected $primaryKey = 'nisn';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nisn', 'nama_lengkap', 'email', 'password', 'poin',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'nisn' => 'string',
            'password' => 'hashed',
        ];
    }

    public function leaderboard()
    {
        return $this->hasMany(Leaderboard::class, 'nisn', 'nisn');
    }
}