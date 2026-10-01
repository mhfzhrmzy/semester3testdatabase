<?php

namespace App\Http\Controllers;

use App\Models\PenggunaSiswa;
use App\Models\Sertifikat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SertifikatController extends Controller
{
    public function index()
    {
        $sertifikat = Sertifikat::with(['siswa', 'materi', 'guru'])
            ->latest()
            ->get();

        return view('admin.sertifikat.index', compact('sertifikat'));
    }

    /**
     * Mengembalikan daftar siswa dalam format JSON untuk filter dinamis di frontend.
     * Query params: kelas, jurusan
     */
    public function siswaJson(Request $request)
    {
        $query = PenggunaSiswa::orderBy('nama_lengkap');

        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        if ($request->filled('jurusan')) {
            $query->where('jurusan', $request->jurusan);
        }

        $siswa = $query->get(['nisn', 'nama_lengkap', 'kelas', 'jurusan']);

        return response()->json($siswa);
    }

    /**
     * Menerima POST dari index (daftar siswa terpilih), lalu menampilkan form upload.
     * Menyimpan daftar NISN ke session sehingga aman dari manipulasi URL.
     */
    public function create(Request $request)
    {
        $request->validate([
            'student_ids' => ['required', 'array', 'min:1'],
            'student_ids.*' => ['required', 'exists:pengguna_siswa,nisn'],
        ], [
            'student_ids.required' => 'Pilih minimal satu siswa terlebih dahulu.',
            'student_ids.min' => 'Pilih minimal satu siswa terlebih dahulu.',
        ]);

        $siswaTerpilih = PenggunaSiswa::whereIn('nisn', $request->student_ids)
            ->orderBy('nama_lengkap')
            ->get(['nisn', 'nama_lengkap', 'kelas', 'jurusan']);

        // Simpan ke session agar tidak bisa dimanipulasi di form selanjutnya
        session(['sertifikat_target_ids' => $siswaTerpilih->pluck('nisn')->toArray()]);

        return view('admin.sertifikat.create', compact('siswaTerpilih'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_materi' => ['nullable', 'exists:materi,id_materi'],
            'judul_sertifikat' => ['required', 'string', 'max:255'],
            'penerbit' => ['nullable', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'file_sertifikat' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ]);

        // Ambil NISN dari session (yang tersimpan saat create)
        $targetNisns = session('sertifikat_target_ids', []);

        if (empty($targetNisns)) {
            return redirect()->route('sertifikat.index')
                ->with('error', 'Sesi pilihan siswa telah habis. Silakan pilih siswa kembali.');
        }

        $uploadedFile = $request->file('file_sertifikat');
        $fileHash = hash_file('sha256', $uploadedFile->getRealPath());
        $nip = Auth::guard('admin')->user()->nip ?? null;
        $tanggalTerbit = now();
        $penerbit = $validated['penerbit'] ?? 'SMKN 2 Jember';

        // Simpan file utama sekali
        $originalPath = $uploadedFile->store('sertifikat/guru', 'public');
        $extension = $uploadedFile->getClientOriginalExtension();

        $jumlahBerhasil = 0;

        foreach ($targetNisns as $index => $nisn) {
            // Untuk siswa pertama pakai file asli, siswa berikutnya salin file
            if ($index === 0) {
                $filePath = $originalPath;
                $hash = $fileHash;
            } else {
                // Salin file dengan nama unik agar setiap siswa punya path berbeda
                $newName = 'sertifikat/guru/'.Str::uuid().'.'.$extension;
                Storage::disk('public')->copy($originalPath, $newName);
                $filePath = $newName;
                // Hash dibuat unik per salinan dengan menambahkan nisn agar tidak kena cek duplikat
                $hash = hash('sha256', $fileHash.$nisn);
            }

            Sertifikat::create([
                'nisn' => $nisn,
                'nip' => $nip,
                'id_materi' => null,
                'judul_sertifikat' => $validated['judul_sertifikat'],
                'penerbit' => $penerbit,
                'tanggal_terbit' => $tanggalTerbit,
                'deskripsi' => $validated['deskripsi'] ?? null,
                'file_sertifikat' => $filePath,
                'file_hash' => $hash,
                'tipe_sertifikat' => 'materi',
            ]);

            $jumlahBerhasil++;
        }

        // Hapus session setelah berhasil disimpan
        session()->forget('sertifikat_target_ids');

        return redirect()->route('sertifikat.index')
            ->with('success', "Sertifikat berhasil diterbitkan untuk {$jumlahBerhasil} siswa.");
    }

    public function destroy(Sertifikat $sertifikat)
    {
        if ($sertifikat->file_sertifikat && Storage::disk('public')->exists($sertifikat->file_sertifikat)) {
            Storage::disk('public')->delete($sertifikat->file_sertifikat);
        }

        $sertifikat->delete();

        return back()->with('success', 'Sertifikat berhasil dihapus.');
    }
}
