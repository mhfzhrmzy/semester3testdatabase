<?php

namespace App\Http\Controllers;

use App\Models\AdminGuru;
use App\Models\Materi;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MateriController extends Controller
{
    /**
     * Menampilkan daftar materi di halaman Admin/Guru
     */
    public function index(): View
    {
        $materis = Materi::with('adminGuru')->latest()->get();
        $admins  = AdminGuru::orderBy('nama_lengkap', 'asc')->get();

        return view('materi.index', compact('materis', 'admins'));
    }

    /**
     * Menyimpan materi baru
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'judul_materi' => 'required|string|max:255',
            'nip'          => 'nullable|exists:admin_guru,nip',
            'isi_materi'   => 'nullable|string',
            'upload_file'  => 'nullable|mimes:pdf,pptx,ppt,doc,docx|max:25600',
        ]);

        $filePath = null;
        if ($request->hasFile('upload_file')) {
            $filePath = $request->file('upload_file')->store('materi', 'public');
        }

        $nip = $request->input('nip') ?: Auth::guard('admin')->id();

        Materi::create([
            'judul_materi' => $request->judul_materi,
            'nip'          => $nip,
            'isi_materi'   => $request->isi_materi,
            'upload_file'  => $filePath,
        ]);

        return redirect()->route('materi.index')->with('success', 'Materi berhasil ditambahkan!');
    }

    /**
     * Menampilkan/mengunduh file materi langsung untuk Admin/Guru
     */
    public function show(Materi $materi): BinaryFileResponse|RedirectResponse
    {
        if ($materi->upload_file && Storage::disk('public')->exists($materi->upload_file)) {
            return response()->file(Storage::disk('public')->path($materi->upload_file));
        }

        return redirect()->route('materi.index')->with('error', 'File materi tidak ditemukan.');
    }

    /**
     * Menampilkan form edit materi
     */
    public function edit(Materi $materi): View
    {
        $admins = AdminGuru::orderBy('nama_lengkap', 'asc')->get();

        return view('materi.edit', compact('materi', 'admins'));
    }

    /**
     * Memperbarui data materi
     */
    public function update(Request $request, Materi $materi): RedirectResponse
    {
        $request->validate([
            'judul_materi' => 'required|string|max:255',
            'nip'          => 'nullable|exists:admin_guru,nip',
            'isi_materi'   => 'nullable|string',
            'upload_file'  => 'nullable|mimes:pdf,pptx,ppt,doc,docx|max:25600',
        ]);

        $data = [
            'judul_materi' => $request->judul_materi,
            'nip'          => $request->input('nip') ?: $materi->nip,
            'isi_materi'   => $request->isi_materi,
        ];

        // Jika mengunggah file baru
        if ($request->hasFile('upload_file')) {
            // Hapus file lama jika ada
            if ($materi->upload_file && Storage::disk('public')->exists($materi->upload_file)) {
                Storage::disk('public')->delete($materi->upload_file);
            }

            // Simpan file baru
            $data['upload_file'] = $request->file('upload_file')->store('materi', 'public');
        }

        $materi->update($data);

        return redirect()->route('materi.index')->with('success', 'Materi berhasil diperbarui!');
    }

    /**
     * Menghapus materi
     */
    public function destroy(Materi $materi): RedirectResponse
    {
        if ($materi->upload_file && Storage::disk('public')->exists($materi->upload_file)) {
            Storage::disk('public')->delete($materi->upload_file);
        }

        $materi->delete();

        return redirect()->route('materi.index')->with('success', 'Materi berhasil dihapus!');
    }
}