@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 sm:p-8">
                <div class="border-b border-gray-100 pb-5 mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Upload Sertifikat Pribadi</h2>
                    <p class="text-sm text-gray-500 mt-1">Unggah dokumen sertifikat prestasi atau kegiatan mandiri Anda.</p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('siswa.sertifikat.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    {{-- Judul Sertifikat --}}
                    <div>
                        <label for="judul_sertifikat" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Judul Sertifikat <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="judul_sertifikat" 
                            id="judul_sertifikat" 
                            value="{{ old('judul_sertifikat') }}"
                            placeholder="Contoh: Juara 1 Desain Poster Digital" 
                            style="border: 1px solid #cbd5e1;"
                            class="w-full px-4 py-2.5 rounded-lg text-sm text-gray-900 bg-white focus:ring-2 focus:ring-red-500 focus:outline-none transition shadow-sm"
                            required
                        >
                    </div>

                    {{-- Dua Kolom: Penerbit & Tanggal Terbit --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="penerbit" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Penerbit / Penyelenggara
                            </label>
                            <input 
                                type="text" 
                                name="penerbit" 
                                id="penerbit" 
                                value="{{ old('penerbit') }}"
                                placeholder="Contoh: SMKN 2 Jember" 
                                style="border: 1px solid #cbd5e1;"
                                class="w-full px-4 py-2.5 rounded-lg text-sm text-gray-900 bg-white focus:ring-2 focus:ring-red-500 focus:outline-none transition shadow-sm"
                            >
                        </div>
                        <div>
                            <label for="tanggal_terbit" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Tanggal Terbit
                            </label>
                            <input 
                                type="date" 
                                name="tanggal_terbit" 
                                id="tanggal_terbit" 
                                value="{{ old('tanggal_terbit') }}"
                                style="border: 1px solid #cbd5e1;"
                                class="w-full px-4 py-2.5 rounded-lg text-sm text-gray-900 bg-white focus:ring-2 focus:ring-red-500 focus:outline-none transition shadow-sm"
                            >
                        </div>
                    </div>

                    {{-- Deskripsi Singkat --}}
                    <div>
                        <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Deskripsi Singkat (Opsional)
                        </label>
                        <textarea 
                            name="deskripsi" 
                            id="deskripsi" 
                            rows="3" 
                            placeholder="Keterangan singkat mengenai kompetensi atau capaian..." 
                            style="border: 1px solid #cbd5e1;"
                            class="w-full px-4 py-2.5 rounded-lg text-sm text-gray-900 bg-white focus:ring-2 focus:ring-red-500 focus:outline-none transition shadow-sm"
                        >{{ old('deskripsi') }}</textarea>
                    </div>

                    {{-- File Input --}}
                    <div>
                        <label for="file_sertifikat" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Berkas Sertifikat (PDF / JPG / PNG, Max 2MB) <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="file" 
                            name="file_sertifikat" 
                            id="file_sertifikat" 
                            accept=".pdf,.jpg,.jpeg,.png"
                            style="border: 1px solid #cbd5e1;"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 bg-white rounded-lg p-1.5 cursor-pointer shadow-sm"
                            required
                        >
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="pt-4 border-t border-gray-100 flex items-center justify-end space-x-3">
                        <a 
                            href="{{ route('siswa.sertifikat.index') }}" 
                            class="px-4 py-2.5 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-100 transition"
                        >
                            Batal
                        </a>
                        <button 
                            type="submit" 
                            class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg text-sm shadow transition duration-150 ease-in-out"
                        >
                            Unggah Sertifikat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection