@extends('layouts.app')

@section('title', 'Quiz — ' . $materi->judul_materi)

@section('content')
<div class="max-w-3xl mx-auto">

    {{-- Breadcrumb --}}
    <nav class="mb-6 flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('portal.materi.index') }}" class="hover:text-blue-600 transition">Portal Siswa</a>
        <span>›</span>
        <a href="{{ route('portal.materi.show', $materi) }}" class="hover:text-blue-600 transition">{{ $materi->judul_materi }}</a>
        <span>›</span>
        <span class="text-gray-700 font-medium">Quiz</span>
    </nav>

    {{-- Header --}}
    <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl p-6 mb-8 text-white shadow-lg">
        <p class="text-blue-200 text-xs font-semibold uppercase tracking-wider mb-1">Materi</p>
        <h1 class="text-2xl font-bold mb-1">{{ $materi->judul_materi }}</h1>
        <p class="text-blue-100 text-sm">
            Diampu oleh <strong>{{ $materi->adminGuru->nama_lengkap ?? '-' }}</strong>
        </p>
        <div class="mt-4 flex flex-wrap gap-3 text-sm">
            <div class="bg-white/20 backdrop-blur-sm rounded-lg px-3 py-1.5 font-semibold">
                {{ $quizzes->count() }} Quiz Tersedia
            </div>
            <div class="bg-white/20 backdrop-blur-sm rounded-lg px-3 py-1.5 font-semibold">
                {{ $attemptedIds->count() }} Sudah Dikerjakan
            </div>
        </div>
    </div>

    @if ($quizzes->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-12 text-center">
            <div class="text-5xl mb-3">📭</div>
            <h3 class="text-lg font-semibold text-gray-700 mb-1">Belum Ada Quiz</h3>
            <p class="text-sm text-gray-400">Guru belum membuat quiz untuk materi ini. Coba lagi nanti.</p>
            <a href="{{ route('portal.materi.show', $materi) }}"
               class="inline-block mt-5 text-sm text-blue-600 hover:underline">
                ← Kembali ke Materi
            </a>
        </div>
    @else
        @php
            $pretests  = $quizzes->where('tipe_test', 'pretest');
            $posttests = $quizzes->where('tipe_test', 'posttest');
        @endphp

        {{-- Pre-Test Section --}}
        @if ($pretests->isNotEmpty())
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-4">
                    <span class="text-xl">🧪</span>
                    <h2 class="text-base font-bold text-gray-800 uppercase tracking-wide">Pre-Test</h2>
                    <div class="flex-1 h-px bg-amber-200"></div>
                    <span class="text-xs font-semibold text-amber-700 bg-amber-100 px-2.5 py-1 rounded-full">
                        {{ $pretests->count() }} Quiz
                    </span>
                </div>

                <div class="space-y-4">
                    @foreach ($pretests as $quiz)
                        @php
                            $attempted = $attemptedIds->has($quiz->id_quiz);
                            $poin      = $attemptedIds->get($quiz->id_quiz);
                        @endphp
                        <div class="group bg-white rounded-xl border {{ $attempted ? 'border-amber-300' : 'border-gray-200' }} shadow-sm hover:shadow-md transition-all duration-200 p-5">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-2 flex-wrap">
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">Pre-Test</span>
                                        @if ($attempted)
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">✔ Sudah Dikerjakan</span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">Belum Dikerjakan</span>
                                        @endif
                                    </div>

                                    <h3 class="text-sm font-semibold text-gray-800 mb-3">
                                        Quiz Tanggal {{ \Carbon\Carbon::parse($quiz->tanggal)->format('d M Y') }}
                                    </h3>

                                    <div class="flex flex-wrap gap-4 text-xs text-gray-500">
                                        <span>📝 {{ $quiz->soal->count() }} Soal</span>
                                        <span>⭐ {{ $quiz->poin }} Poin/Soal</span>
                                        <span>⏱ Timer per soal aktif</span>
                                        <span>🎯 Maks {{ $quiz->soal->count() * $quiz->poin }} Poin</span>
                                    </div>

                                    @if ($attempted)
                                        <div class="mt-3 inline-flex items-center gap-1.5 bg-green-50 border border-green-200 text-green-700 text-xs font-semibold px-3 py-1.5 rounded-lg">
                                            🏆 Skor kamu: {{ $poin }} Poin
                                        </div>
                                    @endif
                                </div>

                                <div class="shrink-0">
                                    @if ($quiz->soal->isEmpty())
                                        <span class="text-xs text-gray-400 italic">Soal belum tersedia</span>
                                    @else
                                        <a href="{{ route('portal.quiz.kerjakan', $quiz) }}"
                                           class="inline-flex items-center gap-1.5 {{ $attempted ? 'bg-amber-500 hover:bg-amber-600' : 'bg-blue-600 hover:bg-blue-700' }} text-white text-sm font-semibold px-4 py-2 rounded-lg shadow transition-all duration-150">
                                            {{ $attempted ? '🔄 Kerjakan Ulang' : '▶ Mulai Quiz' }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Post-Test Section --}}
        @if ($posttests->isNotEmpty())
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-4">
                    <span class="text-xl">🎓</span>
                    <h2 class="text-base font-bold text-gray-800 uppercase tracking-wide">Post-Test</h2>
                    <div class="flex-1 h-px bg-green-200"></div>
                    <span class="text-xs font-semibold text-green-700 bg-green-100 px-2.5 py-1 rounded-full">
                        {{ $posttests->count() }} Quiz
                    </span>
                </div>

                <div class="space-y-4">
                    @foreach ($posttests as $quiz)
                        @php
                            $attempted = $attemptedIds->has($quiz->id_quiz);
                            $poin      = $attemptedIds->get($quiz->id_quiz);
                        @endphp
                        <div class="group bg-white rounded-xl border {{ $attempted ? 'border-green-300' : 'border-gray-200' }} shadow-sm hover:shadow-md transition-all duration-200 p-5">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-2 flex-wrap">
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800">Post-Test</span>
                                        @if ($attempted)
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">✔ Sudah Dikerjakan</span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">Belum Dikerjakan</span>
                                        @endif
                                    </div>

                                    <h3 class="text-sm font-semibold text-gray-800 mb-3">
                                        Quiz Tanggal {{ \Carbon\Carbon::parse($quiz->tanggal)->format('d M Y') }}
                                    </h3>

                                    <div class="flex flex-wrap gap-4 text-xs text-gray-500">
                                        <span>📝 {{ $quiz->soal->count() }} Soal</span>
                                        <span>⭐ {{ $quiz->poin }} Poin/Soal</span>
                                        <span>⏱ Timer per soal aktif</span>
                                        <span>🎯 Maks {{ $quiz->soal->count() * $quiz->poin }} Poin</span>
                                    </div>

                                    @if ($attempted)
                                        <div class="mt-3 inline-flex items-center gap-1.5 bg-green-50 border border-green-200 text-green-700 text-xs font-semibold px-3 py-1.5 rounded-lg">
                                            🏆 Skor kamu: {{ $poin }} Poin
                                        </div>
                                    @endif
                                </div>

                                <div class="shrink-0">
                                    @if ($quiz->soal->isEmpty())
                                        <span class="text-xs text-gray-400 italic">Soal belum tersedia</span>
                                    @else
                                        <a href="{{ route('portal.quiz.kerjakan', $quiz) }}"
                                           class="inline-flex items-center gap-1.5 {{ $attempted ? 'bg-amber-500 hover:bg-amber-600' : 'bg-green-600 hover:bg-green-700' }} text-white text-sm font-semibold px-4 py-2 rounded-lg shadow transition-all duration-150">
                                            {{ $attempted ? '🔄 Kerjakan Ulang' : '▶ Mulai Quiz' }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endif

    <a href="{{ route('portal.materi.show', $materi) }}"
       class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-blue-600 transition mt-2">
        ← Kembali ke Materi
    </a>
</div>
@endsection
