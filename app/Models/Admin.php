<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admins';
    protected $primaryKey = 'nip';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nip',
        'nama_lengkap',
        'password',
        'foto_profile',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Relasi: satu Admin (guru) mengelola banyak Materi.
     */
    public function materis()
    {
        return $this->hasMany(Materi::class, 'nip', 'nip');
    }
}