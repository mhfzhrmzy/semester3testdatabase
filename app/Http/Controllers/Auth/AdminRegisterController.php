<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AdminGuru;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminRegisterController extends Controller
{
    public function create(): View
    {
        return view('auth.admin-register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nip' => ['required', 'digits:18', 'unique:admin_guru,nip'],
            'nama_lengkap' => ['required', 'string', 'max:60', 'regex:/^[a-zA-Z\s]+$/'],
            'email' => ['required', 'email', 'max:255', 'unique:admin_guru,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'foto_profile' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'nip.required' => 'NIP wajib diisi.',
            'nip.digits' => 'NIP wajib tepat 18 digit angka.',
            'nip.unique' => 'NIP ini sudah terdaftar.',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_lengkap.regex' => 'Nama lengkap hanya boleh berisi huruf dan spasi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'foto_profile.image' => 'File foto profile harus berupa gambar.',
        ]);

        if ($request->hasFile('foto_profile')) {
            $validated['foto_profile'] = $request->file('foto_profile')->store('guru/foto', 'public');
        }

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'guru';

        $admin = AdminGuru::create($validated);

        Auth::guard('admin')->login($admin);
        $request->session()->regenerate();

        return redirect()->route('materi.index')->with('success', 'Registrasi guru berhasil! Selamat datang, ' . $admin->nama_lengkap . '.');
    }
}
