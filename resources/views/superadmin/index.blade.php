@extends('layouts.app')
@section('title', 'Superadmin')
@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div>
        <h2 class="text-lg font-semibold mb-4">Akun Guru</h2>
        <table class="w-full text-sm text-left">
            <thead><tr class="border-b bg-gray-50"><th class="px-3 py-2">NIP</th><th class="px-3 py-2">Nama</th><th class="px-3 py-2">Role</th></tr></thead>
            <tbody>
                @forelse ($gurus as $guru)
                    <tr class="border-b"><td class="px-3 py-2">{{ $guru->nip }}</td><td class="px-3 py-2">{{ $guru->nama_lengkap }}</td><td class="px-3 py-2">{{ $guru->role }}</td></tr>
                @empty
                    <tr><td colspan="3" class="px-3 py-6 text-center text-gray-400">Belum ada akun guru.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div>
        <h2 class="text-lg font-semibold mb-4">Akun Siswa</h2>
        <table class="w-full text-sm text-left">
            <thead><tr class="border-b bg-gray-50"><th class="px-3 py-2">NISN</th><th class="px-3 py-2">Nama</th><th class="px-3 py-2">Email</th></tr></thead>
            <tbody>
                @forelse ($siswas as $siswa)
                    <tr class="border-b"><td class="px-3 py-2">{{ $siswa->nisn }}</td><td class="px-3 py-2">{{ $siswa->nama_lengkap }}</td><td class="px-3 py-2">{{ $siswa->email }}</td></tr>
                @empty
                    <tr><td colspan="3" class="px-3 py-6 text-center text-gray-400">Belum ada akun siswa.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection