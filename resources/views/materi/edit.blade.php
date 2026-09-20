@extends('layouts.app')

@section('title', 'Edit Materi')

@section('content')
<div class="container mx-auto p-6">
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold">Edit Data Materi</h2>
            <a href="{{ route('materi.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali ke Daftar Materi</a>
        </div>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('materi.update', $materi) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Diampu Oleh (Admin/Guru)</label>
                <select name="nip" class="w-full border rounded px-3 py-2 text-gray-700">
                    <option value="">-- Pilih Guru --</option>
                    @foreach($admins as $admin)
                        <option value="{{ $admin->nip }}" {{ old('nip', $materi->nip) == $admin->nip ? 'selected' : '' }}>
                            {{ $admin->nama_lengkap }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Judul Materi</label>
                <input type="text" name="judul_materi" value="{{ old('judul_materi', $materi->judul_materi) }}" class="w-full border rounded px-3 py-2 text-gray-700" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Isi Materi</label>
                <textarea name="isi_materi" rows="5" class="w-full border rounded px-3 py-2 text-gray-700">{{ old('isi_materi', $materi->isi_materi) }}</textarea>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Ganti File Modul (PDF/Word/PPT)</label>
                <input type="file" name="upload_file" class="w-full text-sm text-gray-500 mb-2">
                
                @if($materi->upload_file)
                    <div class="p-3 bg-gray-50 border rounded text-xs text-gray-600 flex items-center justify-between">
                        <span>File Terpasang: <strong>{{ basename($materi->upload_file) }}</strong></span>
                        <a href="{{ route('portal.materi.show', $materi) }}" target="_blank" class="text-blue-600 hover:underline">Preview File</a>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">* Biarkan kosong jika tidak ingin mengganti file yang ada.</p>
                @endif
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Simpan Perubahan
                </button>
                <a href="{{ route('materi.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection