@extends('layouts.app')
@section('title', 'Login Guru')
@section('content')
<div class="max-w-sm mx-auto bg-white rounded-lg shadow p-6">
    <h2 class="text-lg font-semibold mb-4">Login Guru</h2>
    <form action="{{ route('admin.login.attempt') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium mb-1">NIP</label>
            <input type="text" name="nip" value="{{ old('nip') }}" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
            @error('nip')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Password</label>
            <input type="password" name="password" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
            @error('password')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 rounded-md">Login</button>
    </form>
    <p class="text-xs text-gray-400 mt-4 text-center">Siswa? <a href="{{ route('siswa.login') }}" class="text-blue-600 hover:underline">Login di sini</a></p>
</div>
@endsection