<?php

namespace App\Http\Controllers;

use App\Models\AdminGuru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminGuruController extends Controller
{
    public function index()
    {
        $gurus = AdminGuru::latest()->get();

        return view('admin.index', compact('gurus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip' => ['required', 'digits:18', 'unique:admin_guru,nip'],
            'nama_lengkap' => ['required', 'string', 'max:60', 'regex:/^[a-zA-Z\s]+$/'],
            'email' => ['required', 'email', 'max:255', 'unique:admin_guru,email'],
            'password' => ['required', 'string', 'min:6'],
            'foto_profile' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'nip.digits' => 'NIP wajib tepat 18 digit angka.',
            'nama_lengkap.regex' => 'Nama lengkap hanya boleh berisi huruf dan spasi.',
        ]);

        if ($request->hasFile('foto_profile')) {
            $validated['foto_profile'] = $request->file('foto_profile')->store('guru/foto', 'public');
        }

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'guru';

        AdminGuru::create($validated);

        return redirect()->route('admin.index')->with('success', 'Akun guru berhasil ditambahkan.');
    }

    public function update(Request $request, AdminGuru $guru)
    {
        $validated = $request->validate([
            'nip' => ['required', 'digits:18', Rule::unique('admin_guru', 'nip')->ignore($guru->nip, 'nip')],
            'nama_lengkap' => ['required', 'string', 'max:60', 'regex:/^[a-zA-Z\s]+$/'],
            'email' => ['required', 'email', 'max:255', Rule::unique('admin_guru', 'email')->ignore($guru->nip, 'nip')],
            'password' => ['nullable', 'string', 'min:6'],
            'foto_profile' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'nip.digits' => 'NIP wajib tepat 18 digit angka.',
            'nama_lengkap.regex' => 'Nama lengkap hanya boleh berisi huruf dan spasi.',
        ]);

        if ($request->hasFile('foto_profile')) {
            if ($guru->foto_profile) {
                Storage::disk('public')->delete($guru->foto_profile);
            }
            $validated['foto_profile'] = $request->file('foto_profile')->store('guru/foto', 'public');
        } else {
            unset($validated['foto_profile']);
        }

        $validated['password'] = $request->filled('password')
            ? Hash::make($validated['password'])
            : $guru->password;

        $guru->update($validated);

        return redirect()->route('admin.index')->with('success', 'Akun guru berhasil diperbarui.');
    }

    public function destroy(AdminGuru $guru)
    {
        if ($guru->foto_profile) {
            Storage::disk('public')->delete($guru->foto_profile);
        }

        $guru->delete();

        return redirect()->route('admin.index')->with('success', 'Akun guru berhasil dihapus.');
    }
}