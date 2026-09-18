@extends('layouts.app')

@section('title', $materi->judul_materi)

@section('content')
<a href="{{ route('portal.materi.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali ke daftar materi</a>

<div class="bg-white rounded-lg shadow p-6 mt-4">
    <h2 class="text-xl font-semibold mb-1">{{ $materi->judul_materi }}</h2>
    <p class="text-sm text-gray-500 mb-4">Diampu oleh {{ $materi->admin->nama_lengkap ?? '-' }}</p>
    <p class="text-sm text-gray-700 mb-6 whitespace-pre-line">{{ $materi->isi_materi }}</p>

    <div class="mb-6">
        <h3 class="text-sm font-semibold mb-2">Modul Materi</h3>
        @if ($materi->upload_file)
            <a href="{{ Storage::url($materi->upload_file) }}" target="_blank"
               class="inline-block bg-gray-700 hover:bg-gray-800 text-white text-sm px-4 py-2 rounded-md">
                📄 Download / Lihat File
            </a>
        @else
            <p class="text-sm text-gray-400 italic">Belum ada file modul untuk materi ini.</p>
        @endif
    </div>

    <div>
        <h3 class="text-sm font-semibold mb-2">Uji Pemahaman</h3>
        <div class="flex gap-3">
            <a href="{{ route('portal.soal.kerjakan', [$materi, 'pretest']) }}"
               class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-md">
                Kerjakan Pretest ({{ $materi->soalPretest()->count() }} soal)
            </a>
            <a href="{{ route('portal.soal.kerjakan', [$materi, 'posttest']) }}"
               class="bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium px-4 py-2 rounded-md">
                Kerjakan Posttest ({{ $materi->soalPosttest()->count() }} soal)
            </a>
        </div>
    </div>
</div>
@endsection