@extends('layouts.app')
@section('title', 'Selamat Datang')
@section('content')
<div class="max-w-xl mx-auto text-center py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-3">Selamat Datang di Portal Pembelajaran</h1>
    <p class="text-gray-600 mb-8">Silakan masuk atau mendaftar sesuai peran Anda (Guru / Siswa) untuk mengakses materi dan kuis.</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Section Guru -->
        <div class="border rounded-xl p-6 bg-gradient-to-b from-blue-50 to-white shadow-sm flex flex-col justify-between">
            <div>
                <div class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 font-bold text-lg">
                    👨‍🏫
                </div>
                <h2 class="text-lg font-bold text-gray-800 mb-1">Area Guru / Admin</h2>
                <p class="text-xs text-gray-500 mb-6">Kelola materi, kuis, soal, serta nilai siswa</p>
            </div>
            <div class="space-y-2">
                <a href="{{ route('admin.login') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 rounded-lg transition duration-150">Login Guru</a>
                <a href="{{ route('admin.register') }}" class="block w-full border border-blue-600 text-blue-600 hover:bg-blue-50 text-sm font-medium py-2 rounded-lg transition duration-150">Registrasi Guru Baru</a>
            </div>
        </div>

        <!-- Section Siswa -->
        <div class="border rounded-xl p-6 bg-gradient-to-b from-amber-50 to-white shadow-sm flex flex-col justify-between">
            <div>
                <div class="w-12 h-12 bg-amber-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 font-bold text-lg">
                    🎓
                </div>
                <h2 class="text-lg font-bold text-gray-800 mb-1">Portal Siswa</h2>
                <p class="text-xs text-gray-500 mb-6">Pelajari materi dan kerjakan kuis pembelajaran</p>
            </div>
            <div class="space-y-2">
                <a href="{{ route('siswa.login') }}" class="block w-full bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium py-2 rounded-lg transition duration-150">Login Siswa</a>
                <a href="{{ route('siswa.register') }}" class="block w-full border border-amber-600 text-amber-600 hover:bg-amber-50 text-sm font-medium py-2 rounded-lg transition duration-150">Registrasi Siswa Baru</a>
            </div>
        </div>
    </div>
</div>
@endsection