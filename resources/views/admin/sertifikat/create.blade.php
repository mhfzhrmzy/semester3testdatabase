@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto my-4 p-6 bg-white rounded-xl border border-gray-200 shadow-sm">
    <div class="border-b border-gray-200 pb-4 mb-6">
        <h2 class="text-xl font-bold text-gray-900">Terbitkan Sertifikat untuk Siswa</h2>
        <p class="text-sm text-gray-500 mt-1">Unggah berkas sertifikat resmi yang akan diberikan langsung ke akun siswa.</p>
    </div>

    @if ($errors->any())
        <div class="mb-5 p-4 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('sertifikat.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        {{-- Pilihan Siswa Penerima --}}
        <div>
            <label for="nisn" class="block text-sm font-semibold text-gray-700 mb-1">
                Siswa Penerima <span class="text-red-500">*</span>
            </label>
            <select 
                name="nisn" 
                id="nisn" 
                style="border: 1px solid #cbd5e1;"
                class="w-full px-3.5 py-2.5 bg-gray-50 rounded-lg text-sm text-gray-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
            >
                <option value="">-- Pilih Siswa Penerima --</option>
                @foreach ($siswa as $s)
                    <option value="{{ $s->nisn }}" {{ (old('nisn', $selectedNisn ?? '') == $s->nisn) ? 'selected' : '' }}>
                        {{ $s->nama_lengkap }} (NISN: {{ $s->nisn }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Judul Sertifikat --}}
        <div>
            <label for="judul_sertifikat" class="block text-sm font-semibold text-gray-700 mb-1">
                Judul Sertifikat <span class="text-red-500">*</span>
            </label>
            <input 
                type="text" 
                name="judul_sertifikat" 
                id="judul_sertifikat"
                value="{{ old('judul_sertifikat') }}"
                placeholder="Contoh: Sertifikat Penyelesaian Pembelajaran"
                style="border: 1px solid #cbd5e1;"
                class="w-full px-3.5 py-2.5 bg-gray-50 rounded-lg text-sm text-gray-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
            >
        </div>

        {{-- Grid Penerbit & Tanggal Terbit --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="penerbit" class="block text-sm font-semibold text-gray-700 mb-1">
                    Instansi Penerbit
                </label>
                <input 
                    type="text" 
                    name="penerbit" 
                    id="penerbit"
                    value="{{ old('penerbit', 'SMKN 2 Jember') }}"
                    style="border: 1px solid #cbd5e1;"
                    class="w-full px-3.5 py-2.5 bg-gray-50 rounded-lg text-sm text-gray-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>

            <div>
                <label for="tanggal_terbit" class="block text-sm font-semibold text-gray-700 mb-1">
                    Tanggal Terbit
                </label>
                <input 
                    type="date" 
                    name="tanggal_terbit" 
                    id="tanggal_terbit"
                    value="{{ old('tanggal_terbit', date('Y-m-d')) }}"
                    style="border: 1px solid #cbd5e1;"
                    class="w-full px-3.5 py-2.5 bg-gray-50 rounded-lg text-sm text-gray-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>
        </div>

        {{-- Catatan / Deskripsi --}}
        <div>
            <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-1">
                Deskripsi / Catatan (Opsional)
            </label>
            <textarea 
                name="deskripsi" 
                id="deskripsi" 
                rows="3"
                placeholder="Catatan prestasi atau rincian pencapaian siswa..."
                style="border: 1px solid #cbd5e1;"
                class="w-full px-3.5 py-2.5 bg-gray-50 rounded-lg text-sm text-gray-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500"
            >{{ old('deskripsi') }}</textarea>
        </div>

        {{-- Berkas File --}}
        <div>
            <label for="file_sertifikat" class="block text-sm font-semibold text-gray-700 mb-1">
                Berkas Sertifikat <span class="text-red-500">*</span>
            </label>
            <input 
                type="file" 
                name="file_sertifikat" 
                id="file_sertifikat"
                accept=".pdf,.jpg,.jpeg,.png"
                style="border: 1px solid #cbd5e1;"
                class="w-full text-sm text-gray-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 bg-white rounded-lg p-1.5 cursor-pointer"
                required
            >
            <span class="text-xs text-gray-500 mt-1 block">Format: PDF, JPG, JPEG, PNG (Maksimal 2 MB)</span>
        </div>

        {{-- Tombol Submit --}}
        <div class="pt-4 flex items-center justify-end space-x-3 border-t border-gray-200">
            <a 
                href="{{ route('sertifikat.index') }}" 
                class="px-4 py-2.5 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-100 transition"
            >
                Batal
            </a>
            <button 
                type="submit" 
                class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-sm transition"
            >
                Terbitkan Sertifikat
            </button>
        </div>
    </form>
</div>
@endsection