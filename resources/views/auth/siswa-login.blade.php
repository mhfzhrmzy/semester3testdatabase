@extends('layouts.app')
@section('title', 'Login Siswa')
@section('content')
<div class="max-w-sm mx-auto bg-white rounded-lg shadow p-6">
    <h2 class="text-lg font-semibold mb-4">Login Siswa</h2>
    <form action="{{ route('siswa.login.attempt') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium mb-1">NISN</label>
            <input type="text" name="nisn" value="{{ old('nisn') }}" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
            @error('nisn')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Password</label>
            <input type="password" name="password" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
            @error('password')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium py-2 rounded-md">Login</button>
    </form>
</div>
@endsection