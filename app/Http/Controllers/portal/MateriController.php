<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    public function index()
    {
        $materis = Materi::with(['adminGuru', 'quiz'])->latest()->get();

        return view('portal.materi.index', compact('materis'));
    }

    public function show(Materi $materi)
    {
        $materi->load(['adminGuru', 'quiz.soal']);

        return view('portal.materi.show', compact('materi'));
    }

    public function preview(Materi $materi)
    {
        if (!$materi->upload_file || !Storage::exists($materi->upload_file)) {
            abort(404, 'File materi tidak ditemukan.');
        }

        return Storage::response($materi->upload_file);
    }
}