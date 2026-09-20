<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PenggunaSiswa;

class SiswaController extends Controller
{
    public function index()
    {
        $siswas = PenggunaSiswa::orderBy('nama_lengkap')->get();

        return view('admin.siswa.index', compact('siswas'));
    }
}
