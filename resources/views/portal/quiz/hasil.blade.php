@extends('layouts.app')
@section('title', 'Hasil Quiz')
@section('content')
<div class="bg-white rounded-lg shadow-sm border p-8 max-w-md mx-auto text-center my-6">
    <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold">
        🎉
    </div>
    <h2 class="text-xl font-bold text-gray-800 mb-1">Hasil {{ ucfirst($quiz->tipe_test) }}</h2>
    <p class="text-xs text-gray-500 mb-4">{{ $quiz->materi->judul_materi ?? 'Quiz' }}</p>

    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
        <div class="text-xs text-blue-800 uppercase font-bold tracking-wider mb-1">Total Poin yang Diperoleh</div>
        <p class="text-4xl font-black text-blue-600">{{ $totalPoin }} <span class="text-sm font-semibold text-gray-500">Poin</span></p>
    </div>

    <p class="text-sm font-medium text-gray-700 mb-6">
        Jawaban Benar: <strong class="text-green-600">{{ $benar }}</strong> dari <strong>{{ $total }}</strong> Soal
    </p>

    <div class="flex flex-col gap-2">
        <a href="{{ route('leaderboard.index') }}" class="w-full bg-amber-600 hover:bg-amber-700 text-white text-sm font-bold py-2.5 rounded-lg transition duration-150 flex items-center justify-center gap-2">
            🏆 Lihat Peringkat di Leaderboard
        </a>
        <a href="{{ route('portal.materi.show', $quiz->id_materi) }}" class="w-full border border-gray-300 hover:bg-gray-100 text-gray-700 text-sm font-medium py-2 rounded-lg transition duration-150">
            &larr; Kembali ke Materi
        </a>
    </div>
</div>
@endsection