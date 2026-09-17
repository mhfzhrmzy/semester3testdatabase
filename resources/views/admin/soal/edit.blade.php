@extends('layouts.app')
@section('title', 'Edit Soal')

@section('content')
    <h1>Edit Soal</h1>

    <form action="{{ route('admin.soal.update', $soal) }}" method="POST">
        @csrf @method('PUT')

        <div class="field">
            <label>Materi</label>
            <select name="materi_id" required>
                @foreach($materis as $m)
                    <option value="{{ $m->id }}" {{ $soal->materi_id == $m->id ? 'selected' : '' }}>{{ $m->judul }}</option>
                @endforeach
            </select>
        </div>

        <div class="field">
            <label>Tipe Soal</label>
            <select name="tipe" required>
                <option value="pretest" {{ $soal->tipe == 'pretest' ? 'selected' : '' }}>Pretest</option>
                <option value="posttest" {{ $soal->tipe == 'posttest' ? 'selected' : '' }}>Posttest</option>
            </select>
        </div>

        <div class="field">
            <label>Pertanyaan</label>
            <textarea name="pertanyaan" rows="2" required>{{ $soal->pertanyaan }}</textarea>
        </div>

        <div class="field">
            <label>Pilihan A</label>
            <input type="text" name="pilihan_a" value="{{ $soal->pilihan_a }}" required>
        </div>
        <div class="field">
            <label>Pilihan B</label>
            <input type="text" name="pilihan_b" value="{{ $soal->pilihan_b }}" required>
        </div>
        <div class="field">
            <label>Pilihan C</label>
            <input type="text" name="pilihan_c" value="{{ $soal->pilihan_c }}" required>
        </div>
        <div class="field">
            <label>Pilihan D</label>
            <input type="text" name="pilihan_d" value="{{ $soal->pilihan_d }}" required>
        </div>

        <div class="field">
            <label>Jawaban Benar</label>
            <select name="jawaban_benar" required>
                @foreach(['a','b','c','d'] as $opt)
                    <option value="{{ $opt }}" {{ $soal->jawaban_benar == $opt ? 'selected' : '' }}>{{ strtoupper($opt) }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('admin.soal.index') }}" class="btn btn-secondary">Batal</a>
    </form>
@endsection
