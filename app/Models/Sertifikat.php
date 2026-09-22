<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sertifikat extends Model
{
    use HasFactory;

    protected $table = 'sertifikat';
    protected $primaryKey = 'id_sertifikat';

    protected $fillable = [
        'nisn',
        'nip',
        'id_materi',
        'judul_sertifikat',
        'penerbit',
        'tanggal_terbit',
        'deskripsi',
        'file_sertifikat',
        'tipe_sertifikat',
    ];

    public function siswa()
    {
        return $this->belongsTo(PenggunaSiswa::class, 'nisn', 'nisn');
    }

    public function guru()
    {
        return $this->belongsTo(AdminGuru::class, 'nip', 'nip');
    }

    public function materi()
    {
        return $this->belongsTo(Materi::class, 'id_materi', 'id_materi');
    }
}