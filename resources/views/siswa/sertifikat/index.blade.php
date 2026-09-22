@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Daftar Sertifikat & Portofolio</h2>
                <p class="text-sm text-gray-500 mt-1">Koleksi sertifikat kelulusan modul dan sertifikat mandiri Anda.</p>
            </div>
            <a 
                href="{{ route('siswa.sertifikat.create') }}" 
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-sm transition"
            >
                + Upload Sertifikat Baru
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        {{-- Grid Kartu Sertifikat --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @forelse ($sertifikat as $item)
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs px-2.5 py-0.5 rounded-full font-semibold {{ $item->tipe_sertifikat === 'materi' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $item->tipe_sertifikat === 'materi' ? 'Kelulusan Modul' : 'Mandiri' }}
                            </span>
                            <span class="text-xs text-gray-400">{{ $item->tanggal_terbit ?? '-' }}</span>
                        </div>
                        <h4 class="font-bold text-gray-900 text-base leading-snug">{{ $item->judul_sertifikat }}</h4>
                        <p class="text-xs text-gray-500 mt-1">Penerbit: {{ $item->penerbit ?? '-' }}</p>
                        @if ($item->materi)
                            <p class="text-xs text-blue-600 mt-1 font-medium">Modul: {{ $item->materi->judul_materi }}</p>
                        @endif
                        @if ($item->deskripsi)
                            <p class="text-xs text-gray-600 mt-2 bg-gray-50 p-2 rounded">{{ $item->deskripsi }}</p>
                        @endif
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
                        <a href="{{ asset('storage/' . $item->file_sertifikat) }}" target="_blank" class="text-xs font-semibold text-blue-600 hover:text-blue-800">
                            Lihat Berkas
                        </a>
                        @if ($item->tipe_sertifikat === 'mandiri')
                            <form action="{{ route('siswa.sertifikat.destroy', $item->id_sertifikat) }}" method="POST" onsubmit="return confirm('Hapus sertifikat ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-500 hover:text-red-700">Hapus</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white p-8 text-center text-sm text-gray-500 rounded-xl border border-dashed border-gray-300">
                    Belum ada sertifikat yang diunggah. Klik tombol <b>+ Upload Sertifikat Baru</b> di atas untuk menambahkan berkas.
                </div>
            @endforelse
        </div>

    </div>
</div>
@endsection