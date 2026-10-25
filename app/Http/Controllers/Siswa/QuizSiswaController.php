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
        $soals = $quiz->soal;

        if ($quiz->is_shuffle && $soals->isNotEmpty()) {
            $siswaId = auth('siswa')->id();
            $sessionKey = 'quiz_shuffle_'.$quiz->id_quiz.'_'.$siswaId;
            $shuffledData = session($sessionKey);

            if (! $shuffledData) {
                // Acak urutan soal secara unik untuk siswa ini
                $shuffledSoals = $soals->shuffle()->values();
                $shuffledData = [];

                foreach ($shuffledSoals as $soal) {
                    $choices = [
                        'a' => $soal->pilihan_a,
                        'b' => $soal->pilihan_b,
                        'c' => $soal->pilihan_c,
                        'd' => $soal->pilihan_d,
                    ];

                    // Acak urutan pilihan jawaban
                    $keys = array_keys($choices);
                    shuffle($keys);

                    $shuffledChoices = [];
                    foreach ($keys as $k) {
                        $shuffledChoices[$k] = $choices[$k];
                    }

                    $shuffledData[] = [
                        'id_soal' => $soal->id_soal,
                        'choices' => $shuffledChoices,
                    ];
                }

                session([$sessionKey => $shuffledData]);
            }

            // Terapkan susunan urutan soal & pilihan jawaban dari session
            $soalMap = $soals->keyBy('id_soal');
            $orderedSoals = collect();

            foreach ($shuffledData as $item) {
                if (isset($soalMap[$item['id_soal']])) {
                    $s = clone $soalMap[$item['id_soal']];
                    $s->display_choices = $item['choices'];
                    $orderedSoals->push($s);
                }
            }

            $quiz->setRelation('soal', $orderedSoals);
        } else {
            // Urutan normal (tidak diacak)
            foreach ($quiz->soal as $s) {
                $s->display_choices = [
                    'a' => $s->pilihan_a,
                    'b' => $s->pilihan_b,
                    'c' => $s->pilihan_c,
                    'd' => $s->pilihan_d,
                ];
            }
        }

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

        // Hapus session acak setelah kuis berhasil disubmit
        $sessionKey = 'quiz_shuffle_'.$quiz->id_quiz.'_'.auth('siswa')->id();
        session()->forget($sessionKey);

        return view('siswa.quiz.hasil', [
            'quiz' => $quiz,
            'benar' => $benar,
            'total' => $soals->count(),
            'totalPoin' => $totalPoin,
        ]);
    }
}
