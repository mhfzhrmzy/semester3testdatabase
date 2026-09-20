<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Leaderboard;
use App\Models\Quiz;

class LeaderboardController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::where('tipe_test', 'posttest')
            ->with('materi')
            ->latest()
            ->get();

        $selectedQuiz = request('quiz_id');

        $results = Leaderboard::with(['pengguna', 'quiz.materi'])
            ->whereHas('quiz', fn ($query) => $query->where('tipe_test', 'posttest'))
            ->when($selectedQuiz, fn ($query) => $query->where('id_quiz', $selectedQuiz))
            ->orderByDesc('total_poin')
            ->orderBy('updated_at')
            ->get();

        return view('admin.leaderboard.index', compact('quizzes', 'results', 'selectedQuiz'));
    }

    public function data()
    {
        $results = Leaderboard::with(['pengguna', 'quiz.materi'])
            ->whereHas('quiz', fn ($query) => $query->where('tipe_test', 'posttest'))
            ->when(request('quiz_id'), fn ($query) => $query->where('id_quiz', request('quiz_id')))
            ->orderByDesc('total_poin')
            ->orderBy('updated_at')
            ->get()
            ->values()
            ->map(fn ($result, $index) => [
                'rank' => $index + 1,
                'nama' => $result->pengguna?->nama_lengkap ?? '-',
                'nisn' => $result->nisn,
                'quiz' => $result->quiz?->materi?->judul_materi ?? '-',
                'poin' => $result->total_poin,
                'updated_at' => $result->updated_at?->format('d/m/Y H:i:s'),
            ]);

        return response()->json($results);
    }
}
