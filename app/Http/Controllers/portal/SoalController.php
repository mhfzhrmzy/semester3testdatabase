<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use Illuminate\Http\Request;

class SoalController extends Controller
{
    public function kerjakan(Materi $materi, string $tipe)
    {
        abort_unless(in_array($tipe, ['pretest', 'posttest']), 404);

        $soals = $materi->soals()->where('tipe', $tipe)->get();

        return view('portal.soal.kerjakan', compact('materi', 'soals', 'tipe'));
    }

    public function submit(Request $request, Materi $materi, string $tipe)
    {
        abort_unless(in_array($tipe, ['pretest', 'posttest']), 404);

        $soals = $materi->soals()->where('tipe', $tipe)->get();
        $jawabanSiswa = $request->input('jawaban', []);

        $benar = 0;
        foreach ($soals as $soal) {
            if (isset($jawabanSiswa[$soal->id]) && $jawabanSiswa[$soal->id] === $soal->jawaban_benar) {
                $benar++;
            }
        }

        $total = $soals->count();
        $skor = $total > 0 ? round(($benar / $total) * 100) : 0;

        return view('portal.soal.hasil', compact('materi', 'tipe', 'benar', 'total', 'skor'));
    }
}