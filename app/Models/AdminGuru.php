<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminGuru extends Model
{
    use HasFactory;

    protected $table = 'admin_guru';
    protected $primaryKey = 'nip';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['nip', 'nama_lengkap', 'password', 'foto_profile'];

    protected $hidden = ['password'];

    public function materi()
    {
        return $this->hasMany(Materi::class, 'nip', 'nip');
    }

    public function quiz()
    {
        return $this->hasMany(Quiz::class, 'nip', 'nip');
    }
}
