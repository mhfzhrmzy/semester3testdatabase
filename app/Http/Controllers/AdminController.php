<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function index()
    {
        $admins = Admin::latest()->get();

        return view('admin.index', compact('admins'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip' => ['required', 'string', 'max:20', 'unique:admins,nip'],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
            'foto_profile' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        if ($request->hasFile('foto_profile')) {
            $validated['foto_profile'] = $request->file('foto_profile')->store('admin/foto', 'public');
        }

        $validated['password'] = Hash::make($validated['password']);

        Admin::create($validated);

        return redirect()
            ->route('admin.index')
            ->with('success', 'Data admin (guru) berhasil ditambahkan.');
    }

    public function edit(Admin $admin)
    {
        return view('admin.edit', compact('admin'));
    }

    public function update(Request $request, Admin $admin)
    {
        // PENTING: Gunakan Rule::unique dengan ->ignore($admin->nip, 'nip')
        $validated = $request->validate([
            'nip' => [
                'required', 
                'string', 
                'max:20', 
                Rule::unique('admins', 'nip')->ignore($admin->nip, 'nip')
            ],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:6'],
            'foto_profile' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        if ($request->hasFile('foto_profile')) {
            if ($admin->foto_profile) {
                Storage::disk('public')->delete($admin->foto_profile);
            }
            $validated['foto_profile'] = $request->file('foto_profile')->store('admin/foto', 'public');
        } else {
            unset($validated['foto_profile']);
        }

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $admin->update($validated);

        return redirect()
            ->route('admin.index')
            ->with('success', 'Data admin (guru) berhasil diperbarui.');
    }

    public function destroy(Admin $admin)
    {
        if ($admin->foto_profile) {
            Storage::disk('public')->delete($admin->foto_profile);
        }

        $admin->delete();

        return redirect()
            ->route('admin.index')
            ->with('success', 'Data admin (guru) berhasil dihapus.');
    }
}