@extends('layouts.app')
@section('title', 'Daftar Materi - Admin')

@section('content')
    <h1>Daftar Materi</h1>
    <a href="{{ route('admin.materi.create') }}" class="btn btn-primary">+ Tambah Materi</a>

    <table>
        <tr>
            <th>Judul</th>
            <th>Deskripsi</th>
            <th>File Modul</th>
            <th>Soal</th>
            <th>Aksi</th>
        </tr>
        @forelse($materis as $materi)
        <tr>
            <td>{{ $materi->judul }}</td>
            <td>{{ Str::limit($materi->deskripsi, 60) }}</td>
            <td>
                @if($materi->file_path)
                    <a href="{{ asset('storage/'.$materi->file_path) }}" target="_blank">{{ $materi->file_name }}</a>
                @else
                    <em>Belum ada file</em>
                @endif
            </td>
            <td>
                Pretest: {{ $materi->soalPretest()->count() }} |
                Posttest: {{ $materi->soalPosttest()->count() }}
            </td>
            <td>
                <a href="{{ route('admin.materi.edit', $materi) }}" class="btn btn-secondary">Edit</a>
                <form class="inline" action="{{ route('admin.materi.destroy', $materi) }}" method="POST" onsubmit="return confirm('Yakin hapus materi ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="5">Belum ada materi.</td></tr>
        @endforelse
    </table>
@endsection
