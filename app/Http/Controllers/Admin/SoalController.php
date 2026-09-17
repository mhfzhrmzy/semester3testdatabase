<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Models\Soal;
use Illuminate\Http\Request;

class SoalController extends Controller
{
    // Daftar soal, bisa difilter per materi & tipe
    public function index(Request $request)
    {
        $query = Soal::with('materi')->latest();

        if ($request->filled('materi_id')) {
            $query->where('materi_id', $request->materi_id);
        }
        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        $soals = $query->get();
        $materis = Materi::all();

        return view('admin.soal.index', compact('soals', 'materis'));
    }

    // Form tambah soal (dinamis: admin bisa klik "tambah soal" berkali-kali)
    public function create(Request $request)
    {
        $materis = Materi::all();
        $tipe = $request->get('tipe', 'pretest');
        return view('admin.soal.create', compact('materis', 'tipe'));
    }

    /**
     * Simpan banyak soal sekaligus.
     * Data dikirim dari form dinamis dengan format array:
     * soals[0][pertanyaan], soals[0][pilihan_a], ... soals[0][jawaban_benar]
     * soals[1][pertanyaan], dst - jadi admin bisa kirim puluhan soal dalam 1 submit.
     */
    public function store(Request $request)
    {
        $request->validate([
            'materi_id' => 'required|exists:materis,id',
            'tipe' => 'required|in:pretest,posttest',
            'soals' => 'required|array|min:1',
            'soals.*.pertanyaan' => 'required|string',
            'soals.*.pilihan_a' => 'required|string|max:500',
            'soals.*.pilihan_b' => 'required|string|max:500',
            'soals.*.pilihan_c' => 'required|string|max:500',
            'soals.*.pilihan_d' => 'required|string|max:500',
            'soals.*.jawaban_benar' => 'required|in:a,b,c,d',
        ]);

        foreach ($request->soals as $item) {
            Soal::create([
                'materi_id' => $request->materi_id,
                'tipe' => $request->tipe,
                'pertanyaan' => $item['pertanyaan'],
                'pilihan_a' => $item['pilihan_a'],
                'pilihan_b' => $item['pilihan_b'],
                'pilihan_c' => $item['pilihan_c'],
                'pilihan_d' => $item['pilihan_d'],
                'jawaban_benar' => $item['jawaban_benar'],
                'created_by' => auth()->id(),
            ]);
        }

        $jumlah = count($request->soals);
        return redirect()->route('admin.soal.index')->with('success', "$jumlah soal berhasil ditambahkan.");
    }

    public function edit(Soal $soal)
    {
        $materis = Materi::all();
        return view('admin.soal.edit', compact('soal', 'materis'));
    }

    public function update(Request $request, Soal $soal)
    {
        $request->validate([
            'materi_id' => 'required|exists:materis,id',
            'tipe' => 'required|in:pretest,posttest',
            'pertanyaan' => 'required|string',
            'pilihan_a' => 'required|string|max:500',
            'pilihan_b' => 'required|string|max:500',
            'pilihan_c' => 'required|string|max:500',
            'pilihan_d' => 'required|string|max:500',
            'jawaban_benar' => 'required|in:a,b,c,d',
        ]);

        $soal->update($request->only([
            'materi_id', 'tipe', 'pertanyaan',
            'pilihan_a', 'pilihan_b', 'pilihan_c', 'pilihan_d',
            'jawaban_benar',
        ]));

        return redirect()->route('admin.soal.index')->with('success', 'Soal berhasil diperbarui.');
    }

    public function destroy(Soal $soal)
    {
        $soal->delete();
        return redirect()->route('admin.soal.index')->with('success', 'Soal berhasil dihapus.');
    }
}
