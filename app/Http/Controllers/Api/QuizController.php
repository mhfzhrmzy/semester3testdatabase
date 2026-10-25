<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Leaderboard;
use App\Models\Materi;
use App\Models\Quiz;
use App\Models\Soal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index(Request $request, Materi $materi): JsonResponse
    {
        $siswa = $request->user();
        $materi->load('adminGuru');

        $quizzes = $materi->quiz()->with('soal')->latest()->get();

        $attemptedIds = Leaderboard::where('nisn', $siswa->nisn)
            ->whereIn('id_quiz', $quizzes->pluck('id_quiz'))
            ->get()
            ->keyBy('id_quiz')
            ->map(function (Leaderboard $lb) {
                return [
                    'score' => $lb->total_poin,
                    'attempted' => true,
                ];
            });

        $data = $quizzes->map(function (Quiz $quiz) use ($attemptedIds) {
            $status = $attemptedIds->get($quiz->id_quiz, ['attempted' => false, 'score' => null]);

            return [
                'id_quiz' => $quiz->id_quiz,
                'tipe_test' => $quiz->tipe_test,
                'poin' => $quiz->poin,
                'timer' => $quiz->timer,
                'tanggal' => $quiz->tanggal,
                'jumlah_soal' => $quiz->soal->count(),
                'attempted' => $status['attempted'],
                'score' => $status['score'],
            ];
        });

        return response()->json([
            'message' => 'Daftar quiz berhasil diambil.',
            'data' => $data,
        ]);
    }

    public function kerjakan(Request $request, Quiz $quiz): JsonResponse
    {
        $siswa = $request->user();
        $quiz->load('materi');

        if (! $quiz->materi) {
            return response()->json([
                'message' => 'Materi tidak ditemukan.',
            ], 404);
        }

        if ($quiz->materi->kelas !== $siswa->kelas || $quiz->materi->jurusan !== $siswa->jurusan) {
            return response()->json([
                'message' => 'Kamu tidak memiliki akses ke quiz ini.',
            ], 403);
        }

        $soals = $quiz->soal;

        if ($quiz->is_shuffle && $soals->isNotEmpty()) {
            $sessionKey = 'quiz_shuffle_'.$quiz->id_quiz.'_'.$siswa->nisn;
            $shuffledData = session($sessionKey);

            if (! $shuffledData) {
                $shuffledSoals = $soals->shuffle()->values();
                $shuffledData = [];

                foreach ($shuffledSoals as $soal) {
                    $choices = [
                        'a' => $soal->pilihan_a,
                        'b' => $soal->pilihan_b,
                        'c' => $soal->pilihan_c,
                        'd' => $soal->pilihan_d,
                    ];

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

            $soalMap = $soals->keyBy('id_soal');
            $orderedSoals = collect();

            foreach ($shuffledData as $item) {
                if (isset($soalMap[$item['id_soal']])) {
                    $s = clone $soalMap[$item['id_soal']];
                    $s->display_choices = $item['choices'];
                    $orderedSoals->push($s);
                }
            }

            $soals = $orderedSoals;
        }

        $soalArray = $soals->map(function (Soal $soal) {
            return [
                'id_soal' => $soal->id_soal,
                'pertanyaan' => $soal->pertanyaan,
                'choices' => [
                    'a' => $soal->pilihan_a,
                    'b' => $soal->pilihan_b,
                    'c' => $soal->pilihan_c,
                    'd' => $soal->pilihan_d,
                ],
                'timer_per_soal' => $soal->timer_per_soal,
            ];
        });

        return response()->json([
            'message' => 'Detail quiz berhasil diambil.',
            'data' => [
                'id_quiz' => $quiz->id_quiz,
                'tipe_test' => $quiz->tipe_test,
                'timer' => $quiz->timer,
                'poin' => $quiz->poin,
                'judul_materi' => $quiz->materi->judul_materi,
                'soals' => $soalArray,
            ],
        ]);
    }

    public function submit(Request $request, Quiz $quiz): JsonResponse
    {
        $siswa = $request->user();
        $soals = $quiz->soal;
        $jawaban = $request->input('jawaban', []);

        $benar = 0;
        foreach ($soals as $soal) {
            if (($jawaban[$soal->id_soal] ?? null) === $soal->jawaban_benar) {
                $benar++;
            }
        }

        $totalSoal = $soals->count();
        $poinPerSoal = $totalSoal > 0 ? (100 / $totalSoal) : 0;
        $totalPoin = $totalSoal > 0 ? (int) round($benar * $poinPerSoal) : 0;

        Leaderboard::updateOrCreate(
            ['id_quiz' => $quiz->id_quiz, 'nisn' => $siswa->nisn],
            ['total_poin' => $totalPoin]
        );

        $sessionKey = 'quiz_shuffle_'.$quiz->id_quiz.'_'.$siswa->nisn;
        session()->forget($sessionKey);

        return response()->json([
            'message' => 'Jawaban berhasil disimpan.',
            'benar' => $benar,
            'total' => $totalSoal,
            'total_poin' => $totalPoin,
        ]);
    }
}
