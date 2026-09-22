<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Leaderboard;
use App\Models\Materi;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index(Materi $materi)
    {
        $materi->load('adminGuru');
        $quizzes = $materi->quiz()->with('soal')->latest()->get();

        $siswa = auth('siswa')->user();
        $attemptedIds = Leaderboard::where('nisn', $siswa->nisn)
            ->whereIn('id_quiz', $quizzes->pluck('id_quiz'))
            ->pluck('total_poin', 'id_quiz');

        return view('portal.quiz.index', compact('materi', 'quizzes', 'attemptedIds'));
    }

    public function kerjakan(Quiz $quiz)
    {
        $quiz->load('soal', 'materi');

        return view('portal.quiz.kerjakan', compact('quiz'));
    }

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

        return view('portal.quiz.hasil', [
            'quiz' => $quiz,
            'benar' => $benar,
            'total' => $soals->count(),
            'totalPoin' => $totalPoin,
        ]);
    }
}
