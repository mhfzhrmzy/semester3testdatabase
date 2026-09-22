@extends('layouts.app')

@section('title', 'Portal Siswa — Daftar Materi')

@section('content')
<div>
    {{-- Page header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Halo, {{ auth('siswa')->user()->nama_lengkap }} 👋</h1>
            <p class="text-sm text-gray-500 mt-0.5">Pilih materi untuk mulai belajar dan kerjakan quiz.</p>
        </div>
        <a href="{{ route('leaderboard.index') }}"
           class="hidden sm:inline-flex items-center gap-1.5 text-sm font-semibold text-amber-700 bg-amber-50 border border-amber-200 hover:bg-amber-100 px-3 py-1.5 rounded-lg transition">
            🏆 Leaderboard
        </a>
    </div>

    @if ($materis->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center">
            <div class="text-4xl mb-3">📚</div>
            <h3 class="text-lg font-semibold text-gray-700 mb-1">Belum Ada Materi</h3>
            <p class="text-sm text-gray-400">Guru belum menambahkan materi. Coba lagi nanti.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($materis as $materi)
                @php
                    $pretestCount  = $materi->quiz->where('tipe_test', 'pretest')->count();
                    $posttestCount = $materi->quiz->where('tipe_test', 'posttest')->count();
                @endphp
                <div class="group bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md hover:border-blue-300 transition-all duration-200 flex flex-col overflow-hidden">
                    {{-- Color accent top bar --}}
                    <div class="h-1.5 bg-gradient-to-r from-blue-500 to-indigo-500"></div>

                    <div class="p-5 flex flex-col flex-1">
                        <div class="flex-1">
                            <h2 class="text-sm font-bold text-gray-800 mb-1 leading-snug group-hover:text-blue-700 transition">
                                {{ $materi->judul_materi }}
                            </h2>
                            <p class="text-xs text-gray-400 mb-4">
                                👤 {{ $materi->adminGuru->nama_lengkap ?? '-' }}
                            </p>

                            {{-- Quiz availability badges --}}
                            <div class="flex flex-wrap gap-1.5 mb-4">
                                @if ($pretestCount > 0)
                                    <span class="px-2 py-0.5 text-xs font-semibold bg-amber-100 text-amber-700 rounded-full">
                                        🧪 {{ $pretestCount }} Pre-Test
                                    </span>
                                @endif
                                @if ($posttestCount > 0)
                                    <span class="px-2 py-0.5 text-xs font-semibold bg-green-100 text-green-700 rounded-full">
                                        🎓 {{ $posttestCount }} Post-Test
                                    </span>
                                @endif
                                @if ($pretestCount === 0 && $posttestCount === 0)
                                    <span class="px-2 py-0.5 text-xs text-gray-400 bg-gray-100 rounded-full">
                                        Belum ada quiz
                                    </span>
                                @endif
                            </div>
                        </div>

                        <a href="{{ route('portal.materi.show', $materi) }}"
                           class="w-full text-center bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2 rounded-lg transition duration-150">
                            Buka Materi →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection