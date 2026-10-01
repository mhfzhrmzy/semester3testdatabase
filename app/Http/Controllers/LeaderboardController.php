<?php

namespace App\Http\Controllers;

use App\Models\Leaderboard;
use App\Models\Materi;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public const DAFTAR_KELAS = ['10', '11', '12'];

    public const DAFTAR_JURUSAN = [
        'Teknik Alat Berat',
        'Teknik Kendaraan Ringan',
        'Teknik Sepeda Motor',
        'Teknik Pemesinan',
        'Teknik Instalasi Listrik',
        'Teknik Pembangkit Listrik',
        'Teknik Mekatronika',
        'Teknik Audio Video',
        'Teknik Komputer & Jaringan',
        'Teknik Konstruksi & Perumahan',
        'Desain Permodelan & Informasi Bangunan',
        'Desain Komunikasi Visual',
    ];

    public function index(Request $request): View
    {
        $isGuru = auth('admin')->check();
        $isSiswa = auth('siswa')->check();

        // 1. Filter Kelas & Jurusan
        $kelas = $request->query('kelas');
        $jurusan = $request->query('jurusan');

        // Jika siswa login dan belum memilih kelas/jurusan, default ke kelas & jurusannya
        if ($isSiswa && ! $kelas && ! $jurusan) {
            $siswa = auth('siswa')->user();
            $kelas = $siswa->kelas;
            $jurusan = $siswa->jurusan;
        }

        if ($kelas && ! in_array($kelas, self::DAFTAR_KELAS)) {
            $kelas = null;
        }
        if ($jurusan && ! in_array($jurusan, self::DAFTAR_JURUSAN)) {
            $jurusan = null;
        }

        // 2. Tipe Leaderboard: 'keseluruhan' atau 'permateri'
        $tipeLeaderboard = $request->query('tipe_leaderboard', 'keseluruhan');
        if (! in_array($tipeLeaderboard, ['keseluruhan', 'permateri'])) {
            $tipeLeaderboard = 'keseluruhan';
        }

        // 3. Tipe Test: 'pretest', 'posttest', atau null (semua test)
        $filterTest = $request->query('tipe_test');
        if (! in_array($filterTest, ['pretest', 'posttest'])) {
            $filterTest = null;
        }

        // 4. Daftar Materi untuk dipilih
        $materiQuery = Materi::with(['quiz']);
        if ($kelas) {
            $materiQuery->where(function ($q) use ($kelas) {
                $q->where('kelas', $kelas)->orWhereNull('kelas');
            });
        }
        if ($jurusan) {
            $materiQuery->where(function ($q) use ($jurusan) {
                $q->where('jurusan', $jurusan)->orWhereNull('jurusan');
            });
        }
        $daftarMateri = $materiQuery->orderBy('judul_materi', 'asc')->get();

        $selectedMateriId = $request->query('materi_id');
        $selectedMateri = null;
        if ($selectedMateriId) {
            $selectedMateri = $daftarMateri->firstWhere('id_materi', $selectedMateriId)
                ?? Materi::with('quiz')->find($selectedMateriId);
        }

        // Jika mode permateri dan materi dipilih tetapi tipe_test belum dipilih,
        // default ke 'posttest' jika ada, atau 'pretest'
        if ($tipeLeaderboard === 'permateri' && $selectedMateri && ! $filterTest) {
            $filterTest = 'posttest';
        }

        // 5. Query Leaderboard
        if ($tipeLeaderboard === 'permateri') {
            // Leaderboard per materi
            if ($selectedMateri) {
                $query = Leaderboard::with(['quiz.materi', 'pengguna'])
                    ->whereHas('quiz', function ($q) use ($selectedMateri, $filterTest) {
                        $q->where('id_materi', $selectedMateri->id_materi);
                        if ($filterTest) {
                            $q->where('tipe_test', $filterTest);
                        }
                    });

                if ($kelas || $jurusan) {
                    $query->whereHas('pengguna', function ($q) use ($kelas, $jurusan) {
                        if ($kelas) {
                            $q->where('kelas', $kelas);
                        }
                        if ($jurusan) {
                            $q->where('jurusan', $jurusan);
                        }
                    });
                }

                $leaderboards = $query->orderBy('total_poin', 'desc')
                    ->orderBy('updated_at', 'asc')
                    ->get();
            } else {
                $leaderboards = collect();
            }
        } else {
            // Leaderboard keseluruhan: Akumulasi total skor per siswa untuk semua materi
            $query = Leaderboard::with('pengguna');

            if ($kelas || $jurusan) {
                $query->whereHas('pengguna', function ($q) use ($kelas, $jurusan) {
                    if ($kelas) {
                        $q->where('kelas', $kelas);
                    }
                    if ($jurusan) {
                        $q->where('jurusan', $jurusan);
                    }
                });
            }

            if ($filterTest) {
                $query->whereHas('quiz', fn ($q) => $q->where('tipe_test', $filterTest));
            }

            $leaderboards = $query->selectRaw('nisn, SUM(total_poin) as total_poin, COUNT(id_quiz) as total_kuis, MAX(updated_at) as updated_at')
                ->groupBy('nisn')
                ->orderByDesc('total_poin')
                ->orderBy('updated_at')
                ->get();
        }

        $daftarKelas = self::DAFTAR_KELAS;
        $daftarJurusan = self::DAFTAR_JURUSAN;

        return view('leaderboard.index', compact(
            'leaderboards',
            'filterTest',
            'kelas',
            'jurusan',
            'tipeLeaderboard',
            'daftarMateri',
            'selectedMateri',
            'selectedMateriId',
            'daftarKelas',
            'daftarJurusan',
            'isGuru',
            'isSiswa'
        ));
    }
}
