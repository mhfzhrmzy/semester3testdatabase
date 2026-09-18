<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Models\Soal;
use Illuminate\Http\Request;

class SoalController extends Controller
{
    public function index(Request $request)
    {
        $query = Soal::with(['materi', 'admin'])->latest();

        if ($request->filled('id_materi')) {
            $query->where('id_materi', $request->id_materi);
        }

        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        $soals = $query->get();
        $materis = Materi::orderBy('judul_materi')->get();

        return view('admin.soal.index', compact('soals', 'materis'));
    }

    public function create(Request $request)
    {
        $materis = Materi::orderBy('judul_materi')->get();
        $tipe = $request->get('tipe', 'pretest');

        return view('admin.soal.create', compact('materis', 'tipe'));
    }

    /**
     * Simpan banyak soal sekaligus untuk 1 materi + 1 tipe test.
     * NIP pembuat soal otomatis mengikuti NIP guru pengelola materi tsb
     * (belum ada sistem login, jadi tidak diambil dari auth()->id()).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_materi' => ['required', 'exists:materis,id_materi'],
            'tipe' => ['required', 'in:pretest,posttest'],
            'soals' => ['required', 'array', 'min:1'],
            'soals.*.pertanyaan' => ['required', 'string'],
            'soals.*.pilihan_a' => ['required', 'string', 'max:500'],
            'soals.*.pilihan_b' => ['required', 'string', 'max:500'],
            'soals.*.pilihan_c' => ['required', 'string', 'max:500'],
            'soals.*.pilihan_d' => ['required', 'string', 'max:500'],
            'soals.*.jawaban_benar' => ['required', 'in:a,b,c,d'],
        ]);

        $materi = Materi::findOrFail($validated['id_materi']);

        foreach ($validated['soals'] as $item) {
            Soal::create([
                'id_materi' => $materi->id_materi,
                'nip' => $materi->nip,
                'tipe' => $validated['tipe'],
                'pertanyaan' => $item['pertanyaan'],
                'pilihan_a' => $item['pilihan_a'],
                'pilihan_b' => $item['pilihan_b'],
                'pilihan_c' => $item['pilihan_c'],
                'pilihan_d' => $item['pilihan_d'],
                'jawaban_benar' => $item['jawaban_benar'],
            ]);
        }

        $jumlah = count($validated['soals']);

        return redirect()
            ->route('admin.soal.index')
            ->with('success', "{$jumlah} soal berhasil ditambahkan.");
    }

    public function edit(Soal $soal)
    {
        $materis = Materi::orderBy('judul_materi')->get();

        return view('admin.soal.edit', compact('soal', 'materis'));
    }

    public function update(Request $request, Soal $soal)
    {
        $validated = $request->validate([
            'id_materi' => ['required', 'exists:materis,id_materi'],
            'tipe' => ['required', 'in:pretest,posttest'],
            'pertanyaan' => ['required', 'string'],
            'pilihan_a' => ['required', 'string', 'max:500'],
            'pilihan_b' => ['required', 'string', 'max:500'],
            'pilihan_c' => ['required', 'string', 'max:500'],
            'pilihan_d' => ['required', 'string', 'max:500'],
            'jawaban_benar' => ['required', 'in:a,b,c,d'],
        ]);

        $materi = Materi::findOrFail($validated['id_materi']);
        $validated['nip'] = $materi->nip;

        $soal->update($validated);

        return redirect()
            ->route('admin.soal.index')
            ->with('success', 'Soal berhasil diperbarui.');
    }

    public function destroy(Soal $soal)
    {
        $soal->delete();

        return redirect()
            ->route('admin.soal.index')
            ->with('success', 'Soal berhasil dihapus.');
    }
}