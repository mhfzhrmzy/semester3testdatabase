@extends('layouts.app')

@section('title', $materi->judul_materi)

@section('content')
<a href="{{ route('portal.materi.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali ke daftar materi</a>

<div class="bg-white rounded-lg shadow p-6 mt-4">
    <h2 class="text-xl font-semibold mb-1">{{ $materi->judul_materi }}</h2>
    <p class="text-sm text-gray-500 mb-4">Diampu oleh {{ $materi->adminGuru->nama_lengkap ?? '-' }}</p>
    <p class="text-sm text-gray-700 mb-6 whitespace-pre-line">{{ $materi->isi_materi }}</p>

    {{-- Modul / File Materi --}}
    <div class="mb-6">
        <h3 class="text-sm font-semibold mb-2">Modul Materi</h3>

        @if ($materi->upload_file)
            @if ($pretestCompleted)
                {{-- Siswa sudah mengerjakan pre-test → tampilkan file --}}
                @php
                    $fileUrl = route('portal.materi.file', $materi);
                @endphp

                <div class="mb-2 flex gap-2">
                    <a href="{{ $fileUrl }}" target="_blank"
                       class="inline-block bg-gray-700 hover:bg-gray-800 text-white text-sm px-4 py-2 rounded-md transition">
                        📄 Download / Buka File Materi
                    </a>
                </div>

                <iframe src="{{ $fileUrl }}"
                        class="w-full rounded-md border border-gray-300"
                        style="height: 80vh;"
                        title="Preview {{ $materi->judul_materi }}">
                </iframe>
                <p class="text-xs text-gray-400 mt-1">
                    Jika preview tidak muncul, gunakan tombol Download di atas.
                </p>
            @else
                {{-- Siswa belum mengerjakan pre-test → tampilkan pesan kunci --}}
                <div class="flex flex-col items-start gap-4 p-5 rounded-xl border-2 border-dashed border-amber-300 bg-amber-50">
                    <div class="flex items-start gap-3">
                        <span class="text-2xl mt-0.5">🔒</span>
                        <div>
                            <p class="text-sm font-bold text-amber-800">File Materi Terkunci</p>
                            <p class="text-sm text-amber-700 mt-1">
                                Kamu perlu mengerjakan <strong>Pre-Test</strong> terlebih dahulu untuk dapat mengakses file materi ini.
                                Setelah menyelesaikan pre-test, file akan terbuka secara otomatis.
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('portal.quiz.index', $materi) }}"
                       class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold px-5 py-2.5 rounded-lg shadow transition duration-150">
                        🧪 Kerjakan Pre-Test Sekarang →
                    </a>
                </div>
            @endif
        @else
            <p class="text-sm text-gray-400 italic">Belum ada file modul untuk materi ini.</p>
        @endif
    </div>

    {{-- Uji Pemahaman --}}
    <div>
        <h3 class="text-sm font-semibold mb-3">Uji Pemahaman</h3>
        @php
            $pretestCount  = $materi->quiz->where('tipe_test', 'pretest')->count();
            $posttestCount = $materi->quiz->where('tipe_test', 'posttest')->count();
            $totalQuiz     = $materi->quiz->count();
        @endphp
        @if ($totalQuiz > 0)
            <div class="flex flex-wrap gap-3 items-center">
                <a href="{{ route('portal.quiz.index', $materi) }}"
                   class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg shadow transition duration-150">
                    📝 Lihat Semua Quiz
                </a>
                <span class="text-xs text-gray-500">
                    {{ $pretestCount }} Pre-Test · {{ $posttestCount }} Post-Test tersedia
                </span>
            </div>
        @else
            <p class="text-sm text-gray-400 italic">Belum ada quiz untuk materi ini.</p>
        @endif
    </div>
</div>
@endsection