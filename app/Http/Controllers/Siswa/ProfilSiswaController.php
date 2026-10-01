<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfilSiswaController extends Controller
{
    public function index()
    {
        $siswa = auth('siswa')->user();

        return view('siswa.profil', compact('siswa'));
    }

    public function updateFoto(Request $request)
    {
        $request->validate([
            'foto' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $siswa = auth('siswa')->user();

        if ($siswa->foto && Storage::disk('public')->exists($siswa->foto)) {
            Storage::disk('public')->delete($siswa->foto);
        }

        $path = $request->file('foto')->store('profil', 'public');

        DB::table('pengguna_siswa')->where('nisn', $siswa->nisn)->update(['foto' => $path]);

        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }

    public function hapusFoto()
    {
        $siswa = auth('siswa')->user();

        if ($siswa->foto && Storage::disk('public')->exists($siswa->foto)) {
            Storage::disk('public')->delete($siswa->foto);
        }

        DB::table('pengguna_siswa')->where('nisn', $siswa->nisn)->update(['foto' => null]);

        return back()->with('success', 'Foto profil berhasil dihapus.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => ['required'],
            'password_baru' => ['required', 'string', 'min:8'],
        ]);

        $siswa = auth('siswa')->user();

        if (! Hash::check($request->password_lama, $siswa->password)) {
            return back()->withErrors(['password_lama' => 'Password lama tidak sesuai.']);
        }

        DB::table('pengguna_siswa')
            ->where('nisn', $siswa->nisn)
            ->update(['password' => Hash::make($request->password_baru)]);

        return back()->with('success', 'Password berhasil diubah.');
    }
}