<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Materi;
use App\Models\AdminGuru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::with(['guru', 'materi'])->latest()->paginate(10);
        $gurus = AdminGuru::all();
        $materis = Materi::all();
        return view('quiz.index', compact('quizzes', 'gurus', 'materis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_quiz'      => 'required|string|unique:quiz,id_quiz',
            'nip'          => 'required|exists:admin_guru,nip',
            'id_materi'    => 'required|exists:materi,id_materi',
            'tipe_test'    => 'required|in:pre_test,post_test',
            'poin'         => 'required|integer|min:0',
            'timer'        => 'required|integer|min:1',
            'tanggal'      => 'required|date',
            'kunci_jawaban'=> 'required|string',
        ]);

        try {
            DB::beginTransaction();
            Quiz::create($validated);
            DB::commit();

            return redirect()->route('quiz.index')->with('success', 'Quiz berhasil ditambahkan!');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menambahkan quiz: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $quiz = Quiz::findOrFail($id);

        $validated = $request->validate([
            'nip'          => 'required|exists:admin_guru,nip',
            'id_materi'    => 'required|exists:materi,id_materi',
            'tipe_test'    => 'required|in:pre_test,post_test',
            'poin'         => 'required|integer|min:0',
            'timer'        => 'required|integer|min:1',
            'tanggal'      => 'required|date',
            'kunci_jawaban'=> 'required|string',
        ]);

        try {
            DB::beginTransaction();
            $quiz->update($validated);
            DB::commit();

            return redirect()->route('quiz.index')->with('success', 'Quiz berhasil diperbarui!');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui quiz: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $quiz = Quiz::findOrFail($id);
            $quiz->delete();
            DB::commit();

            return redirect()->route('quiz.index')->with('success', 'Quiz berhasil dihapus!');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus quiz: ' . $e->getMessage());
        }
    }
}
