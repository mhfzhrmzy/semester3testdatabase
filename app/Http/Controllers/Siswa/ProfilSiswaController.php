<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Sertifikat;

class ProfilSiswaController extends Controller
{
    public function index()
    {
        $siswa = auth('siswa')->user();

        $sertifikat = Sertifikat::where('nisn', $siswa->nisn)
            ->latest()
            ->get();

        return view('siswa.profil', compact('siswa', 'sertifikat'));
    }
}
