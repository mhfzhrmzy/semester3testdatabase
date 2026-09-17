<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Materi;

class MateriController extends Controller
{
    // Daftar materi yang bisa dilihat siswa
    public function index()
    {
        $materis = Materi::latest()->get();
        return view('siswa.materi.index', compact('materis'));
    }

    // Detail 1 materi: deskripsi + link download modul + link pretest/posttest
    public function show(Materi $materi)
    {
        return view('siswa.materi.show', compact('materi'));
    }
}
