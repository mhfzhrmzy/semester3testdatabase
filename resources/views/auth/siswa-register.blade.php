@extends('layouts.app')
@section('title', 'Registrasi Siswa')
@section('content')
<div class="max-w-md mx-auto bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-semibold mb-6 text-gray-800 border-b pb-3">Registrasi Siswa Baru</h2>
    <form action="{{ route('siswa.register.attempt') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">NISN (10 Digit)</label>
            <input type="text" name="nisn" value="{{ old('nisn') }}" placeholder="Contoh: 0051234567" maxlength="10" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none">
            @error('nisn')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" placeholder="Masukkan nama lengkap siswa" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none">
            @error('nama_lengkap')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="siswa@sekolah.sch.id" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none">
            @error('email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input type="password" name="password" placeholder="Minimal 6 karakter" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none">
            @error('password')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" placeholder="Ulangi password" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none">
        </div>
        <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium py-2.5 rounded-md transition duration-150">Daftar Siswa</button>
    </form>
    <div class="mt-4 pt-4 border-t text-center text-xs text-gray-600">
        Sudah memiliki akun? <a href="{{ route('siswa.login') }}" class="text-amber-600 hover:underline font-semibold">Login di sini</a>
    </div>
</div>
@endsection
