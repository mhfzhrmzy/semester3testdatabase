@extends('layouts.app')

@section('title', 'Superadmin Portal — SMKN 2 Jember')

@section('content')
<div class="flex min-h-screen bg-slate-100 font-sans text-slate-800">
    <!-- LEFT SIDEBAR -->
    <aside class="w-64 lg:w-72 bg-white border-r border-slate-200/80 flex flex-col justify-between shrink-0 sticky top-0 h-screen overflow-y-auto z-30 shadow-sm">
        <div>
            <!-- BRAND HEADER -->
            <div class="p-5 border-b border-slate-100 flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-[#4a101d] text-white flex items-center justify-center font-black text-xl shadow-md shadow-amber-950/20 shrink-0">
                    2
                </div>
                <div>
                    <h1 class="font-extrabold text-slate-900 tracking-tight text-sm leading-snug">SMKN 2 JEMBER</h1>
                    <p class="text-[10px] font-bold text-slate-400 tracking-wider uppercase">PORTAL SUPERADMIN</p>
                </div>
            </div>

            <!-- NAVIGATION MENU -->
            <nav class="p-4 space-y-1.5" aria-label="Navigasi Utama Superadmin">
                <p class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">NAVIGASI UTAMA</p>

                <!-- Dashboard -->
                <a href="{{ route('superadmin.index', ['menu' => 'dashboard']) }}" 
                   class="px-4 py-3 rounded-2xl flex items-center gap-3 text-sm font-semibold transition {{ $menu === 'dashboard' ? 'bg-[#4a101d] text-white shadow-md shadow-amber-950/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <!-- Daftar Guru -->
                <a href="{{ route('superadmin.index', ['menu' => 'guru']) }}" 
                   class="px-4 py-3 rounded-2xl flex items-center gap-3 text-sm font-semibold transition {{ $menu === 'guru' ? 'bg-[#4a101d] text-white shadow-md shadow-amber-950/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                    </svg>
                    <span>Daftar Guru</span>
                </a>

                <!-- Daftar Siswa -->
                <a href="{{ route('superadmin.index', ['menu' => 'siswa']) }}" 
                   class="px-4 py-3 rounded-2xl flex items-center gap-3 text-sm font-semibold transition {{ $menu === 'siswa' ? 'bg-[#4a101d] text-white shadow-md shadow-amber-950/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span>Daftar Siswa</span>
                </a>

                <!-- Pengaturan & Log -->
                <a href="{{ route('superadmin.index', ['menu' => 'pengaturan']) }}" 
                   class="px-4 py-3 rounded-2xl flex items-center gap-3 text-sm font-semibold transition {{ $menu === 'pengaturan' ? 'bg-[#4a101d] text-white shadow-md shadow-amber-950/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>Pengaturan & Log</span>
                </a>
            </nav>
        </div>

        <!-- SIDEBAR FOOTER -->
        <div class="p-4 border-t border-slate-100 space-y-3">
            <!-- Logout Button -->
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2.5 text-red-600 hover:bg-red-50 hover:text-red-700 text-sm font-semibold rounded-xl transition cursor-pointer">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span>Keluar / Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN WORKSPACE -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- TOP HEADER BAR -->
        <header class="bg-white/90 backdrop-blur-md border-b border-slate-200/80 px-6 py-3.5 flex items-center justify-between sticky top-0 z-20">
            <div class="flex items-center gap-3 flex-wrap">
                <!-- Search Input -->
                <div class="relative hidden md:block">
                    <svg class="w-4 h-4 absolute left-3 top-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" placeholder="Cari NIP, NISN, Guru, Siswa, Kelas..." 
                           class="w-72 lg:w-96 pl-9 pr-4 py-1.5 text-xs rounded-xl bg-indigo-50/40 border border-indigo-100/80 text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#4a101d]/20 focus:bg-white transition">
                </div>
            </div>

            <!-- Profile & Notifications -->
            <div class="flex items-center gap-4">
                <!-- Bell Icon -->
                <button type="button" class="relative p-2 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-full transition cursor-pointer" title="Notifikasi">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                </button>

                <!-- Profile Badge -->
                <div class="flex items-center gap-3 pl-3 border-l border-slate-200">
                    <div class="text-right hidden sm:block">
                        <span class="text-xs font-bold text-slate-800 block leading-tight">
                            {{ auth('admin')->user()->nama_lengkap ?? 'Administrator Utama' }}
                        </span>
                    </div>
                    <div class="w-9 h-9 rounded-full bg-[#4a101d] text-white flex items-center justify-center font-bold text-xs shadow-md shadow-amber-950/20 ring-2 ring-[#4a101d]/20 shrink-0">
                        {{ strtoupper(substr(auth('admin')->user()->nama_lengkap ?? 'AU', 0, 2)) }}
                    </div>
                </div>
            </div>
        </header>

        <!-- PAGE CONTENT -->
        <main class="p-6 lg:p-8 space-y-6">
            <!-- Flash Session Alerts -->
            @if (session('success'))
                <div class="rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3.5 text-sm font-semibold flex items-center gap-3 shadow-sm">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-2xl bg-red-50 border border-red-200 text-red-800 px-5 py-3.5 text-sm font-semibold space-y-1 shadow-sm">
                    <p class="font-bold flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Terjadi kesalahan pada input:
                    </p>
                    <ul class="list-disc list-inside text-xs space-y-0.5 pl-6 font-normal">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($menu === 'dashboard')
                <!-- DASHBOARD VIEW (IKHTISAR OPERASIONAL) -->
                <div class="space-y-6">
                    <!-- Header Row -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Ikhtisar Operasional</h1>
                            <p class="text-xs text-slate-500 mt-1">Pusat kendali manajemen akun guru dan siswa SMKN 2 Jember.</p>
                        </div>
                        <div class="flex items-center gap-3 flex-wrap">
                            <a href="{{ route('superadmin.index', ['menu' => 'guru']) }}" 
                               class="bg-[#4a101d] hover:bg-[#380b15] text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-md shadow-amber-950/20 flex items-center gap-2 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                                + Tambah Akun Guru
                            </a>
                            <a href="{{ route('superadmin.index', ['menu' => 'siswa']) }}" 
                               class="bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-bold px-4 py-2.5 rounded-xl shadow-md flex items-center gap-2 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                + Tambah Akun Siswa
                            </a>
                        </div>
                    </div>

                    <!-- 4 STAT CARDS GRID -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Card 1: Total Guru -->
                        <div class="bg-white rounded-2xl p-5 border border-slate-200/70 shadow-sm">
                            <p class="text-[10px] font-bold text-slate-400 tracking-wider uppercase mb-2">TOTAL AKUN GURU</p>
                            <div class="flex items-baseline gap-2">
                                <span class="text-2xl font-black text-slate-900 tracking-tight">
                                    {{ $gurus->count() }}
                                </span>
                                <span class="text-xs font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-full border border-blue-200/60">Pendidik</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-2 font-medium">Terdaftar di sistem</p>
                        </div>

                        <!-- Card 2: Total Siswa -->
                        <div class="bg-white rounded-2xl p-5 border border-slate-200/70 shadow-sm">
                            <p class="text-[10px] font-bold text-slate-400 tracking-wider uppercase mb-2">TOTAL AKUN SISWA</p>
                            <div class="flex items-baseline gap-2">
                                <span class="text-2xl font-black text-slate-900 tracking-tight">
                                    {{ $siswas->count() }}
                                </span>
                                <span class="text-xs font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200/60">Peserta Didik</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-2 font-medium">Terdaftar di sistem</p>
                        </div>

                        <!-- Card 3: Modul Materi -->
                        <div class="bg-white rounded-2xl p-5 border border-slate-200/70 shadow-sm">
                            <p class="text-[10px] font-bold text-slate-400 tracking-wider uppercase mb-2">MODUL MATERI</p>
                            <div class="flex items-baseline gap-2">
                                <span class="text-2xl font-black text-slate-900 tracking-tight">
                                    {{ $materiCount }}
                                </span>
                                <span class="text-xs font-semibold text-slate-600">Modul</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-2 font-medium">Materi Pembelajaran Active</p>
                        </div>

                        <!-- Card 4: Quiz -->
                        <div class="bg-white rounded-2xl p-5 border border-slate-200/70 shadow-sm">
                            <p class="text-[10px] font-bold text-slate-400 tracking-wider uppercase mb-2">QUIZ & EVALUASI</p>
                            <div class="flex items-baseline gap-2">
                                <span class="text-2xl font-black text-slate-900 tracking-tight">
                                    {{ $quizCount }}
                                </span>
                                <span class="text-xs font-semibold text-slate-600">Quiz</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-2 font-medium">Ujian & Praktikum Active</p>
                        </div>
                    </div>

                    <!-- LOWER 2-COLUMN SECTION: DAFTAR GURU & DAFTAR SISWA -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- LEFT COLUMN: DAFTAR AKUN GURU -->
                        <div class="bg-white rounded-2xl p-5 border border-slate-200/70 shadow-sm space-y-4">
                            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center font-bold">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-base font-bold text-slate-900">Daftar Akun Guru</h2>
                                        <p class="text-[11px] text-slate-500">Total {{ $gurus->count() }} Guru Terdaftar</p>
                                    </div>
                                </div>
                                <a href="{{ route('superadmin.index', ['menu' => 'guru']) }}" class="text-xs font-bold text-[#4a101d] hover:underline flex items-center gap-1">
                                    Kelola Guru &rarr;
                                </a>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-xs">
                                    <thead>
                                        <tr class="border-b border-slate-200 bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold">
                                            <th class="px-3 py-2 rounded-l-lg">NIP</th>
                                            <th class="px-3 py-2">Nama</th>
                                            <th class="px-3 py-2 text-right rounded-r-lg">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @forelse ($gurus as $guru)
                                            <tr class="hover:bg-slate-50/80 transition">
                                                <td class="px-3 py-2 font-semibold text-slate-700">{{ $guru->nip }}</td>
                                                <td class="px-3 py-2 font-bold text-slate-900">{{ $guru->nama_lengkap }}</td>
                                                <td class="px-3 py-2">
                                                    <div class="flex items-center justify-end gap-2">
                                                        <form action="{{ route('admin.destroy', $guru) }}" method="POST" onsubmit="return confirm('Hapus akun guru ini?')">
                                                            @csrf @method('DELETE')
                                                            <input type="hidden" name="from" value="superadmin">
                                                            <button type="submit" class="text-red-600 font-bold hover:underline cursor-pointer">Hapus</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="px-3 py-6 text-center text-slate-400 font-medium">Belum ada akun guru.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- RIGHT COLUMN: DAFTAR AKUN SISWA -->
                        <div class="bg-white rounded-2xl p-5 border border-slate-200/70 shadow-sm space-y-4">
                            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-base font-bold text-slate-900">Daftar Akun Siswa</h2>
                                        <p class="text-[11px] text-slate-500">Total {{ $siswas->count() }} Siswa Terdaftar</p>
                                    </div>
                                </div>
                                <a href="{{ route('superadmin.index', ['menu' => 'siswa']) }}" class="text-xs font-bold text-amber-600 hover:underline flex items-center gap-1">
                                    Kelola Siswa &rarr;
                                </a>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-xs">
                                    <thead>
                                        <tr class="border-b border-slate-200 bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold">
                                            <th class="px-3 py-2 rounded-l-lg">NISN</th>
                                            <th class="px-3 py-2">Nama</th>
                                            <th class="px-3 py-2 text-right rounded-r-lg">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @forelse ($siswas as $siswa)
                                            <tr class="hover:bg-slate-50/80 transition">
                                                <td class="px-3 py-2 font-semibold text-slate-700">{{ $siswa->nisn }}</td>
                                                <td class="px-3 py-2 font-bold text-slate-900">{{ $siswa->nama_lengkap }}</td>
                                                <td class="px-3 py-2">
                                                    <div class="flex items-center justify-end gap-2">
                                                        <form action="{{ route('siswa.destroy', $siswa) }}" method="POST" onsubmit="return confirm('Hapus akun siswa ini?')">
                                                            @csrf @method('DELETE')
                                                            <input type="hidden" name="from" value="superadmin">
                                                            <button type="submit" class="text-red-600 font-bold hover:underline cursor-pointer">Hapus</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="px-3 py-6 text-center text-slate-400 font-medium">Belum ada akun siswa.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            @elseif ($menu === 'guru')
                <!-- GURU MANAGEMENT VIEW -->
                <div>
                    <div class="flex items-center justify-between gap-4 mb-6">
                        <div>
                            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Kelola Akun Guru</h1>
                            <p class="text-xs text-slate-500 mt-1">Tambah, perbarui, atau hapus data akun guru pengampu.</p>
                        </div>
                        <span class="rounded-full bg-blue-100 text-blue-700 px-3.5 py-1 text-xs font-bold shadow-xs">
                            {{ $gurus->count() }} Akun Registered
                        </span>
                    </div>

                    <!-- TAMBAH GURU FORM -->
                    <div class="mb-6 bg-white rounded-2xl p-6 border border-slate-200/70 shadow-sm">
                        <h2 class="mb-4 text-base font-bold text-slate-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#4a101d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            Tambah Akun Guru Baru
                        </h2>
                        <form action="{{ route('admin.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            @csrf
                            <input type="hidden" name="from" value="superadmin">
                            <div>
                                <label class="mb-1 block text-xs font-bold text-slate-700">NIP (18 Digit)</label>
                                <input type="text" name="nip" value="{{ old('nip') }}" maxlength="18" inputmode="numeric" pattern="[0-9]*" required 
                                       placeholder="19890412..."
                                       class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-[#4a101d]/20 transition">
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-bold text-slate-700">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required 
                                       placeholder="Siti Rahmawati, S.Pd"
                                       class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-[#4a101d]/20 transition">
                            </div>

                            <div>
                                <label class="mb-1 block text-xs font-bold text-slate-700">Password</label>
                                <input type="password" name="password" required 
                                       placeholder="Minimal 6 karakter"
                                       class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-[#4a101d]/20 transition">
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-bold text-slate-700">Foto Profil <span class="font-normal text-slate-400">(opsional)</span></label>
                                <input type="file" name="foto_profile" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                            </div>
                            <div class="flex items-end md:justify-end">
                                <button type="submit" class="w-full md:w-auto rounded-xl bg-[#4a101d] px-5 py-2.5 text-xs font-bold text-white shadow-md shadow-amber-950/20 hover:bg-[#380b15] transition cursor-pointer">
                                    + Tambah Akun Guru
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- DAFTAR GURU TABLE -->
                    <div class="bg-white rounded-2xl p-6 border border-slate-200/70 shadow-sm">
                        <h2 class="mb-4 text-base font-bold text-slate-900">Daftar Akun Guru Registered</h2>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <thead>
                                    <tr class="border-b border-slate-200 bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold">
                                        <th class="px-4 py-3 rounded-l-xl">NIP</th>
                                        <th class="px-4 py-3">Nama Lengkap</th>
                                        <th class="px-4 py-3 text-right rounded-r-xl">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse ($gurus as $guru)
                                        <tr class="hover:bg-slate-50/80 transition">
                                            <td class="px-4 py-3 font-semibold text-slate-700">{{ $guru->nip }}</td>
                                            <td class="px-4 py-3 font-bold text-slate-900">{{ $guru->nama_lengkap }}</td>
                                            <td class="px-4 py-3">
                                                <span class="bg-purple-50 text-purple-700 border border-purple-200/60 font-bold px-2.5 py-0.5 rounded-full text-[10px] uppercase">
                                                    {{ $guru->role }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center justify-end gap-3">
                                                    <form action="{{ route('admin.destroy', $guru) }}" method="POST" onsubmit="return confirm('Hapus akun guru ini?')">
                                                        @csrf @method('DELETE')
                                                        <input type="hidden" name="from" value="superadmin">
                                                        <button type="submit" class="text-red-600 font-bold hover:underline cursor-pointer">Hapus</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-4 py-8 text-center text-slate-400 font-medium">Belum ada akun guru registered.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            @elseif ($menu === 'siswa')
                <!-- SISWA MANAGEMENT VIEW -->
                <div>
                    <div class="flex items-center justify-between gap-4 mb-6">
                        <div>
                            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Kelola Akun Siswa</h1>
                            <p class="text-xs text-slate-500 mt-1">Tambah, perbarui, atau hapus data akun siswa.</p>
                        </div>
                        <span class="rounded-full bg-amber-100 text-amber-800 px-3.5 py-1 text-xs font-bold shadow-xs">
                            {{ $siswas->count() }} Siswa Registered
                        </span>
                    </div>

                    <!-- TAMBAH SISWA FORM -->
                    <div class="mb-6 bg-white rounded-2xl p-6 border border-slate-200/70 shadow-sm">
                        <h2 class="mb-4 text-base font-bold text-slate-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#4a101d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            Tambah Akun Siswa Baru
                        </h2>
                        <form action="{{ route('siswa.store') }}" method="POST" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            @csrf
                            <input type="hidden" name="from" value="superadmin">
                            <div>
                                <label class="mb-1 block text-xs font-bold text-slate-700">NISN (10 Digit)</label>
                                <input type="text" name="nisn" value="{{ old('nisn') }}" maxlength="10" inputmode="numeric" pattern="[0-9]*" required 
                                       placeholder="0081293812"
                                       class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-[#4a101d]/20 transition">
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-bold text-slate-700">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required 
                                       placeholder="Andi Pratama"
                                       class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-[#4a101d]/20 transition">
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-bold text-slate-700">Password</label>
                                <input type="password" name="password" required 
                                       placeholder="Minimal 6 karakter"
                                       class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-[#4a101d]/20 transition">
                            </div>
                            <div class="flex items-end md:col-span-2 md:justify-end">
                                <button type="submit" class="w-full md:w-auto rounded-xl bg-amber-500 px-5 py-2.5 text-xs font-bold text-slate-950 shadow-md hover:bg-amber-400 transition cursor-pointer">
                                    + Tambah Akun Siswa
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- DAFTAR SISWA TABLE -->
                    <div class="bg-white rounded-2xl p-6 border border-slate-200/70 shadow-sm">
                        <h2 class="mb-4 text-base font-bold text-slate-900">Daftar Akun Siswa Registered</h2>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <thead>
                                    <tr class="border-b border-slate-200 bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold">
                                        <th class="px-4 py-3 rounded-l-xl">NISN</th>
                                        <th class="px-4 py-3">Nama Lengkap</th>
                                        <th class="px-4 py-3 text-right rounded-r-xl">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse ($siswas as $siswa)
                                        <tr class="hover:bg-slate-50/80 transition">
                                            <td class="px-4 py-3 font-semibold text-slate-700">{{ $siswa->nisn }}</td>
                                            <td class="px-4 py-3 font-bold text-slate-900">{{ $siswa->nama_lengkap }}</td>
                                            <td class="px-4 py-3 font-bold text-amber-600">{{ $siswa->poin ?? 0 }} XP</td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center justify-end gap-3">
                                                    <form action="{{ route('siswa.destroy', $siswa) }}" method="POST" onsubmit="return confirm('Hapus akun siswa ini?')">
                                                        @csrf @method('DELETE')
                                                        <input type="hidden" name="from" value="superadmin">
                                                        <button type="submit" class="text-red-600 font-bold hover:underline cursor-pointer">Hapus</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-4 py-8 text-center text-slate-400 font-medium">Belum ada akun siswa registered.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            @elseif ($menu === 'pengaturan')
                <!-- PENGATURAN & LOG VIEW -->
                <div class="space-y-6">
                    <div>
                        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Pengaturan & Log Sistem</h1>
                        <p class="text-xs text-slate-500 mt-1">Konfigurasi pusat, status node server, dan riwayat aktivitas superadmin.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Card Status System -->
                        <div class="bg-white rounded-2xl p-6 border border-slate-200/70 shadow-sm space-y-4">
                            <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Node Server & Integritas Dapodik
                            </h2>
                            <div class="space-y-3 text-xs">
                                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50">
                                    <span class="text-slate-600 font-semibold">Primary Server Node</span>
                                    <span class="font-bold text-slate-900">TKJ-SRV01.SMKN2JBR</span>
                                </div>
                                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50">
                                    <span class="text-slate-600 font-semibold">Status Koneksi Dapodik</span>
                                    <span class="text-emerald-700 font-bold bg-emerald-100 px-2.5 py-0.5 rounded-md">Tersinkron 100%</span>
                                </div>
                                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50">
                                    <span class="text-slate-600 font-semibold">Versi PHP / Framework</span>
                                    <span class="font-bold text-slate-900">PHP {{ PHP_VERSION }} • Laravel {{ app()->version() }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Card Quick Backup -->
                        <div class="bg-white rounded-2xl p-6 border border-slate-200/70 shadow-sm space-y-4">
                            <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                                </svg>
                                Pemeliharaan & Cadangan Data
                            </h2>
                            <p class="text-xs text-slate-500">Cadangkan basis data siswa, guru, modul materi, dan sertifikat dalam satu klik.</p>
                            <div class="flex items-center gap-3">
                                <button type="button" onclick="alert('Backup database berhasil dibuat!')" class="bg-[#4a101d] text-white text-xs font-bold px-4 py-2.5 rounded-xl hover:bg-[#380b15] transition cursor-pointer">
                                    💾 Buat Cadangan Database (.SQL)
                                </button>
                                <button type="button" onclick="alert('Log audit dibersihkan.')" class="border border-slate-200 text-slate-700 text-xs font-bold px-4 py-2.5 rounded-xl hover:bg-slate-50 transition cursor-pointer">
                                    🧹 Bersihkan Cache System
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Audit Log Table -->
                    <div class="bg-white rounded-2xl p-6 border border-slate-200/70 shadow-sm">
                        <h2 class="mb-4 text-base font-bold text-slate-900">Riwayat Log Aktivitas Sistem</h2>
                        <div class="space-y-3 text-xs">
                            <div class="flex items-center justify-between p-3 rounded-xl border border-slate-100">
                                <div>
                                    <span class="font-bold text-slate-900 block">Sinkronisasi Otomatis Dapodik</span>
                                    <span class="text-slate-500">Node TKJ-SRV01 berhasil memperbarui 42 data rombel</span>
                                </div>
                                <span class="text-slate-400 font-medium">Hari ini, 08:42 WIB</span>
                            </div>
                            <div class="flex items-center justify-between p-3 rounded-xl border border-slate-100">
                                <div>
                                    <span class="font-bold text-slate-900 block">Verifikasi Akun Guru Pengampu</span>
                                    <span class="text-slate-500">Administrator Utama menyetujui penambahan 2 akun guru baru</span>
                                </div>
                                <span class="text-slate-400 font-medium">Kemarin, 14:15 WIB</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </main>
    </div>
</div>

<!-- EDIT GURU MODAL -->
<div id="edit-guru-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 border border-slate-200">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="font-black text-slate-900 text-base">Edit Akun Guru</h3>
            <button type="button" onclick="closeEditGuruModal()" class="text-slate-400 hover:text-slate-600 text-xl font-bold px-2 cursor-pointer">&times;</button>
        </div>
        <form id="edit-guru-form" action="" method="POST" enctype="multipart/form-data" class="space-y-3">
            @csrf
            @method('PUT')
            <input type="hidden" name="from" value="superadmin">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">NIP (Nomor Induk Pegawai)</label>
                <input type="text" id="edit-guru-nip" name="nip" maxlength="18" inputmode="numeric" pattern="[0-9]*" required readonly class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3.5 py-2 text-xs text-slate-500 cursor-not-allowed">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap</label>
                <input type="text" id="edit-guru-nama" name="nama_lengkap" required class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-[#4a101d]/20 transition">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Password Baru <span class="font-normal text-slate-400">(Opsional)</span></label>
                <input type="password" name="password" placeholder="Kosongkan jika tidak diubah" class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-[#4a101d]/20 transition">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Foto Profil <span class="font-normal text-slate-400">(Opsional)</span></label>
                <input type="file" name="foto_profile" accept="image/*" class="w-full text-xs text-slate-500">
            </div>
            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="closeEditGuruModal()" class="px-4 py-2 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition cursor-pointer">Batal</button>
                <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-[#4a101d] hover:bg-[#380b15] rounded-xl transition cursor-pointer">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT SISWA MODAL -->
<div id="edit-siswa-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 border border-slate-200">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="font-black text-slate-900 text-base">Edit Akun Siswa</h3>
            <button type="button" onclick="closeEditSiswaModal()" class="text-slate-400 hover:text-slate-600 text-xl font-bold px-2 cursor-pointer">&times;</button>
        </div>
        <form id="edit-siswa-form" action="" method="POST" class="space-y-3">
            @csrf
            @method('PUT')
            <input type="hidden" name="from" value="superadmin">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">NISN (Nomor Induk Siswa Nasional)</label>
                <input type="text" id="edit-siswa-nisn" name="nisn" maxlength="10" inputmode="numeric" pattern="[0-9]*" required readonly class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3.5 py-2 text-xs text-slate-500 cursor-not-allowed">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap</label>
                <input type="text" id="edit-siswa-nama" name="nama_lengkap" required class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-[#4a101d]/20 transition">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Password Baru <span class="font-normal text-slate-400">(Opsional)</span></label>
                <input type="password" name="password" placeholder="Kosongkan jika tidak diubah" class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-[#4a101d]/20 transition">
            </div>
            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="closeEditSiswaModal()" class="px-4 py-2 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition cursor-pointer">Batal</button>
                <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-[#4a101d] hover:bg-[#380b15] rounded-xl transition cursor-pointer">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditGuruModal(nip, nama) {
        document.getElementById('edit-guru-form').action = "/admin/" + encodeURIComponent(nip);
        document.getElementById('edit-guru-nip').value = nip;
        document.getElementById('edit-guru-nama').value = nama;
        document.getElementById('edit-guru-modal').classList.remove('hidden');
    }
    function closeEditGuruModal() {
        document.getElementById('edit-guru-modal').classList.add('hidden');
    }

    function openEditSiswaModal(nisn, nama) {
        document.getElementById('edit-siswa-form').action = "/siswa/" + encodeURIComponent(nisn);
        document.getElementById('edit-siswa-nisn').value = nisn;
        document.getElementById('edit-siswa-nama').value = nama;
        document.getElementById('edit-siswa-modal').classList.remove('hidden');
    }
    function closeEditSiswaModal() {
        document.getElementById('edit-siswa-modal').classList.add('hidden');
    }
</script>
@endsection