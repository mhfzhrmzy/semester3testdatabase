@extends('layouts.app')

@section('title', 'Portal Siswa - Daftar Materi')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-lg font-semibold mb-4">Daftar Materi</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="border-b bg-gray-50">
                    <th class="px-3 py-2">Judul</th>
                    <th class="px-3 py-2">Guru Pengampu</th>
                    <th class="px-3 py-2 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($materis as $materi)
                    <tr class="border-b">
                        <td class="px-3 py-2">{{ $materi->judul_materi }}</td>
                        <td class="px-3 py-2">{{ $materi->admin->nama_lengkap ?? '-' }}</td>
                        <td class="px-3 py-2 text-right">
                            <a href="{{ route('portal.materi.show', $materi) }}"
                               class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium px-3 py-1.5 rounded-md">
                                Buka
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-3 py-6 text-center text-gray-400">Belum ada materi tersedia.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection