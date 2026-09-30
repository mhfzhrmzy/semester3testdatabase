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

    public function edit(PenggunaSiswa $siswa)
    {
        return view('siswa.edit', compact('siswa'));
    }

    public function store(Request $request)
    {
        $jurusanList = [
            'Teknik Alat Berat', 'Teknik Kendaraan Ringan', 'Teknik Sepeda Motor',
            'Teknik Pemesinan', 'Teknik Instalasi Listrik', 'Teknik Pembangkit Listrik',
            'Teknik Mekatronika', 'Teknik Audio Video', 'Teknik Komputer & Jaringan',
            'Teknik Konstruksi & Perumahan', 'Desain Permodelan & Informasi Bangunan',
            'Desain Komunikasi Visual',
        ];

        $validated = $request->validate([
            'nisn' => ['required', 'digits:10', 'unique:pengguna_siswa,nisn'],
            'nama_lengkap' => ['required', 'string', 'max:60', 'regex:/^[a-zA-Z\s]+$/'],
            'kelas' => ['required', 'in:10,11,12'],
            'jurusan' => ['required', 'in:'.implode(',', $jurusanList)],
            'password' => ['required', 'string', 'min:6'],
        ], [
            'nisn.digits' => 'NISN wajib tepat 10 digit angka.',
            'nama_lengkap.regex' => 'Nama lengkap hanya boleh berisi huruf dan spasi.',
            'kelas.required' => 'Kelas wajib dipilih.',
            'jurusan.required' => 'Jurusan wajib dipilih.',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['poin'] = 0;

        PenggunaSiswa::create($validated);

        $redirectRoute = $request->input('from') === 'superadmin' ? 'superadmin.index' : 'siswa.index';
        $redirectParameters = $request->input('from') === 'superadmin' ? ['menu' => 'siswa'] : [];

        return redirect()->route($redirectRoute, $redirectParameters)->with('success', 'Akun siswa berhasil ditambahkan.');
    }

    public function update(Request $request, PenggunaSiswa $siswa)
    {
        $jurusanList = [
            'Teknik Alat Berat', 'Teknik Kendaraan Ringan', 'Teknik Sepeda Motor',
            'Teknik Pemesinan', 'Teknik Instalasi Listrik', 'Teknik Pembangkit Listrik',
            'Teknik Mekatronika', 'Teknik Audio Video', 'Teknik Komputer & Jaringan',
            'Teknik Konstruksi & Perumahan', 'Desain Permodelan & Informasi Bangunan',
            'Desain Komunikasi Visual',
        ];

        $validated = $request->validate([
            'nisn' => ['required', 'digits:10', Rule::unique('pengguna_siswa', 'nisn')->ignore($siswa->nisn, 'nisn')],
            'nama_lengkap' => ['required', 'string', 'max:60', 'regex:/^[a-zA-Z\s]+$/'],
            'kelas' => ['required', 'in:10,11,12'],
            'jurusan' => ['required', 'in:'.implode(',', $jurusanList)],
            'password' => ['nullable', 'string', 'min:6'],
        ], [
            'nisn.digits' => 'NISN wajib tepat 10 digit angka.',
            'nama_lengkap.regex' => 'Nama lengkap hanya boleh berisi huruf dan spasi.',
            'kelas.required' => 'Kelas wajib dipilih.',
            'jurusan.required' => 'Jurusan wajib dipilih.',
        ]);

        $validated['password'] = $request->filled('password')
            ? Hash::make($validated['password'])
            : $siswa->password;

        $siswa->update($validated);

        $redirectRoute = $request->input('from') === 'superadmin' ? 'superadmin.index' : 'siswa.index';
        $redirectParameters = $request->input('from') === 'superadmin' ? ['menu' => 'siswa'] : [];

        return redirect()->route($redirectRoute, $redirectParameters)->with('success', 'Akun siswa berhasil diperbarui.');
    }

    public function destroy(PenggunaSiswa $siswa)
    {
        $siswa->delete();

        $redirectRoute = request()->input('from') === 'superadmin' ? 'superadmin.index' : 'siswa.index';
        $redirectParameters = request()->input('from') === 'superadmin' ? ['menu' => 'siswa'] : [];

        return redirect()->route($redirectRoute, $redirectParameters)->with('success', 'Akun siswa berhasil dihapus.');
    }
}
