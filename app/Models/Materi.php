<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    protected $fillable = [
        'judul',
        'deskripsi',
        'file_name',
        'file_path',
        'created_by',
    ];

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function soals()
    {
        return $this->hasMany(Soal::class, 'materi_id');
    }

    public function soalPretest()
    {
        return $this->hasMany(Soal::class, 'materi_id')->where('tipe', 'pretest');
    }

    public function soalPosttest()
    {
        return $this->hasMany(Soal::class, 'materi_id')->where('tipe', 'posttest');
    }
}
