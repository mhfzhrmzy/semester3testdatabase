<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Materi;

class MateriController extends Controller
{
    public function index()
    {
        $materis = Materi::with('admin')->latest()->get();

        return view('portal.materi.index', compact('materis'));
    }

    public function show(Materi $materi)
    {
        $materi->load('admin');

        return view('portal.materi.show', compact('materi'));
    }
}