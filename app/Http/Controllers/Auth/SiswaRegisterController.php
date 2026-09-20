<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PenggunaSiswa;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SiswaRegisterController extends Controller
{
    public function create(): View
    {
        return view('auth.siswa-register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nisn' => ['required', 'digits:10', 'unique:pengguna_siswa,nisn'],
            'nama_lengkap' => ['required', 'string', 'max:60', 'regex:/^[a-zA-Z\s]+$/'],
            'email' => ['required', 'email', 'max:255', 'unique:pengguna_siswa,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'nisn.required' => 'NISN wajib diisi.',
            'nisn.digits' => 'NISN wajib tepat 10 digit angka.',
            'nisn.unique' => 'NISN ini sudah terdaftar.',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_lengkap.regex' => 'Nama lengkap hanya boleh berisi huruf dan spasi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['poin'] = 0;

        $siswa = PenggunaSiswa::create($validated);

        Auth::guard('siswa')->login($siswa);
        $request->session()->regenerate();

        return redirect()->route('portal.materi.index')->with('success', 'Registrasi siswa berhasil! Selamat datang, ' . $siswa->nama_lengkap . '.');
    }
}
