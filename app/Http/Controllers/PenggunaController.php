<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
    public function index()
    {
        $siswas = Pengguna::latest()->get();

        return view('siswa.index', compact('siswas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nisn_pengguna' => ['required', 'string', 'max:20', 'unique:penggunas,nisn_pengguna'],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:penggunas,email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['poin'] = 0;

        Pengguna::create($validated);

        return redirect()
            ->route('siswa.index')
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function destroy(Pengguna $siswa)
    {
        $siswa->delete();

        return redirect()
            ->route('siswa.index')
            ->with('success', 'Data siswa berhasil dihapus.');
    }
}