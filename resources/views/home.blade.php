@extends('layouts.app')
@section('title', 'Selamat Datang')
@section('content')
<div class="min-h-screen w-full flex flex-col md:flex-row bg-[#0A2342] relative overflow-hidden font-sans">

    <!-- LEFT AREA: Deep Blue Branding & Title Section -->
    <div class="hidden md:flex md:w-[52%] lg:w-[56%] min-h-screen bg-gradient-to-br from-[#0B2A4C] via-[#0A2342] to-[#071930] flex-col justify-center px-12 lg:px-20 text-white relative">
        <div class="max-w-xl relative z-10">
            <div class="flex items-center gap-3 mb-4">
                <span class="text-xs font-semibold uppercase tracking-widest text-slate-300">PORTAL AKADEMIK</span>
            </div>
            <h1 class="text-4xl lg:text-5xl font-extrabold tracking-tight text-white leading-tight font-sans">
                e-Learning TIK SMKN 2 Jember
            </h1>
        </div>
    </div>

    <!-- RIGHT AREA: White Curved Form & Action Container -->
    <div class="relative w-full md:w-[48%] lg:w-[44%] min-h-screen flex items-center z-10">
        <!-- Secondary Stacked Blue Backdrop Layer (left edge of white card) -->
        <div class="hidden md:block absolute top-0 bottom-0 right-0 left-[-14px] bg-[#1A3A66] rounded-l-[3.5rem] shadow-xl pointer-events-none"></div>

        <!-- Primary White Card -->
        <div class="w-full h-full min-h-screen bg-white rounded-l-none md:rounded-l-[3.2rem] shadow-2xl px-6 sm:px-12 lg:px-16 py-12 flex flex-col justify-between relative z-10">

            <!-- Main Portal Section -->
            <div class="my-auto py-8">
                <div class="mb-8">
                    <h2 class="text-3xl font-extrabold text-[#0A2342] tracking-tight">Selamat Datang</h2>
                    <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                        Silakan pilih peranan Anda di bawah ini untuk mengakses sistem pembelajaran digital.
                    </p>
                </div>

                <!-- Action Cards -->
                <div class="space-y-4">
                    <!-- Option Siswa -->
                    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 hover:border-slate-300 transition">
                        <div class="flex items-center gap-3 mb-3">
                            <div>
                                <h3 class="font-bold text-sm text-slate-800">Portal Siswa</h3>
                                <p class="text-xs text-slate-500">Materi, kuis, &amp; nilai hasil belajar</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2 mt-4">
                            <a href="{{ route('siswa.login') }}" class="w-full bg-[#0A2342] hover:bg-[#061529] text-white text-xs font-semibold py-3 px-3 rounded-xl text-center shadow-sm transition">
                                Login Siswa
                            </a>
                            <a href="{{ route('siswa.register') }}" class="w-full bg-white hover:bg-slate-100 text-[#3B5284] border border-[#3B5284]/40 text-xs font-semibold py-3 px-3 rounded-xl text-center transition">
                                Daftar Siswa
                            </a>
                        </div>
                    </div>

                    <!-- Option Guru -->
                    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 hover:border-slate-300 transition">
                        <div class="flex items-center gap-3 mb-3">
                            <div>
                                <h3 class="font-bold text-sm text-slate-800">Area Guru / Admin</h3>
                                <p class="text-xs text-slate-500">Kelola materi, bank soal, &amp; nilai</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2 mt-4">
                            <a href="{{ route('admin.login') }}" class="w-full bg-[#0A2342] hover:bg-[#061529] text-white text-xs font-semibold py-3 px-3 rounded-xl text-center shadow-sm transition">
                                Login Guru
                            </a>
                            <a href="{{ route('admin.register') }}" class="w-full bg-white hover:bg-slate-100 text-slate-700 border border-slate-300 text-xs font-semibold py-3 px-3 rounded-xl text-center transition">
                                Daftar Guru
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer info -->
            <div class="text-xs text-slate-400 border-t border-slate-100 pt-4 flex justify-between items-center">
                <span>&copy; 2026 SMKN 2 Jember</span>
                <span class="text-slate-400">e-Learning TIK</span>
            </div>
        </div>
    </div>

</div>
@endsection