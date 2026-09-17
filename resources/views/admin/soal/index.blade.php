@extends('layouts.app')
@section('title', 'Bank Soal - Admin')

@section('content')
    <h1>Bank Soal (Pretest & Posttest)</h1>

    <form method="GET" action="{{ route('admin.soal.index') }}" style="margin-bottom:16px; display:flex; gap:10px; align-items:end;">
        <div class="field" style="margin:0;">
            <label>Filter Materi</label>
            <select name="materi_id" onchange="this.form.submit()">
                <option value="">-- Semua Materi --</option>
                @foreach($materis as $m)
                    <option value="{{ $m->id }}" {{ request('materi_id') == $m->id ? 'selected' : '' }}>{{ $m->judul }}</option>
                @endforeach
            </select>
        </div>
        <div class="field" style="margin:0;">
            <label>Filter Tipe</label>
            <select name="tipe" onchange="this.form.submit()">
                <option value="">-- Semua Tipe --</option>
                <option value="pretest" {{ request('tipe') == 'pretest' ? 'selected' : '' }}>Pretest</option>
                <option value="posttest" {{ request('tipe') == 'posttest' ? 'selected' : '' }}>Posttest</option>
            </select>
        </div>
    </form>

    <a href="{{ route('admin.soal.create', ['tipe' => 'pretest']) }}" class="btn btn-primary">+ Tambah Soal Pretest</a>
    <a href="{{ route('admin.soal.create', ['tipe' => 'posttest']) }}" class="btn btn-primary">+ Tambah Soal Posttest</a>

    <table>
        <tr>
            <th>Materi</th>
            <th>Tipe</th>
            <th>Pertanyaan</th>
            <th>Pilihan</th>
            <th>Jawaban Benar</th>
            <th>Aksi</th>
        </tr>
        @forelse($soals as $soal)
        <tr>
            <td>{{ $soal->materi->judul ?? '-' }}</td>
            <td>
                <span class="badge badge-{{ $soal->tipe }}">{{ ucfirst($soal->tipe) }}</span>
            </td>
            <td>{{ $soal->pertanyaan }}</td>
            <td>
                a. {{ $soal->pilihan_a }}<br>
                b. {{ $soal->pilihan_b }}<br>
                c. {{ $soal->pilihan_c }}<br>
                d. {{ $soal->pilihan_d }}
            </td>
            <td><strong>{{ strtoupper($soal->jawaban_benar) }}</strong></td>
            <td>
                <a href="{{ route('admin.soal.edit', $soal) }}" class="btn btn-secondary">Edit</a>
                <form class="inline" action="{{ route('admin.soal.destroy', $soal) }}" method="POST" onsubmit="return confirm('Yakin hapus soal ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="6">Belum ada soal.</td></tr>
        @endforelse
    </table>
@endsection
