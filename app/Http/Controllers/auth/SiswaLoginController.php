<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaLoginController extends Controller
{
    public function create()
    {
        return view('auth.siswa-login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'nisn' => ['required', 'digits:10'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('siswa')->attempt($credentials, $request->boolean('remember'))) {
            return back()->withInput($request->only('nisn'))->withErrors(['nisn' => 'NISN atau password salah.']);
        }

        $request->session()->regenerate();

        return redirect()->route('portal.materi.index');
    }

    public function destroy(Request $request)
    {
        Auth::guard('siswa')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}