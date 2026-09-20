<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::with(['materi', 'adminGuru', 'soal'])->latest()->get();
        $materis = Materi::orderBy('judul_materi')->get();

        return view('admin.quiz.index', compact('quizzes', 'materis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_materi' => ['required', 'exists:materi,id_materi'],
            'tipe_test' => ['required', 'in:pretest,posttest'],
            'poin' => ['required', 'integer', 'min:1'],
            'timer' => ['required', 'integer', 'min:1'],
            'tanggal' => ['required', 'date'],
        ]);

        $validated['nip'] = auth('admin')->id();

        Quiz::create($validated);

        return redirect()->route('admin.quiz.index')->with('success', 'Quiz berhasil dibuat.');
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();

        return redirect()->route('admin.quiz.index')->with('success', 'Quiz berhasil dihapus.');
    }
}