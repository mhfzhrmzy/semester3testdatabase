@extends('layouts.app')

@section('title', 'Daftar Materi')

@section('content')
<div class="container mx-auto p-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Form Tambah Materi -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-4">Tambah Materi</h2>
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded mb-4">
                    <p class="font-semibold text-sm mb-1">Gagal menyimpan materi:</p>
                    <ul class="list-disc list-inside text-sm space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('materi.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Diampu Oleh (Guru)</label>
                    <input type="text" value="{{ auth('admin')->user()->nama_lengkap ?? 'Guru Terautentikasi' }}" readonly class="w-full border rounded px-3 py-2 text-gray-600 bg-gray-100 cursor-not-allowed">
                    <input type="hidden" name="nip" value="{{ auth('admin')->id() }}">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Judul Materi</label>
                    <input type="text" name="judul_materi" class="w-full border rounded px-3 py-2 text-gray-700" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Isi Materi</label>
                    <textarea name="isi_materi" rows="4" class="w-full border rounded px-3 py-2 text-gray-700"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Upload File Modul
                        <span class="text-red-500 ml-0.5">*</span>
                        <span class="text-gray-400 font-normal text-xs ml-1">(PDF, PPT, PPTX, DOC, DOCX — maks. 25 MB)</span>
                    </label>
                    <input type="file" name="upload_file" accept=".pdf,.ppt,.pptx,.doc,.docx"
                           class="w-full text-sm text-gray-500 border rounded px-2 py-1.5 {{ $errors->has('upload_file') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    @error('upload_file')
                        <p class="text-red-600 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full">
                    Simpan Materi
                </button>
            </form>
        </div>

        <!-- Tabel Daftar Materi -->
        <div class="md:col-span-2 bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-4">Daftar Materi</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead>
                        <tr class="bg-gray-100 border-b">
                            <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Judul</th>
                            <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Diampu Oleh</th>
                            <th class="text-left py-3 px-4 uppercase font-semibold text-sm">File</th>
                            <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($materis ?? $materi ?? [] as $item)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4">{{ $item->judul_materi ?? $item->judul }}</td>
                                <td class="py-3 px-4">{{ $item->adminGuru->nama_lengkap ?? '-' }}</td>
                                <td class="py-3 px-4">
                                    @if($item->upload_file || $item->file)
                                        <a href="{{ route('materi.show', $item) }}" target="_blank" class="text-blue-600 hover:underline font-semibold">
                                            Lihat File
                                        </a>
                                    @else
                                        <span class="text-gray-400">Tidak ada file</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 flex items-center gap-3">
                                    {{-- Tombol Edit Materi --}}
                                    <a href="{{ route('materi.edit', $item) }}" class="text-amber-600 hover:text-amber-700 font-semibold text-sm">
                                        Edit
                                    </a>

                                    {{-- Form Hapus Materi --}}
                                    <form action="{{ route('materi.destroy', $item) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus materi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700 font-semibold text-sm">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-gray-500">Belum ada data materi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection