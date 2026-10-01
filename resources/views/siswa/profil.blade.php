@extends('layouts.app')

@section('title', 'Profil')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Profil & Portofolio</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- KOLOM KIRI --}}
        <div class="space-y-6">

            {{-- Kartu profil --}}
            <div class="border rounded-lg p-6 text-center">
                <div class="w-24 h-24 mx-auto rounded-full bg-gray-300 flex items-center justify-center font-bold text-xl">
                    {{ strtoupper(substr($siswa->nama_lengkap, 0, 3)) }}
                </div>

                <h2 class="mt-3 text-xl font-bold">{{ $siswa->nama_lengkap }}</h2>
                <p class="text-sm text-gray-500">NISN: {{ $siswa->nisn }}</p>

                <div class="bg-blue-50 rounded-lg p-3 mt-4 text-left">
                    <p class="text-xs text-gray-500">Total Poin</p>
                    <p class="font-bold">{{ $siswa->poin }}</p>
                </div>

                <div class="flex justify-between text-sm mt-4">
                    <span class="text-gray-500">Bergabung</span>
                    <span>{{ $siswa->created_at->translatedFormat('F Y') }}</span>
                </div>
                <div class="flex justify-between text-sm mt-2">
                    <span class="text-gray-500">Status Akun</span>
                    <span class="bg-blue-100 text-xs rounded-full px-2 py-1">Siswa Aktif</span>
                </div>
            </div>

            {{-- Data pokok --}}
            <div class="border rounded-lg p-6 space-y-3">
                <h3 class="font-bold text-lg">Data Pokok Siswa</h3>

                @foreach ([
                    'Nama Lengkap Siswa' => $siswa->nama_lengkap,
                    'NISN'               => $siswa->nisn,
                    'Alamat Email'       => $siswa->email,
                    'No. WhatsApp'       => $siswa->no_wa ?? '-',
                    'Jurusan'            => $siswa->jurusan ?? '-',
                ] as $label => $nilai)
                    <div>
                        <p class="text-sm text-gray-600">{{ $label }}</p>
                        <div class="bg-blue-50 rounded-lg px-3 py-2 font-medium">{{ $nilai }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="lg:col-span-2">
            <h3 class="font-bold text-lg">Sertifikat Saya</h3>
            <p class="text-sm text-gray-500">Dokumen yang tercatat atas nama kamu.</p>

            <div class="grid md:grid-cols-2 gap-4 mt-4">
                @forelse ($sertifikat as $s)
                    <div class="border rounded-lg p-4">
                        <span class="text-xs bg-amber-100 rounded px-2 py-1">
                            {{ $s->tipe_sertifikat === 'mandiri' ? 'Unggahan Mandiri' : 'Dari Guru' }}
                        </span>

                        <h4 class="font-semibold mt-3">{{ $s->judul_sertifikat }}</h4>
                        <p class="text-sm text-gray-500">Diterbitkan oleh {{ $s->penerbit ?? '-' }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $s->tanggal_terbit ? \Carbon\Carbon::parse($s->tanggal_terbit)->translatedFormat('d F Y') : '-' }}
                        </p>

                        @if ($s->file_sertifikat)
                            <a href="{{ asset('storage/' . $s->file_sertifikat) }}" target="_blank"
                            class="inline-block mt-3 text-sm bg-gray-900 text-white rounded-lg px-4 py-2">
                                Lihat / Unduh
                            </a>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500 md:col-span-2">Belum ada sertifikat.</p>
                @endforelse
            </div>
        </div>
@endsection