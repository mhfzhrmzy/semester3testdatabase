<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    public function index()
    {
        // Eager load relasi admin supaya tidak N+1 query
        $materis = Materi::with('admin')->latest()->get();
        $admins = Admin::orderBy('nama_lengkap')->get();

        return view('materi.index', compact('materis', 'admins'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip' => ['required', 'string', 'exists:admins,nip'],
            'judul_materi' => ['required', 'string', 'max:255'],
            'isi_materi' => ['required', 'string'],
            'upload_file' => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx', 'max:10240'],
        ]);

        if ($request->hasFile('upload_file')) {
            $validated['upload_file'] = $request->file('upload_file')->store('materi/file', 'public');
        }

        Materi::create($validated);

        return redirect()
            ->route('materi.index')
            ->with('success', 'Materi berhasil ditambahkan.');
    }

    public function destroy(Materi $materi)
    {
        if ($materi->upload_file) {
            Storage::disk('public')->delete($materi->upload_file);
        }

        $materi->delete();

        return redirect()
            ->route('materi.index')
            ->with('success', 'Materi berhasil dihapus.');
    }
}