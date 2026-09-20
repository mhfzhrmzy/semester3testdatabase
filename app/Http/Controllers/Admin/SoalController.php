<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Soal;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SoalController extends Controller
{
    public function index(Quiz $quiz): View
    {
        $soals = $quiz->soal()->latest()->get();

        return view('admin.soal.index', compact('quiz', 'soals'));
    }

    public function store(Request $request, Quiz $quiz): RedirectResponse
    {
        $validated = $request->validate([
            'soals' => ['required', 'array', 'min:1'],
            'soals.*.pertanyaan' => ['required', 'string'],
            'soals.*.pilihan_a' => ['required', 'string', 'max:500'],
            'soals.*.pilihan_b' => ['required', 'string', 'max:500'],
            'soals.*.pilihan_c' => ['required', 'string', 'max:500'],
            'soals.*.pilihan_d' => ['required', 'string', 'max:500'],
            'soals.*.jawaban_benar' => ['required', 'in:a,b,c,d,A,B,C,D'],
            'soals.*.timer_per_soal' => ['nullable', 'integer', 'min:5'],
        ]);

        foreach ($validated['soals'] as $item) {
            $item['jawaban_benar'] = strtolower($item['jawaban_benar']);
            $item['timer_per_soal'] = $item['timer_per_soal'] ?? 60;
            $quiz->soal()->create($item);
        }

        return redirect()->route('admin.soal.index', $quiz)
            ->with('success', count($validated['soals']) . ' soal berhasil ditambahkan.');
    }

    public function importCsv(Request $request, Quiz $quiz): RedirectResponse
    {
        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
        ], [
            'csv_file.required' => 'File CSV/Spreadsheet wajib diunggah.',
            'csv_file.mimes' => 'Format file harus berupa .csv',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');

        if (! $handle) {
            return back()->with('error', 'Gagal membaca file CSV.');
        }

        $header = fgetcsv($handle, 2000, ',');

        // Deteksi pemisah koma atau titik koma
        if ($header && count($header) === 1 && str_contains($header[0], ';')) {
            rewind($handle);
            $header = fgetcsv($handle, 2000, ';');
            $delimiter = ';';
        } else {
            $delimiter = ',';
        }

        $importedCount = 0;
        while (($row = fgetcsv($handle, 2000, $delimiter)) !== false) {
            // Hindari baris kosong
            if (empty(array_filter($row))) {
                continue;
            }

            // Kolom: pertanyaan, pilihan_a, pilihan_b, pilihan_c, pilihan_d, jawaban_benar, timer_per_soal
            $pertanyaan = trim($row[0] ?? '');
            $pilihanA   = trim($row[1] ?? '');
            $pilihanB   = trim($row[2] ?? '');
            $pilihanC   = trim($row[3] ?? '');
            $pilihanD   = trim($row[4] ?? '');
            $jawaban    = strtolower(trim($row[5] ?? 'a'));
            $timer      = isset($row[6]) && is_numeric($row[6]) ? (int) $row[6] : 60;

            if ($pertanyaan !== '' && $pilihanA !== '' && $pilihanB !== '') {
                if (! in_array($jawaban, ['a', 'b', 'c', 'd'])) {
                    $jawaban = 'a';
                }

                $quiz->soal()->create([
                    'pertanyaan' => $pertanyaan,
                    'pilihan_a'  => $pilihanA,
                    'pilihan_b'  => $pilihanB,
                    'pilihan_c'  => $pilihanC,
                    'pilihan_d'  => $pilihanD,
                    'jawaban_benar' => $jawaban,
                    'timer_per_soal' => $timer,
                ]);

                $importedCount++;
            }
        }

        fclose($handle);

        return redirect()->route('admin.soal.index', $quiz)
            ->with('success', "Berhasil meng-import {$importedCount} soal dari file spreadsheet/CSV.");
    }

    public function downloadTemplate(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_soal_quiz.csv"',
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['pertanyaan', 'pilihan_a', 'pilihan_b', 'pilihan_c', 'pilihan_d', 'jawaban_benar', 'timer_per_soal']);
            fputcsv($handle, ['Berapakah hasil dari 5 + 5?', '8', '9', '10', '11', 'c', '60']);
            fputcsv($handle, ['Apakah ibukota dari Indonesia?', 'Bandung', 'Jakarta', 'Surabaya', 'Medan', 'b', '45']);
            fclose($handle);
        }, 200, $headers);
    }

    public function edit(Soal $soal): View
    {
        return view('admin.soal.edit', compact('soal'));
    }

    public function update(Request $request, Soal $soal): RedirectResponse
    {
        $validated = $request->validate([
            'pertanyaan' => ['required', 'string'],
            'pilihan_a' => ['required', 'string', 'max:500'],
            'pilihan_b' => ['required', 'string', 'max:500'],
            'pilihan_c' => ['required', 'string', 'max:500'],
            'pilihan_d' => ['required', 'string', 'max:500'],
            'jawaban_benar' => ['required', 'in:a,b,c,d,A,B,C,D'],
            'timer_per_soal' => ['required', 'integer', 'min:5'],
        ]);

        $validated['jawaban_benar'] = strtolower($validated['jawaban_benar']);

        $soal->update($validated);

        return redirect()->route('admin.soal.index', $soal->id_quiz)->with('success', 'Soal berhasil diperbarui.');
    }

    public function destroy(Soal $soal): RedirectResponse
    {
        $quizId = $soal->id_quiz;
        $soal->delete();

        return redirect()->route('admin.soal.index', $quizId)->with('success', 'Soal berhasil dihapus.');
    }
}