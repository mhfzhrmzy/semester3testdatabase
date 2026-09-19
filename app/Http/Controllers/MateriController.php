<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    /**
     * Menampilkan daftar materi di halaman Admin/Guru
     */
    public function index()
    {
        $materis = Materi::with('admin')->latest()->get();
        $admins  = Admin::orderBy('nama_lengkap', 'asc')->get();

        return view('materi.index', compact('materis', 'admins'));
    }

    /**
     * Menyimpan materi baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul_materi' => 'required|string|max:255',
            'admin_id'     => 'nullable|exists:admins,id',
            'isi_materi'   => 'nullable|string',
            'upload_file'  => 'nullable|mimes:pdf,pptx,ppt,doc,docx|max:25600',
        ]);

        $filePath = null;
        if ($request->hasFile('upload_file')) {
            $filePath = $request->file('upload_file')->store('materi', 'public');
        }

        Materi::create([
            'judul_materi' => $request->judul_materi,
            'admin_id'     => $request->admin_id,
            'isi_materi'   => $request->isi_materi,
            'upload_file'  => $filePath,
        ]);

        return redirect()->route('materi.index')->with('success', 'Materi berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit materi
     */
    public function edit(Materi $materi)
    {
        $admins = Admin::orderBy('nama_lengkap', 'asc')->get();

        return view('materi.edit', compact('materi', 'admins'));
    }

    /**
     * Memperbarui data materi
     */
    public function update(Request $request, Materi $materi)
    {
        $request->validate([
            'judul_materi' => 'required|string|max:255',
            'admin_id'     => 'nullable|exists:admins,id',
            'isi_materi'   => 'nullable|string',
            'upload_file'  => 'nullable|mimes:pdf,pptx,ppt,doc,docx|max:25600',
        ]);

        $data = [
            'judul_materi' => $request->judul_materi,
            'admin_id'     => $request->admin_id,
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
    public function destroy(Materi $materi)
    {
        if ($materi->upload_file && Storage::disk('public')->exists($materi->upload_file)) {
            Storage::disk('public')->delete($materi->upload_file);
        }

        $materi->delete();

        return redirect()->route('materi.index')->with('success', 'Materi berhasil dihapus!');
    }
}