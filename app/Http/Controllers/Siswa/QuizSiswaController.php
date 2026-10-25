<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Leaderboard;
use App\Models\Materi;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizSiswaController extends Controller
{
    /**
     * Menampilkan daftar kuis untuk materi tertentu beserta status pengerjaan siswa.
     */
    public function index(Materi $materi)
    {
        $materi->load('adminGuru');
        $quizzes = $materi->quiz()->with('soal')->latest()->get();

        $siswa = auth('siswa')->user();
        $attemptedIds = Leaderboard::where('nisn', $siswa->nisn)
            ->whereIn('id_quiz', $quizzes->pluck('id_quiz'))
            ->pluck('total_poin', 'id_quiz');

        return view('siswa.quiz.index', compact('materi', 'quizzes', 'attemptedIds'));
    }

    /**
     * Menampilkan halaman pengerjaan kuis.
     */
    public function kerjakan(Quiz $quiz)
    {
        $quiz->load('soal', 'materi');

        return view('siswa.quiz.kerjakan', compact('quiz'));
    }

    /**
     * Memproses dan menyimpan jawaban kuis siswa ke leaderboard.
     */
    public function submit(Request $request, Quiz $quiz)
    {
        $soals = $quiz->soal;
        $jawaban = $request->input('jawaban', []);

        $benar = 0;
        foreach ($soals as $soal) {
            if (($jawaban[$soal->id_soal] ?? null) === $soal->jawaban_benar) {
                $benar++;
            }
        }

        $totalPoin = $benar * $quiz->poin;

        Leaderboard::updateOrCreate(
            ['id_quiz' => $quiz->id_quiz, 'nisn' => auth('siswa')->id()],
            ['total_poin' => $totalPoin]
        );

        return view('siswa.quiz.hasil', [
            'quiz' => $quiz,
            'benar' => $benar,
            'total' => $soals->count(),
            'totalPoin' => $totalPoin,
        ]);
    }
}
