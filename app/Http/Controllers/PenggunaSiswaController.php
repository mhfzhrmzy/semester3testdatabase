<?php

namespace App\Http\Controllers;

use App\Models\PenggunaSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    public function importCsv(Request $request)
    {
        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
        ], [
            'csv_file.required' => 'File CSV wajib diunggah.',
            'csv_file.mimes' => 'Format file harus berupa .csv',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');

        if (! $handle) {
            return back()->with('error', 'Gagal membaca file CSV.');
        }

        $header = fgetcsv($handle, 2000, ',');

        if ($header && count($header) === 1 && str_contains($header[0], ';')) {
            rewind($handle);
            $header = fgetcsv($handle, 2000, ';');
            $delimiter = ';';
        } else {
            $delimiter = ',';
        }

        $importedCount = 0;
        $jurusanList = [
            'Teknik Alat Berat', 'Teknik Kendaraan Ringan', 'Teknik Sepeda Motor',
            'Teknik Pemesinan', 'Teknik Instalasi Listrik', 'Teknik Pembangkit Listrik',
            'Teknik Mekatronika', 'Teknik Audio Video', 'Teknik Komputer & Jaringan',
            'Teknik Konstruksi & Perumahan', 'Desain Permodelan & Informasi Bangunan',
            'Desain Komunikasi Visual',
        ];

        while (($row = fgetcsv($handle, 2000, $delimiter)) !== false) {
            if (empty(array_filter($row))) {
                continue;
            }

            $nisnRaw = trim($row[0] ?? '');
            if (strtolower($nisnRaw) === 'nisn') {
                continue;
            }

            $digitsOnly = preg_replace('/[^0-9]/', '', $nisnRaw);
            $nisn = str_pad($digitsOnly, 10, '0', STR_PAD_LEFT);
            $namaLengkap = trim($row[1] ?? '');
            $kelas = trim($row[2] ?? '');
            $jurusan = trim($row[3] ?? '');
            $passwordRaw = trim($row[4] ?? '');

            if (strlen($nisn) === 10 && $namaLengkap !== '') {
                if (! in_array($kelas, ['10', '11', '12'], true)) {
                    $kelas = '10';
                }

                if (! in_array($jurusan, $jurusanList, true)) {
                    $jurusan = 'Teknik Komputer & Jaringan';
                }

                $password = $passwordRaw !== '' ? Hash::make($passwordRaw) : Hash::make('password123');

                PenggunaSiswa::updateOrCreate(
                    ['nisn' => $nisn],
                    [
                        'nama_lengkap' => $namaLengkap,
                        'kelas' => $kelas,
                        'jurusan' => $jurusan,
                        'password' => $password,
                        'poin' => 0,
                    ]
                );

                $importedCount++;
            }
        }

        fclose($handle);

        $redirectRoute = $request->input('from') === 'superadmin' ? 'superadmin.index' : 'siswa.index';
        $redirectParameters = $request->input('from') === 'superadmin' ? ['menu' => 'siswa'] : [];

        return redirect()->route($redirectRoute, $redirectParameters)
            ->with('success', "Berhasil meng-import {$importedCount} data akun siswa dari file CSV.");
    }

    public function downloadTemplate(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_data_siswa.csv"',
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['nisn', 'nama_lengkap', 'kelas', 'jurusan', 'password']);
            fputcsv($handle, ['0051234567', 'Ahmad Subagyo', '10', 'Teknik Komputer & Jaringan', 'password123']);
            fputcsv($handle, ['0051234568', 'Budi Utomo', '11', 'Desain Komunikasi Visual', 'password123']);
            fclose($handle);
        }, 200, $headers);
    }
}
