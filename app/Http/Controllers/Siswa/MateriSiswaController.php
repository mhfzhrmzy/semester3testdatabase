<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Leaderboard;
use App\Models\Materi;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MateriSiswaController extends Controller
{
    /**
     * Menampilkan daftar materi sesuai kelas dan jurusan siswa yang login.
     */
    public function index(): View
    {
        $siswa = auth('siswa')->user();
        $materis = Materi::with(['adminGuru', 'quiz'])
            ->where('kelas', $siswa->kelas)
            ->where('jurusan', $siswa->jurusan)
            ->latest()
            ->get();

        return view('siswa.materi.index', compact('materis'));
    }

    /**
     * Menampilkan detail materi beserta info akses pre-test.
     */
    public function show(Materi $materi): View|RedirectResponse
    {
        $siswa = auth('siswa')->user();

        if ($materi->kelas !== $siswa->kelas || $materi->jurusan !== $siswa->jurusan) {
            return redirect()
                ->route('portal.materi.index')
                ->with('error', 'Kamu tidak memiliki akses ke materi ini.');
        }

        $materi->load(['adminGuru', 'quiz.soal']);

        $pretests = $materi->quiz->where('tipe_test', 'pretest');
        $hasPretests = $pretests->isNotEmpty();
        $pretestCompleted = false;

        if ($hasPretests) {
            $pretestIds = $pretests->pluck('id_quiz');
            $pretestCompleted = Leaderboard::where('nisn', $siswa->nisn)
                ->whereIn('id_quiz', $pretestIds)
                ->exists();
        } else {
            $pretestCompleted = true;
        }

        return view('siswa.materi.show', compact('materi', 'pretestCompleted', 'hasPretests'));
    }

    /**
     * Melayani file materi untuk siswa yang sudah memenuhi syarat akses.
     */
    public function serveFile(Materi $materi): StreamedResponse|RedirectResponse
    {
        $siswa = auth('siswa')->user();

        if ($materi->kelas !== $siswa->kelas || $materi->jurusan !== $siswa->jurusan) {
            return redirect()
                ->route('portal.materi.index')
                ->with('error', 'Kamu tidak memiliki akses ke file materi ini.');
        }

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
