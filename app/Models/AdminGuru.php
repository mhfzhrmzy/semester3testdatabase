<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class AdminGuru extends Authenticatable
{
    use Notifiable;

    protected $table = 'admin_guru';
    protected $primaryKey = 'nip';
    public $incrementing = false;   // nip diisi manual, bukan auto-increment
    protected $keyType = 'int';     // tetap integer (bigint unsigned), bukan string

    protected $fillable = [
        'nip', 'nama_lengkap', 'email', 'password', 'role', 'foto_profile',
    ];

    protected $hidden = ['password', 'remember_token'];

    public function isSuperadmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function materi()
    {
        return $this->hasMany(Materi::class, 'nip', 'nip');
    }

    public function quiz()
    {
        return $this->hasMany(Quiz::class, 'nip', 'nip');
    }
}