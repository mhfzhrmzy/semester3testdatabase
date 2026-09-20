@extends('layouts.app')
@section('title', 'Login Siswa')
@section('content')
<div class="max-w-sm mx-auto bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-3">Login Siswa</h2>
    <form action="{{ route('siswa.login.attempt') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">NISN (10 Digit)</label>
            <input type="text" name="nisn" value="{{ old('nisn') }}" maxlength="10" placeholder="Masukkan NISN" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none">
            @error('nisn')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input type="password" name="password" placeholder="Masukkan Password" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none">
            @error('password')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium py-2.5 rounded-md transition duration-150">Login Siswa</button>
    </form>
    <div class="mt-4 pt-4 border-t space-y-2 text-xs text-center text-gray-600">
        <p>Belum memiliki akun siswa? <a href="{{ route('siswa.register') }}" class="text-amber-600 hover:underline font-semibold">Registrasi Siswa</a></p>
        <p>Login sebagai Guru? <a href="{{ route('admin.login') }}" class="text-blue-600 hover:underline font-semibold">Login Guru</a></p>
    </div>
</div>
@endsection