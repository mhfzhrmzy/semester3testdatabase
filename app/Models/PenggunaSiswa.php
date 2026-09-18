<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengguna extends Model
{
    protected $table = 'penggunas';
    protected $primaryKey = 'nisn_pengguna';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nisn_pengguna',
        'nama_lengkap',
        'email',
        'password',
        'poin',
    ];

    protected $hidden = [
        'password',
    ];
}