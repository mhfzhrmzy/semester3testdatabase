<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Leaderboard;
use App\Models\Materi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

        $siswa = auth('siswa')->user();

        // Cek apakah ada pre-test untuk materi ini
        $pretests = $materi->quiz->where('tipe_test', 'pretest');
        $hasPretests = $pretests->isNotEmpty();

        // Cek apakah siswa sudah mengerjakan minimal satu pre-test
        $pretestCompleted = false;
        if ($hasPretests) {
            $pretestIds = $pretests->pluck('id_quiz');
            $pretestCompleted = Leaderboard::where('nisn', $siswa->nisn)
                ->whereIn('id_quiz', $pretestIds)
                ->exists();
        } else {
            // Tidak ada pre-test → langsung boleh akses materi
            $pretestCompleted = true;
        }

        return view('portal.materi.show', compact('materi', 'pretestCompleted', 'hasPretests'));
    }

    /**
     * Melayani file materi untuk siswa.
     * Siswa hanya bisa mengakses file jika sudah mengerjakan pre-test
     * (atau jika tidak ada pre-test untuk materi ini).
     */
    public function serveFile(Materi $materi): StreamedResponse|RedirectResponse
    {
        $siswa = auth('siswa')->user();

        // Cek keberadaan pre-test dan apakah sudah dikerjakan
        $pretests = $materi->quiz()->where('tipe_test', 'pretest')->get();
        $hasPretests = $pretests->isNotEmpty();

        if ($hasPretests) {
            $pretestIds = $pretests->pluck('id_quiz');
            $pretestCompleted = Leaderboard::where('nisn', $siswa->nisn)
                ->whereIn('id_quiz', $pretestIds)
                ->exists();

            if (! $pretestCompleted) {
                return redirect()
                    ->route('portal.quiz.index', $materi)
                    ->with('error', 'Kamu harus menyelesaikan Pre-Test terlebih dahulu sebelum dapat mengakses file materi.');
            }
        }

        if (! $materi->upload_file || ! Storage::disk('public')->exists($materi->upload_file)) {
            abort(404, 'File materi tidak ditemukan.');
        }

        return Storage::disk('public')->response($materi->upload_file);
    }
}