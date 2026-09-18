@extends('layouts.app')
@section('title', 'Daftar Materi - Siswa')

@section('content')
    <h1>Daftar Materi</h1>

    <table>
        <tr>
            <th>Judul</th>
            <th>Deskripsi</th>
            <th>Aksi</th>
        </tr>
        @forelse($materis as $materi)
        <tr>
            <td>{{ $materi->judul }}</td>
            <td>{{ Str::limit($materi->deskripsi, 80) }}</td>
            <td><a href="{{ route('siswa.materi.show', $materi) }}" class="btn btn-primary">Buka</a></td>
        </tr>
        @empty
        <tr><td colspan="3">Belum ada materi tersedia.</td></tr>
        @endforelse
    </table>
@endsection
