@extends('layouts.app')
@section('title', 'Hasil Quiz — ' . ($quiz->materi->judul_materi ?? 'Quiz'))
@section('content')

@php
    $persen = $total > 0 ? round(($benar / $total) * 100) : 0;
    $grade = match(true) {
        $persen >= 90 => ['label' => 'Sempurna!',    'color' => 'text-emerald-600', 'bg' => 'bg-emerald-50',  'border' => 'border-emerald-200'],
        $persen >= 75 => ['label' => 'Bagus!',        'color' => 'text-blue-600',    'bg' => 'bg-blue-50',     'border' => 'border-blue-200'],
        $persen >= 60 => ['label' => 'Cukup Baik',   'color' => 'text-amber-600',   'bg' => 'bg-amber-50',    'border' => 'border-amber-200'],
        default       => ['label' => 'Perlu Belajar', 'color' => 'text-red-600',     'bg' => 'bg-red-50',      'border' => 'border-red-200'],
    };
    $circumference = 2 * 3.14159 * 45;
    $dash = round($circumference * $persen / 100, 2);
@endphp

<div class="max-w-md mx-auto py-4">

    {{-- Celebration banner --}}
    <div class="text-center mb-2 text-3xl select-none" aria-hidden="true">
        @if ($persen >= 75) 🎉🎊🏆 @elseif ($persen >= 60) 👍✨ @else 💪📚 @endif
    </div>

    {{-- Main result card --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-md overflow-hidden">

        {{-- Gradient header --}}
        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 px-6 pt-8 pb-10 text-center text-white">
            <p class="text-blue-200 text-xs font-semibold uppercase tracking-widest mb-1">{{ ucfirst($quiz->tipe_test) }}</p>
            <h1 class="text-xl font-bold mb-0.5">{{ $quiz->materi->judul_materi ?? 'Quiz' }}</h1>
            <p class="text-blue-300 text-xs">Hasil Pengerjaan Kamu</p>
        </div>

        {{-- Score ring (pulls up from overlap) --}}
        <div class="-mt-8 flex justify-center mb-4">
            <div class="relative w-28 h-28 bg-white rounded-full shadow-lg border-4 border-white flex items-center justify-center">
                <svg class="w-full h-full -rotate-90" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="45" fill="none" stroke="#e5e7eb" stroke-width="8"/>
                    <circle cx="50" cy="50" r="45" fill="none"
                            stroke="{{ $persen >= 75 ? '#10b981' : ($persen >= 60 ? '#f59e0b' : '#ef4444') }}"
                            stroke-width="8"
                            stroke-linecap="round"
                            stroke-dasharray="{{ $dash }} {{ $circumference }}"
                            style="transition: stroke-dasharray 1s ease;"/>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-2xl font-black text-gray-800">{{ $persen }}%</span>
                    <span class="text-xs text-gray-400 font-medium">Skor</span>
                </div>
            </div>
        </div>

        <div class="px-6 pb-6 text-center">

            {{-- Grade badge --}}
            <div class="inline-flex items-center gap-1.5 {{ $grade['bg'] }} {{ $grade['border'] }} border {{ $grade['color'] }} text-sm font-bold px-4 py-1.5 rounded-full mb-5">
                {{ $grade['label'] }}
            </div>

            {{-- Stats row --}}
            <div class="grid grid-cols-3 gap-3 mb-6">
                <div class="bg-green-50 border border-green-200 rounded-xl p-3 text-center">
                    <div class="text-2xl font-black text-green-600">{{ $benar }}</div>
                    <div class="text-xs text-green-700 font-medium mt-0.5">Benar</div>
                </div>
                <div class="bg-red-50 border border-red-200 rounded-xl p-3 text-center">
                    <div class="text-2xl font-black text-red-500">{{ $total - $benar }}</div>
                    <div class="text-xs text-red-700 font-medium mt-0.5">Salah</div>
                </div>
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-3 text-center">
                    <div class="text-2xl font-black text-blue-600">{{ $total }}</div>
                    <div class="text-xs text-blue-700 font-medium mt-0.5">Total</div>
                </div>
            </div>

            {{-- Total points --}}
            <div class="bg-gradient-to-br from-indigo-50 to-blue-50 border border-indigo-200 rounded-xl p-4 mb-6">
                <div class="text-xs text-indigo-600 font-bold uppercase tracking-wider mb-1">Total Poin Diperoleh</div>
                <div class="text-4xl font-black text-indigo-700">
                    {{ $totalPoin }}
                    <span class="text-base font-semibold text-gray-400">Poin</span>
                </div>
            </div>

            {{-- Action buttons --}}
            <div class="flex flex-col gap-2.5">
                <a href="{{ route('leaderboard.index') }}"
                   class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-amber-500 to-orange-500
                          hover:from-amber-600 hover:to-orange-600 text-white font-bold py-3 rounded-xl shadow
                          transition-all duration-150">
                    🏆 Lihat Leaderboard
                </a>
                <a href="{{ route('portal.quiz.index', $quiz->materi) }}"
                   class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700
                          text-white font-semibold py-2.5 rounded-xl transition duration-150">
                    📋 Daftar Quiz Lainnya
                </a>
                <a href="{{ route('portal.materi.show', $quiz->materi) }}"
                   class="w-full flex items-center justify-center gap-2 border border-gray-300 hover:bg-gray-50
                          text-gray-600 font-medium py-2.5 rounded-xl transition duration-150">
                    ← Kembali ke Materi
                </a>
            </div>
        </div>
    </div>
</div>
@endsection