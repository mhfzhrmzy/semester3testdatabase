@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Penerbitan Sertifikat Siswa</h2>
        <p class="text-sm text-gray-500 mt-1">Pilih siswa yang akan diberikan sertifikat penghargaan atau kelulusan.</p>
    </div>

    @if (session('error'))
        <div class="p-4 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="p-4 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Form Pilih Siswa Target --}}
    <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
        <form action="{{ route('sertifikat.create') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-4">
            <div class="flex-1 w-full">
                <label for="pilih_siswa" class="block text-sm font-semibold text-gray-700 mb-1.5">Pilih Siswa Target:</label>
                <select name="nisn" id="pilih_siswa" style="border: 1px solid #d1d5db;" class="w-full px-4 py-2.5 rounded-lg bg-white text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">-- Pilih Siswa yang Mau Diberi Sertifikat --</option>
                    @foreach (\App\Models\PenggunaSiswa::orderBy('nama_lengkap', 'asc')->get() as $s)
                        <option value="{{ $s->nisn }}">{{ $s->nama_lengkap }} (NISN: {{ $s->nisn }})</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full sm:w-auto pt-0 sm:pt-6">
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                    Lanjut Upload Sertifikat &rarr;
                </button>
            </div>
        </form>
    </div>

    {{-- Riwayat Sertifikat (Tanpa Kolom Modul) --}}
    <div>
        <h3 class="text-lg font-bold text-gray-900 mb-4">Riwayat Sertifikat yang Telah Diterbitkan Guru</h3>
        <div class="overflow-x-auto bg-white rounded-xl border border-gray-200">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="text-xs uppercase bg-gray-50 border-b border-gray-200 text-gray-600">
                    <tr>
                        <th class="px-5 py-3">Nama Siswa</th>
                        <th class="px-5 py-3">NISN</th>
                        <th class="px-5 py-3">Judul Sertifikat</th>
                        <th class="px-5 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($sertifikat as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3.5 font-semibold text-gray-900">{{ $item->siswa->nama_lengkap ?? '-' }}</td>
                            <td class="px-5 py-3.5">{{ $item->nisn }}</td>
                            <td class="px-5 py-3.5">{{ $item->judul_sertifikat }}</td>
                            <td class="px-5 py-3.5 text-center space-x-3">
                                <a href="{{ asset('storage/' . $item->file_sertifikat) }}" target="_blank" class="text-blue-600 hover:underline text-xs font-semibold">
                                    Lihat Berkas
                                </a>
                                <form action="{{ route('sertifikat.destroy', $item->id_sertifikat) }}" method="POST" class="inline" onsubmit="return confirm('Hapus sertifikat ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline text-xs">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-8 text-center text-gray-500">
                                Belum ada sertifikat yang diterbitkan oleh guru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection