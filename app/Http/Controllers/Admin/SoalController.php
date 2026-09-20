<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Soal;
use Illuminate\Http\Request;

class SoalController extends Controller
{
    public function index(Quiz $quiz)
    {
        $soals = $quiz->soal()->latest()->get();

        return view('admin.soal.index', compact('quiz', 'soals'));
    }

    public function store(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'soals' => ['required', 'array', 'min:1'],
            'soals.*.pertanyaan' => ['required', 'string'],
            'soals.*.pilihan_a' => ['required', 'string', 'max:500'],
            'soals.*.pilihan_b' => ['required', 'string', 'max:500'],
            'soals.*.pilihan_c' => ['required', 'string', 'max:500'],
            'soals.*.pilihan_d' => ['required', 'string', 'max:500'],
            'soals.*.jawaban_benar' => ['required', 'in:a,b,c,d'],
        ]);

        foreach ($validated['soals'] as $item) {
            $quiz->soal()->create($item);
        }

        return redirect()->route('admin.soal.index', $quiz)
            ->with('success', count($validated['soals']).' soal berhasil ditambahkan.');
    }

    public function edit(Soal $soal)
    {
        return view('admin.soal.edit', compact('soal'));
    }

    public function update(Request $request, Soal $soal)
    {
        $validated = $request->validate([
            'pertanyaan' => ['required', 'string'],
            'pilihan_a' => ['required', 'string', 'max:500'],
            'pilihan_b' => ['required', 'string', 'max:500'],
            'pilihan_c' => ['required', 'string', 'max:500'],
            'pilihan_d' => ['required', 'string', 'max:500'],
            'jawaban_benar' => ['required', 'in:a,b,c,d'],
        ]);

        $soal->update($validated);

        return redirect()->route('admin.soal.index', $soal->id_quiz)->with('success', 'Soal berhasil diperbarui.');
    }

    public function destroy(Soal $soal)
    {
        $quizId = $soal->id_quiz;
        $soal->delete();

        return redirect()->route('admin.soal.index', $quizId)->with('success', 'Soal berhasil dihapus.');
    }
}