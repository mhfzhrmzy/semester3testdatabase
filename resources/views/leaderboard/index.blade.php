@extends('layouts.app')
@section('title', 'Leaderboard Pembelajaran')
@section('content')
<div class="max-w-6xl mx-auto py-2 space-y-6">

    <!-- Header Leaderboard -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b pb-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <h1 class="text-2xl font-bold text-gray-800">
                    Leaderboard &amp; Peringkat Nilai Siswa
                </h1>
            </div>
            <p class="text-sm text-gray-600">
                @if ($isSiswa)
                    Peringkat perolehan nilai Post-Test siswa di Kelas {{ $kelas }} - {{ $jurusan }}.
                @else
                    Peringkat perolehan poin dari pengerjaan kuis Pre-Test dan Post-Test siswa.
                @endif
            </p>
        </div>

        @if (! $isSiswa && ($kelas || $jurusan || $selectedMateriId || $filterTest))
            <div>
                <a href="{{ url()->current() }}" class="inline-flex items-center gap-1 text-xs font-semibold text-gray-500 hover:text-red-600 bg-gray-100 hover:bg-red-50 border border-gray-300 hover:border-red-200 px-3 py-1.5 rounded-lg transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Reset Filter
                </a>
            </div>
        @elseif ($isSiswa && ($selectedMateriId || $tipeLeaderboard === 'permateri'))
            <div>
                <a href="{{ route('leaderboard.index') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-gray-500 hover:text-blue-600 bg-gray-100 hover:bg-blue-50 border border-gray-300 hover:border-blue-200 px-3 py-1.5 rounded-lg transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Kembali ke Keseluruhan
                </a>
            </div>
        @endif
    </div>

    <!-- Panel Filter Guru / Siswa -->
    <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 shadow-sm space-y-5">
        <form method="GET" action="{{ url()->current() }}" id="leaderboardFilterForm">

            @if ($isSiswa)
                {{-- Banner Informasi Kelas Siswa --}}
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center text-lg font-bold shadow-sm">
                            🎓
                        </div>
                        <div>
                            <div class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Ruang Kelas &amp; Jurusan Anda</div>
                            <div class="text-base font-bold text-gray-900">
                                Kelas {{ $kelas }} • {{ $jurusan }}
                            </div>
                        </div>
                    </div>
                </div>
            @else
                {{-- 1. PEMILIHAN KELAS DAN JURUSAN (UNTUK GURU) --}}
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">
                            Pilih Kelas &amp; Jurusan Yang Dituju
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        {{-- Dropdown Kelas --}}
                        <div>
                            <label for="filterKelas" class="block text-xs font-semibold text-gray-600 mb-1">Tingkat Kelas</label>
                            <select name="kelas" id="filterKelas" onchange="this.form.submit()"
                                    class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <option value="">-- Semua Kelas --</option>
                                @foreach ($daftarKelas as $k)
                                    <option value="{{ $k }}" @selected((string)$kelas === (string)$k)>
                                        Kelas {{ $k }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Dropdown Jurusan --}}
                        <div>
                            <label for="filterJurusan" class="block text-xs font-semibold text-gray-600 mb-1">Jurusan / Program Keahlian</label>
                            <select name="jurusan" id="filterJurusan" onchange="this.form.submit()"
                                    class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <option value="">-- Semua Jurusan --</option>
                                @foreach ($daftarJurusan as $j)
                                    <option value="{{ $j }}" @selected($jurusan === $j)>
                                        {{ $j }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-200">
            @endif

            {{-- 2. TIPE LEADERBOARD: KESELURUHAN vs PER MATERI --}}
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">
                        Tipe Leaderboard
                    </h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    {{-- Tombol Keseluruhan --}}
                    <label class="cursor-pointer border-2 rounded-xl p-3 flex items-center gap-3 transition {{ $tipeLeaderboard === 'keseluruhan' ? 'border-blue-600 bg-blue-50/70 text-blue-900 shadow-sm' : 'border-gray-200 bg-white hover:border-gray-300 text-gray-700' }}">
                        <input type="radio" name="tipe_leaderboard" value="keseluruhan" class="hidden"
                               @checked($tipeLeaderboard === 'keseluruhan') onchange="this.form.submit()">
                        <div>
                            <div class="font-bold text-sm">
                                {{ $isSiswa ? 'Leaderboard Post-Test Keseluruhan' : 'Leaderboard Keseluruhan' }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $isSiswa ? 'Peringkat akumulasi post-test semua materi di kelas Anda' : 'Peringkat umum siswa dari semua materi' }}
                            </div>
                        </div>
                    </label>

                    {{-- Tombol Per Materi --}}
                    <label class="cursor-pointer border-2 rounded-xl p-3 flex items-center gap-3 transition {{ $tipeLeaderboard === 'permateri' ? 'border-blue-600 bg-blue-50/70 text-blue-900 shadow-sm' : 'border-gray-200 bg-white hover:border-gray-300 text-gray-700' }}">
                        <input type="radio" name="tipe_leaderboard" value="permateri" class="hidden"
                               @checked($tipeLeaderboard === 'permateri') onchange="this.form.submit()">
                        <div>
                            <div class="font-bold text-sm">
                                {{ $isSiswa ? 'Leaderboard Post-Test Per Materi' : 'Leaderboard Per Materi' }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $isSiswa ? 'Peringkat post-test materi tertentu di kelas Anda' : 'Peringkat spesifik berdasarkan materi & kuis tertentu' }}
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <hr class="border-gray-200">

            {{-- 3. DETAIL PILIHAN: JIKA PER MATERI ATAU KESELURUHAN --}}
            @if ($tipeLeaderboard === 'permateri')
                {{-- OPSI UNTUK PER MATERI --}}
                <div class="space-y-4 bg-white p-4 rounded-xl border border-blue-200">
                    <div class="flex items-center gap-2 mb-1">
                        <h3 class="text-sm font-bold text-blue-900 uppercase tracking-wider">
                            Pilih Materi Pembelajaran
                        </h3>
                    </div>

                    {{-- Dropdown Pilih Materi --}}
                    <div>
                        <select name="materi_id" id="materiSelect" onchange="this.form.submit()"
                                class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2.5 text-sm font-medium text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <option value="">-- Silakan Pilih Materi --</option>
                            @forelse ($daftarMateri as $m)
                                <option value="{{ $m->id_materi }}" @selected((string)$selectedMateriId === (string)$m->id_materi)>
                                    {{ $m->judul_materi }}
                                    @if (! $isSiswa && ($m->kelas || $m->jurusan))
                                        (Kelas {{ $m->kelas ?? '-' }} • {{ $m->jurusan ?? '-' }})
                                    @endif
                                </option>
                            @empty
                                <option value="" disabled>Tidak ada materi untuk kelas &amp; jurusan ini</option>
                            @endforelse
                        </select>
                    </div>

                    {{-- Opsi Tipe Test: Muncul ketika materi sudah dipilih --}}
                    @if ($selectedMateri)
                        @if ($isSiswa)
                            <div class="pt-3 border-t border-gray-100 flex items-center justify-between flex-wrap gap-2">
                                <div class="text-xs text-gray-700">
                                    Menampilkan Leaderboard: <strong class="text-blue-800">{{ $selectedMateri->judul_materi }}</strong>
                                </div>
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-lg border border-green-200">
                                    Post-Test
                                </span>
                            </div>
                        @else
                            {{-- Untuk Guru: Opsi Pre-Test dan Post-Test --}}
                            <div class="pt-3 border-t border-gray-100">
                                <div class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    Pilihan Leaderboard Untuk Materi "{{ $selectedMateri->judul_materi }}":
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="tipe_test" value="pretest" class="hidden"
                                               @checked($filterTest === 'pretest') onchange="this.form.submit()">
                                        <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-bold transition border {{ $filterTest === 'pretest' ? 'bg-amber-600 text-white border-amber-700 shadow' : 'bg-amber-50 text-amber-800 border-amber-200 hover:bg-amber-100' }}">
                                            Leaderboard Pre-Test
                                        </span>
                                    </label>

                                    <label class="cursor-pointer">
                                        <input type="radio" name="tipe_test" value="posttest" class="hidden"
                                               @checked($filterTest === 'posttest') onchange="this.form.submit()">
                                        <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-bold transition border {{ $filterTest === 'posttest' ? 'bg-green-600 text-white border-green-700 shadow' : 'bg-green-50 text-green-800 border-green-200 hover:bg-green-100' }}">
                                            Leaderboard Post-Test
                                        </span>
                                    </label>

                                    <label class="cursor-pointer">
                                        <input type="radio" name="tipe_test" value="" class="hidden"
                                               @checked(empty($filterTest)) onchange="this.form.submit()">
                                        <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-bold transition border {{ empty($filterTest) ? 'bg-gray-800 text-white border-gray-900 shadow' : 'bg-gray-100 text-gray-700 border-gray-200 hover:bg-gray-200' }}">
                                            Semua (Pre &amp; Post)
                                        </span>
                                    </label>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="p-3 bg-amber-50 border border-amber-200 rounded-lg text-xs text-amber-800 flex items-center gap-2">
                            <span>Silakan pilih salah satu materi di atas untuk melihat nilai leaderboard.</span>
                        </div>
                    @endif
                </div>
            @else
                {{-- OPSI UNTUK KESELURUHAN --}}
                @if ($isSiswa)
                    <div class="bg-white p-4 rounded-xl border border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <div class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-1">
                                Leaderboard Post-Test Keseluruhan
                            </div>
                            <p class="text-xs text-gray-500">
                                Akumulasi seluruh nilai Post-Test siswa di Kelas {{ $kelas }} - {{ $jurusan }}.
                            </p>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-green-100 text-green-800 border border-green-200 self-start sm:self-auto">
                            Post-Test Only
                        </span>
                    </div>
                @else
                    {{-- Untuk Guru: Opsi Tipe Test Keseluruhan --}}
                    <div class="bg-white p-4 rounded-xl border border-gray-200">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Pilih Tipe Test (Akumulasi Semua Materi):
                            </span>
                        </div>
                        <div class="flex flex-wrap gap-2 mt-2">
                            <label class="cursor-pointer">
                                <input type="radio" name="tipe_test" value="" class="hidden"
                                       @checked(empty($filterTest)) onchange="this.form.submit()">
                                <span class="inline-block px-3.5 py-2 rounded-lg text-xs font-semibold border transition {{ empty($filterTest) ? 'bg-blue-600 text-white border-blue-700 shadow-sm' : 'bg-gray-100 text-gray-700 border-gray-200 hover:bg-gray-200' }}">
                                    Semua Test
                                </span>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="tipe_test" value="pretest" class="hidden"
                                       @checked($filterTest === 'pretest') onchange="this.form.submit()">
                                 <span class="inline-block px-3.5 py-2 rounded-lg text-xs font-semibold border transition {{ $filterTest === 'pretest' ? 'bg-amber-600 text-white border-amber-700 shadow-sm' : 'bg-gray-100 text-gray-700 border-gray-200 hover:bg-gray-200' }}">
                                    Pre-Test Only
                                </span>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="tipe_test" value="posttest" class="hidden"
                                       @checked($filterTest === 'posttest') onchange="this.form.submit()">
                                 <span class="inline-block px-3.5 py-2 rounded-lg text-xs font-semibold border transition {{ $filterTest === 'posttest' ? 'bg-green-600 text-white border-green-700 shadow-sm' : 'bg-gray-100 text-gray-700 border-gray-200 hover:bg-gray-200' }}">
                                    Post-Test Only
                                </span>
                            </label>
                        </div>
                    </div>
                @endif
            @endif

        </form>
    </div>

    <!-- Active Filter Summary Banner -->
    <div class="flex flex-wrap items-center justify-between gap-2 text-xs bg-white border border-gray-200 rounded-lg px-4 py-2.5 shadow-sm text-gray-600">
        <div class="flex flex-wrap items-center gap-1.5">
            <span class="font-bold text-gray-700">Filter Aktif:</span>
            <span class="bg-blue-50 text-blue-700 font-semibold px-2 py-0.5 rounded border border-blue-200">
                {{ $kelas ? 'Kelas ' . $kelas : 'Semua Kelas' }}
            </span>
            <span class="bg-blue-50 text-blue-700 font-semibold px-2 py-0.5 rounded border border-blue-200">
                {{ $jurusan ? $jurusan : 'Semua Jurusan' }}
            </span>
            <span class="bg-blue-50 text-blue-700 font-semibold px-2 py-0.5 rounded border border-blue-200">
                {{ $tipeLeaderboard === 'permateri' ? ('Per Materi: ' . ($selectedMateri->judul_materi ?? 'Semua Materi')) : 'Keseluruhan' }}
            </span>
            <span class="bg-blue-50 text-blue-700 font-semibold px-2 py-0.5 rounded border border-blue-200">
                {{ $filterTest ? ucfirst($filterTest) : 'Semua Test' }}
            </span>
        </div>
        <div class="text-gray-500 font-medium">
            Total Siswa Terdaftar di Hasil: <span class="font-bold text-gray-800">{{ $leaderboards->count() }}</span>
        </div>
    </div>

    <!-- Top 3 Podiums (If Data Available) -->
    @if ($leaderboards->count() >= 1)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-2">
            @foreach ($leaderboards->take(3) as $rank => $item)
                @php
                    $colors = [
                        0 => 'bg-gradient-to-b from-amber-100 via-amber-50 to-white border-amber-300 text-amber-900', // Gold
                        1 => 'bg-gradient-to-b from-slate-100 via-slate-50 to-white border-slate-300 text-slate-800',  // Silver
                        2 => 'bg-gradient-to-b from-orange-100 via-orange-50 to-white border-orange-300 text-orange-900', // Bronze
                    ];
                    $badges = [0 => '🥇 JUARA 1', 1 => '🥈 JUARA 2', 2 => '🥉 JUARA 3'];
                @endphp
                <div class="border-2 rounded-2xl p-5 text-center shadow-sm relative overflow-hidden {{ $colors[$rank] ?? 'border-gray-200 bg-white' }}">
                    <div class="text-xs font-extrabold uppercase tracking-wider mb-1.5">{{ $badges[$rank] }}</div>
                    <div class="text-lg font-bold text-gray-900 truncate">{{ $item->pengguna->nama_lengkap ?? 'Siswa' }}</div>
                    <div class="text-xs text-gray-500 mb-2 font-mono">NISN: {{ $item->nisn }}</div>

                    {{-- Atribut Kelas & Jurusan di Card Podium --}}
                    <div class="inline-flex flex-wrap items-center justify-center gap-1.5 mb-3">
                        @if ($item->pengguna?->kelas)
                            <span class="text-xs font-semibold px-2 py-0.5 bg-blue-100 text-blue-800 rounded">
                                Kelas {{ $item->pengguna->kelas }}
                            </span>
                        @endif
                        @if ($item->pengguna?->jurusan)
                            <span class="text-xs font-medium px-2 py-0.5 bg-gray-100 text-gray-700 rounded truncate max-w-[200px]" title="{{ $item->pengguna->jurusan }}">
                                {{ $item->pengguna->jurusan }}
                            </span>
                        @endif
                    </div>

                    <div class="text-3xl font-black text-blue-600 mb-1">
                        {{ $item->total_poin }} <span class="text-xs font-normal text-gray-500">Poin</span>
                    </div>

                    <div class="text-xs text-gray-600 font-medium">
                        @php
                            $testLabel = match (strtolower($filterTest ?: ($item->quiz->tipe_test ?? ''))) {
                                'pretest' => 'Pre-Test',
                                'posttest' => 'Post-Test',
                                default => 'Semua Test',
                            };
                        @endphp
                        @if ($tipeLeaderboard === 'permateri')
                            {{ $selectedMateri->judul_materi ?? ($item->quiz->materi->judul_materi ?? 'Quiz') }}
                            <span class="text-gray-500">({{ $testLabel }})</span>
                        @else
                            {{ $item->total_kuis ?? 1 }} Kuis Diselesaikan
                            <span class="text-gray-500">({{ $testLabel }})</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Table Leaderboard -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="bg-gray-100 border-b text-gray-700 text-xs uppercase tracking-wider">
                        <th class="px-4 py-3.5 text-center w-16">Peringkat</th>
                        <th class="px-4 py-3.5">Nama Siswa</th>
                        <th class="px-4 py-3.5">NISN</th>
                        <th class="px-4 py-3.5">Kelas</th>
                        <th class="px-4 py-3.5">Jurusan</th>
                        <th class="px-4 py-3.5">Materi &amp; Quiz</th>
                        <th class="px-4 py-3.5 text-center">Tipe Test</th>
                        <th class="px-4 py-3.5 text-center">Total Poin</th>
                        <th class="px-4 py-3.5 text-right">Waktu Selesai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($leaderboards as $index => $row)
                        <tr class="hover:bg-gray-50 transition {{ auth('siswa')->check() && auth('siswa')->id() == $row->nisn ? 'bg-amber-50/80 font-medium' : '' }}">
                            {{-- Peringkat --}}
                            <td class="px-4 py-3.5 text-center font-bold">
                                @if ($index === 0) 🥇 1
                                @elseif ($index === 1) 🥈 2
                                @elseif ($index === 2) 🥉 3
                                @else <span class="text-gray-600">#{{ $index + 1 }}</span>
                                @endif
                            </td>

                            {{-- Nama Siswa --}}
                            <td class="px-4 py-3.5 font-medium text-gray-900">
                                {{ $row->pengguna->nama_lengkap ?? 'Siswa' }}
                                @if (auth('siswa')->check() && auth('siswa')->id() == $row->nisn)
                                    <span class="ml-2 text-xs bg-amber-200 text-amber-800 px-2 py-0.5 rounded-full font-bold">(Anda)</span>
                                @endif
                            </td>

                            {{-- NISN --}}
                            <td class="px-4 py-3.5 text-gray-600 text-xs font-mono">{{ $row->nisn }}</td>

                            {{-- Atribut KELAS --}}
                            <td class="px-4 py-3.5">
                                @if ($row->pengguna?->kelas)
                                    <span class="inline-block px-2.5 py-0.5 rounded-md text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                                        Kelas {{ $row->pengguna->kelas }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>

                            {{-- Atribut JURUSAN --}}
                            <td class="px-4 py-3.5 text-xs text-gray-700 font-medium">
                                {{ $row->pengguna?->jurusan ?? '-' }}
                            </td>

                            {{-- Materi & Quiz --}}
                            <td class="px-4 py-3.5 text-gray-800">
                                @if ($tipeLeaderboard === 'permateri')
                                    <span class="font-semibold text-gray-900">
                                        {{ $selectedMateri->judul_materi ?? ($row->quiz->materi->judul_materi ?? '-') }}
                                    </span>
                                @else
                                    <span class="font-semibold text-gray-800">Semua Materi</span>
                                    <span class="text-xs text-gray-500 block">({{ $row->total_kuis ?? 0 }} kuis diselesaikan)</span>
                                @endif
                            </td>

                            {{-- Tipe Test --}}
                            <td class="px-4 py-3.5 text-center">
                                @php
                                    $badgeTest = ($tipeLeaderboard === 'keseluruhan')
                                        ? ($filterTest ?? 'Semua')
                                        : ($row->quiz->tipe_test ?? $filterTest ?? '-');

                                    $badgeLabel = match (strtolower($badgeTest)) {
                                        'pretest' => 'Pre-Test',
                                        'posttest' => 'Post-Test',
                                        default => 'Semua Test',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ strtolower($badgeTest) === 'pretest' ? 'bg-amber-100 text-amber-800 border border-amber-200' : (strtolower($badgeTest) === 'posttest' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-blue-100 text-blue-800 border border-blue-200') }}">
                                    {{ $badgeLabel }}
                                </span>
                            </td>

                            {{-- Total Poin --}}
                            <td class="px-4 py-3.5 text-center font-black text-blue-600 text-base">
                                {{ $row->total_poin }}
                            </td>

                            {{-- Waktu Selesai --}}
                            <td class="px-4 py-3.5 text-right text-xs text-gray-500">
                                {{ $row->updated_at ? \Carbon\Carbon::parse($row->updated_at)->diffForHumans() : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-12 text-center text-gray-400">
                                <div class="max-w-md mx-auto space-y-2">
                                    <div class="text-3xl">📊</div>
                                    <p class="font-medium text-gray-600">Belum ada riwayat nilai kuis untuk filter ini.</p>
                                    <p class="text-xs text-gray-400">
                                        @if ($tipeLeaderboard === 'permateri' && ! $selectedMateriId)
                                            Silakan pilih materi di atas untuk melihat nilai leaderboard.
                                        @else
                                            Coba ubah filter kelas, jurusan, atau tipe test di panel atas.
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
