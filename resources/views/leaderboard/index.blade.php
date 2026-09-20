@extends('layouts.app')
@section('title', 'Leaderboard Pembelajaran')
@section('content')
<div class="max-w-5xl mx-auto py-4">
    <!-- Header Leaderboard -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 border-b pb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                🏆 Leaderboard &amp; Peringkat Nilai Siswa
            </h1>
            <p class="text-sm text-gray-600">Daftar perolehan poin terbanyak dari pengerjaan Pre-Test dan Post-Test</p>
        </div>

        <!-- Filter Tipe Test -->
        <div class="flex gap-2">
            <a href="{{ route('leaderboard.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ empty($filterTest) ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Semua Test
            </a>
            <a href="{{ route('leaderboard.index', ['tipe_test' => 'pretest']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $filterTest === 'pretest' ? 'bg-amber-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Pre-Test Only
            </a>
            <a href="{{ route('leaderboard.index', ['tipe_test' => 'posttest']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $filterTest === 'posttest' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Post-Test Only
            </a>
        </div>
    </div>

    <!-- Top 3 Podiums (If Data Available) -->
    @if ($leaderboards->count() >= 1)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            @foreach ($leaderboards->take(3) as $rank => $item)
                @php
                    $colors = [
                        0 => 'bg-gradient-to-b from-amber-100 to-amber-50 border-amber-300 text-amber-900', // Gold
                        1 => 'bg-gradient-to-b from-slate-100 to-slate-50 border-slate-300 text-slate-800',  // Silver
                        2 => 'bg-gradient-to-b from-orange-100 to-orange-50 border-orange-300 text-orange-900', // Bronze
                    ];
                    $badges = [0 => '🥇 JUARA 1', 1 => '🥈 JUARA 2', 2 => '🥉 JUARA 3'];
                @endphp
                <div class="border rounded-xl p-5 text-center shadow-sm relative overflow-hidden {{ $colors[$rank] ?? '' }}">
                    <div class="text-xs font-bold uppercase tracking-wider mb-1">{{ $badges[$rank] }}</div>
                    <div class="text-lg font-bold truncate">{{ $item->pengguna->nama_lengkap ?? 'Siswa' }}</div>
                    <div class="text-xs text-gray-500 mb-3">NISN: {{ $item->nisn }}</div>
                    <div class="text-2xl font-black text-blue-600 mb-1">{{ $item->total_poin }} <span class="text-xs font-normal text-gray-500">Poin</span></div>
                    <div class="text-xs text-gray-500">
                        {{ $item->quiz->materi->judul_materi ?? 'Quiz' }} ({{ ucfirst($item->quiz->tipe_test ?? '') }})
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Table Leaderboard -->
    <div class="bg-white rounded-lg border overflow-hidden shadow-sm">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="bg-gray-100 border-b text-gray-700">
                    <th class="px-4 py-3 text-center w-16">Peringkat</th>
                    <th class="px-4 py-3">Nama Siswa</th>
                    <th class="px-4 py-3">NISN</th>
                    <th class="px-4 py-3">Materi &amp; Quiz</th>
                    <th class="px-4 py-3">Tipe Test</th>
                    <th class="px-4 py-3 text-center">Total Poin</th>
                    <th class="px-4 py-3 text-right">Waktu Selesai</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($leaderboards as $index => $row)
                    <tr class="border-b hover:bg-gray-50 {{ auth('siswa')->check() && auth('siswa')->id() == $row->nisn ? 'bg-amber-50 font-semibold' : '' }}">
                        <td class="px-4 py-3 text-center font-bold">
                            @if ($index === 0) 🥇 1
                            @elseif ($index === 1) 🥈 2
                            @elseif ($index === 2) 🥉 3
                            @else #{{ $index + 1 }}
                            @endif
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-900">
                            {{ $row->pengguna->nama_lengkap ?? 'Siswa' }}
                            @if (auth('siswa')->check() && auth('siswa')->id() == $row->nisn)
                                <span class="ml-2 text-xs bg-amber-200 text-amber-800 px-2 py-0.5 rounded-full">(Anda)</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-600 text-xs font-mono">{{ $row->nisn }}</td>
                        <td class="px-4 py-3">{{ $row->quiz->materi->judul_materi ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ ($row->quiz->tipe_test ?? '') === 'pretest' ? 'bg-amber-100 text-amber-800' : 'bg-green-100 text-green-800' }}">
                                {{ ucfirst($row->quiz->tipe_test ?? '-') }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center font-black text-blue-600 text-base">
                            {{ $row->total_poin }}
                        </td>
                        <td class="px-4 py-3 text-right text-xs text-gray-500">
                            {{ $row->updated_at ? $row->updated_at->diffForHumans() : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                            Belum ada riwayat nilai kuis di leaderboard.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
