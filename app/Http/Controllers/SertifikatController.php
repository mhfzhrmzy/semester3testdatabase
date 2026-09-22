<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AdminGuru;
use App\Models\Materi;
use App\Models\PenggunaSiswa;
use App\Models\Sertifikat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SertifikatController extends Controller
{
    public function index()
    {
        $sertifikat = Sertifikat::with(['siswa', 'materi', 'guru'])
            ->latest()
            ->get();

        return view('admin.sertifikat.index', compact('sertifikat'));
    }

    public function create()
    {
        $siswa = PenggunaSiswa::orderBy('nama_lengkap', 'asc')->get();
        $materi = Materi::orderBy('judul_materi', 'asc')->get();

        return view('admin.sertifikat.create', compact('siswa', 'materi'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'nisn'             => ['required', 'exists:pengguna_siswa,nisn'],
        'id_materi'        => ['nullable', 'exists:materi,id_materi'],
        'judul_sertifikat' => ['required', 'string', 'max:255'],
        'penerbit'         => ['nullable', 'string', 'max:255'],
        'tanggal_terbit'   => ['nullable', 'date'],
        'deskripsi'        => ['nullable', 'string'],
        'file_sertifikat'  => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
    ]);

    $path = $request->file('file_sertifikat')->store('sertifikat/guru', 'public');

    Sertifikat::create([
        'nisn'             => $validated['nisn'],
        'nip'              => Auth::guard('admin')->user()->nip ?? null,
        'id_materi'        => null,
        'judul_sertifikat' => $validated['judul_sertifikat'],
        'penerbit'         => $validated['penerbit'] ?? 'SMKN 2 Jember',
        'tanggal_terbit'   => $validated['tanggal_terbit'] ?? now(),
        'deskripsi'        => $validated['deskripsi'] ?? null,
        'file_sertifikat'  => $path,
        'tipe_sertifikat'  => 'materi',
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