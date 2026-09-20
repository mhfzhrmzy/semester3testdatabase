<?php

namespace App\Http\Controllers;

use App\Models\PenggunaSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PenggunaSiswaController extends Controller
{
    public function index()
    {
        $siswas = PenggunaSiswa::latest()->get();

        return view('siswa.index', compact('siswas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nisn' => ['required', 'digits:10', 'unique:pengguna_siswa,nisn'],
            'nama_lengkap' => ['required', 'string', 'max:60', 'regex:/^[a-zA-Z\s]+$/'],
            'email' => ['required', 'email', 'max:255', 'unique:pengguna_siswa,email'],
            'password' => ['required', 'string', 'min:6'],
        ], [
            'nisn.digits' => 'NISN wajib tepat 10 digit angka.',
            'nama_lengkap.regex' => 'Nama lengkap hanya boleh berisi huruf dan spasi.',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['poin'] = 0;

        PenggunaSiswa::create($validated);

        return redirect()->route('siswa.index')->with('success', 'Akun siswa berhasil ditambahkan.');
    }

    public function update(Request $request, PenggunaSiswa $siswa)
    {
        $validated = $request->validate([
            'nisn' => ['required', 'digits:10', Rule::unique('pengguna_siswa', 'nisn')->ignore($siswa->nisn, 'nisn')],
            'nama_lengkap' => ['required', 'string', 'max:60', 'regex:/^[a-zA-Z\s]+$/'],
            'email' => ['required', 'email', 'max:255', Rule::unique('pengguna_siswa', 'email')->ignore($siswa->nisn, 'nisn')],
            'password' => ['nullable', 'string', 'min:6'],
        ], [
            'nisn.digits' => 'NISN wajib tepat 10 digit angka.',
            'nama_lengkap.regex' => 'Nama lengkap hanya boleh berisi huruf dan spasi.',
        ]);

        $validated['password'] = $request->filled('password')
            ? Hash::make($validated['password'])
            : $siswa->password;

        $siswa->update($validated);

        return redirect()->route('siswa.index')->with('success', 'Akun siswa berhasil diperbarui.');
    }

    public function destroy(PenggunaSiswa $siswa)
    {
        $siswa->delete();

        return redirect()->route('siswa.index')->with('success', 'Akun siswa berhasil dihapus.');
    }
}