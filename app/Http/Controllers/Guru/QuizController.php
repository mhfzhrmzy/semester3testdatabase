<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Models\Quiz;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class QuizController extends Controller
{
    /**
     * Menampilkan daftar kuis dan form tambah kuis.
     */
    public function index(): View
    {
        $quizzes = Quiz::with(['materi', 'adminGuru', 'soal'])->latest()->get();
        $materis = Materi::orderBy('judul_materi')->get();

        // Kombinasi id_materi => [tipe_test, ...] yang sudah ada (untuk mencegah duplikat)
        $existingCombinations = Quiz::select('id_materi', 'tipe_test')
            ->get()
            ->groupBy('id_materi')
            ->map(fn ($items) => $items->pluck('tipe_test')->toArray());

        return view('guru.quiz.index', compact('quizzes', 'materis', 'existingCombinations'));
    }

    /**
     * Menyimpan kuis baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_materi' => ['required', 'exists:materi,id_materi'],
            'tipe_test' => [
                'required',
                'in:pretest,posttest',
                Rule::unique('quiz')->where(fn ($query) => $query->where('id_materi', $request->id_materi)),
            ],
            'poin' => ['nullable', 'integer', 'min:1'],
            'timer' => ['required', 'integer', 'min:1', 'max:120'],
        ], [
            'tipe_test.unique' => 'Quiz tipe ini sudah ada untuk materi yang dipilih. Setiap materi hanya boleh memiliki satu pre-test dan satu post-test.',
        ]);

        $validated['poin'] = 100; // Otomatis total 100 poin (poin per soal = 100 / jumlah soal)
        $validated['tanggal'] = now()->toDateString();
        $validated['nip'] = auth('admin')->id();

        Quiz::create($validated);

        return redirect()->route('admin.quiz.index')->with('success', 'Quiz berhasil dibuat.');
    }

    /**
     * Menghapus kuis beserta seluruh soal di dalamnya.
     */
    public function destroy(Quiz $quiz): RedirectResponse
    {
        $quiz->delete();

        return redirect()->route('admin.quiz.index')->with('success', 'Quiz berhasil dihapus.');
    }
}
