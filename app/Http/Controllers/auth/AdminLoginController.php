<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function create(): View
    {
        return view('auth.admin-login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'nip' => ['required', 'digits:18'],
            'password' => ['required', 'string'],
        ], [
            'nip.required' => 'NIP wajib diisi.',
            'nip.digits' => 'NIP wajib 18 digit angka.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (! Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            return back()->withInput($request->only('nip'))->withErrors(['nip' => 'NIP atau password salah.']);
        }

        $request->session()->regenerate();
        $admin = Auth::guard('admin')->user();

        return $admin->role === 'superadmin'
            ? redirect()->route('superadmin.index')->with('success', 'Selamat datang kembali, Superadmin ' . $admin->nama_lengkap . '.')
            : redirect()->route('materi.index')->with('success', 'Selamat datang kembali, Guru ' . $admin->nama_lengkap . '.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah berhasil logout.');
    }
}