<?php

namespace App\Http\Controllers;

use App\Models\PenggunaSiswa;
use App\Models\Sertifikat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class SertifikatController extends Controller
{
    public function index()
    {
        $sertifikat = Sertifikat::with(['siswa', 'materi', 'guru'])
            ->latest()
            ->get();

        return view('admin.sertifikat.index', compact('sertifikat'));
    }

    public function create(Request $request)
    {
        $nisn = $request->query('nisn');

        if (! $nisn) {
            return redirect()->route('sertifikat.index')
                ->with('error', 'Silakan pilih siswa terlebih dahulu sebelum mengisi form sertifikat.');
        }

        $siswa = PenggunaSiswa::where('nisn', $nisn)->firstOrFail();

        return view('admin.sertifikat.create', compact('siswa'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nisn' => ['required', 'exists:pengguna_siswa,nisn'],
            'id_materi' => ['nullable', 'exists:materi,id_materi'],
            'judul_sertifikat' => ['required', 'string', 'max:255'],
            'penerbit' => ['nullable', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'file_sertifikat' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ]);

        // Cek duplikat berdasarkan hash file
        $uploadedFile = $request->file('file_sertifikat');
        $fileHash = hash_file('sha256', $uploadedFile->getRealPath());

        $duplicate = Sertifikat::where('file_hash', $fileHash)->first();
        if ($duplicate) {
            throw ValidationException::withMessages([
                'file_sertifikat' => 'File sertifikat ini sudah pernah diupload sebelumnya. Tidak boleh mengupload sertifikat yang sama (duplikat).',
            ]);
        }

        $path = $uploadedFile->store('sertifikat/guru', 'public');

        // tanggal_terbit selalu otomatis = waktu server saat ini, admin tidak bisa mengubahnya
        Sertifikat::create([
            'nisn' => $validated['nisn'],
            'nip' => Auth::guard('admin')->user()->nip ?? null,
            'id_materi' => null,
            'judul_sertifikat' => $validated['judul_sertifikat'],
            'penerbit' => $validated['penerbit'] ?? 'SMKN 2 Jember',
            'tanggal_terbit' => now(),
            'deskripsi' => $validated['deskripsi'] ?? null,
            'file_sertifikat' => $path,
            'file_hash' => $fileHash,
            'tipe_sertifikat' => 'materi',
        ]);

        return redirect()->route('sertifikat.index')->with('success', 'Sertifikat berhasil diterbitkan.');
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
