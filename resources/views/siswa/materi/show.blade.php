@extends('layouts.app')
@section('title', $materi->judul)

@section('content')
    <a href="{{ route('siswa.materi.index') }}">&larr; Kembali ke daftar materi</a>
    <h1>{{ $materi->judul }}</h1>
    <p>{{ $materi->deskripsi }}</p>

    <div class="field">
        <label>Modul Materi</label>
        @if($materi->file_path)
            <a href="{{ asset('storage/'.$materi->file_path) }}" target="_blank" class="btn btn-secondary">
                📄 Download / Lihat: {{ $materi->file_name }}
            </a>
        @else
            <p><em>Belum ada file modul untuk materi ini.</em></p>
        @endif
    </div>

    <hr>

    <div class="field">
        <label>Uji Pemahaman</label>
        <p>
            <a href="{{ route('siswa.soal.kerjakan', [$materi, 'pretest']) }}" class="btn btn-primary">
                Kerjakan Pretest ({{ $materi->soalPretest()->count() }} soal)
            </a>
            &nbsp;
            <a href="{{ route('siswa.soal.kerjakan', [$materi, 'posttest']) }}" class="btn btn-primary">
                Kerjakan Posttest ({{ $materi->soalPosttest()->count() }} soal)
            </a>
        </p>
    </div>
@endsection
