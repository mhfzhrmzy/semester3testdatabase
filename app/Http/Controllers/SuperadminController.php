<?php

namespace App\Http\Controllers;

use App\Models\AdminGuru;
use App\Models\PenggunaSiswa;

class SuperadminController extends Controller
{
    public function index()
    {
        $gurus = AdminGuru::orderBy('nama_lengkap')->get();
        $siswas = PenggunaSiswa::orderBy('nama_lengkap')->get();

        return view('superadmin.index', compact('gurus', 'siswas'));
    }
}