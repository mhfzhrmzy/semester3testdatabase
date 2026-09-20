<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function create()
    {
        return view('auth.admin-login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'nip' => ['required', 'digits:18'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            return back()->withInput($request->only('nip'))->withErrors(['nip' => 'NIP atau password salah.']);
        }

        $request->session()->regenerate();
        $admin = Auth::guard('admin')->user();

        return $admin->role === 'superadmin'
            ? redirect()->route('superadmin.index')
            : redirect()->route('materi.index');
    }

    public function destroy(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}