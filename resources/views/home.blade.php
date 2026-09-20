@extends('layouts.app')
@section('title', 'Selamat Datang')
@section('content')
<div class="max-w-md mx-auto text-center py-10">
    <h1 class="text-2xl font-semibold mb-2">Sistem Pembelajaran</h1>
    <div class="flex flex-col gap-3 mt-6">
        <a href="{{ route('admin.login') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2.5 rounded-md">Login Guru / Superadmin</a>
        <a href="{{ route('siswa.login') }}" class="bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium py-2.5 rounded-md">Login Siswa</a>
    </div>
</div>
@endsection