@extends('layouts.app')
@section('title', 'Login Guru')
@section('content')
<div class="max-w-sm mx-auto bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-3">Login Guru / Admin</h2>
    <form action="{{ route('admin.login.attempt') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">NIP (18 Digit)</label>
            <input type="text" name="nip" value="{{ old('nip') }}" maxlength="18" placeholder="Masukkan NIP" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
            @error('nip')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input type="password" name="password" placeholder="Masukkan Password" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
            @error('password')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2.5 rounded-md transition duration-150">Login Guru</button>
    </form>
    <div class="mt-4 pt-4 border-t space-y-2 text-xs text-center text-gray-600">
        <p>Belum memiliki akun guru? <a href="{{ route('admin.register') }}" class="text-blue-600 hover:underline font-semibold">Registrasi Guru</a></p>
        <p>Login sebagai Siswa? <a href="{{ route('siswa.login') }}" class="text-amber-600 hover:underline font-semibold">Login Siswa</a></p>
    </div>
</div>
@endsection