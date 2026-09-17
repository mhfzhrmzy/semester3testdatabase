<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    // Tampilkan semua materi
    public function index()
    {
        $materis = Materi::latest()->get();
        return view('admin.materi.index', compact('materis'));
    }

    public function create()
    {
        return view('admin.materi.create');
    }

    // Simpan materi baru + upload file (jika ada)
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            // file modul: pdf/word/ppt, maksimal 10 MB
            'file_modul' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
        ]);

        $filePath = null;
        $fileName = null;

        if ($request->hasFile('file_modul')) {
            $file = $request->file('file_modul');
            $fileName = $file->getClientOriginalName();
            // disimpan di storage/app/public/modul, lalu path disimpan ke DB
            $filePath = $file->store('modul', 'public');
        }

        Materi::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'file_name' => $fileName,
            'file_path' => $filePath,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.materi.index')->with('success', 'Materi berhasil ditambahkan.');
    }

    public function edit(Materi $materi)
    {
        return view('admin.materi.edit', compact('materi'));
    }

    // Update materi, ganti file jika diupload file baru
    public function update(Request $request, Materi $materi)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file_modul' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
        ]);

        $data = [
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
        ];

        if ($request->hasFile('file_modul')) {
            // hapus file lama jika ada
            if ($materi->file_path) {
                Storage::disk('public')->delete($materi->file_path);
            }
            $file = $request->file('file_modul');
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_path'] = $file->store('modul', 'public');
        }

        $materi->update($data);

        return redirect()->route('admin.materi.index')->with('success', 'Materi berhasil diperbarui.');
    }

    public function destroy(Materi $materi)
    {
        if ($materi->file_path) {
            Storage::disk('public')->delete($materi->file_path);
        }
        $materi->delete();

        return redirect()->route('admin.materi.index')->with('success', 'Materi berhasil dihapus.');
    }
}
