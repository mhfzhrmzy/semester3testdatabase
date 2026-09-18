<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    protected $table = 'materis';
    protected $primaryKey = 'id_materi';

    protected $fillable = [
        'nip',
        'judul_materi',
        'isi_materi',
        'upload_file',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'nip', 'nip');
    }

    public function soals()
    {
        return $this->hasMany(Soal::class, 'id_materi', 'id_materi');
    }

    public function soalPretest()
    {
        return $this->soals()->where('tipe', 'pretest');
    }

    public function soalPosttest()
    {
        return $this->soals()->where('tipe', 'posttest');
    }
}