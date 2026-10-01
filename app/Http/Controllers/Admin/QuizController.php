<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Models\Quiz;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class QuizController extends Controller
{
    public function index(): View
    {
        $quizzes = Quiz::with(['materi', 'adminGuru', 'soal'])->latest()->get();
        $materis = Materi::orderBy('judul_materi')->get();

        // Kombinasi id_materi => [tipe_test, ...] yang sudah ada
        $existingCombinations = Quiz::select('id_materi', 'tipe_test')
            ->get()
            ->groupBy('id_materi')
            ->map(fn ($items) => $items->pluck('tipe_test')->toArray());

        return view('admin.quiz.index', compact('quizzes', 'materis', 'existingCombinations'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_materi' => ['required', 'exists:materi,id_materi'],
            'tipe_test' => [
                'required',
                'in:pretest,posttest',
                Rule::unique('quiz')->where(fn ($query) => $query->where('id_materi', $request->id_materi)),
            ],
            'poin' => ['required', 'integer', 'min:1', 'max:100'],
            'timer' => ['required', 'integer', 'min:1', 'max:120'],
        ], [
            'tipe_test.unique' => 'Quiz tipe ini sudah ada untuk materi yang dipilih. Setiap materi hanya boleh memiliki satu pre-test dan satu post-test.',
        ]);

        // Tanggal selalu otomatis dari server, tidak dari input pengguna
        $validated['tanggal'] = now()->toDateString();
        $validated['nip'] = auth('admin')->id();

        Quiz::create($validated);

        return redirect()->route('admin.quiz.index')->with('success', 'Quiz berhasil dibuat.');
    }

    public function destroy(Quiz $quiz): RedirectResponse
    {
        $quiz->delete();

        return redirect()->route('admin.quiz.index')->with('success', 'Quiz berhasil dihapus.');
    }
}
