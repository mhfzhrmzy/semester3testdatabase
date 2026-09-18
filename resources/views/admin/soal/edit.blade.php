@extends('layouts.app')

@section('title', 'Edit Soal')

@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-2xl mx-auto">
    <h2 class="text-lg font-semibold mb-4">Edit Soal</h2>

    <form action="{{ route('admin.soal.update', $soal) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium mb-1">Materi</label>
            <select name="id_materi" required class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                @foreach ($materis as $m)
                    <option value="{{ $m->id_materi }}" {{ $soal->id_materi == $m->id_materi ? 'selected' : '' }}>
                        {{ $m->judul_materi }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Tipe Soal</label>
            <select name="tipe" required class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                <option value="pretest" {{ $soal->tipe == 'pretest' ? 'selected' : '' }}>Pretest</option>
                <option value="posttest" {{ $soal->tipe == 'posttest' ? 'selected' : '' }}>Posttest</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Pertanyaan</label>
            <textarea name="pertanyaan" rows="2" required
                      class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">{{ $soal->pertanyaan }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-medium mb-1">Pilihan A</label>
                <input type="text" name="pilihan_a" value="{{ $soal->pilihan_a }}" required
                       class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Pilihan B</label>
                <input type="text" name="pilihan_b" value="{{ $soal->pilihan_b }}" required
                       class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Pilihan C</label>
                <input type="text" name="pilihan_c" value="{{ $soal->pilihan_c }}" required
                       class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Pilihan D</label>
                <input type="text" name="pilihan_d" value="{{ $soal->pilihan_d }}" required
                       class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Jawaban Benar</label>
            <select name="jawaban_benar" required class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                @foreach (['a', 'b', 'c', 'd'] as $opt)
                    <option value="{{ $opt }}" {{ $soal->jawaban_benar == $opt ? 'selected' : '' }}>
                        {{ strtoupper($opt) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-md">
                Update
            </button>
            <a href="{{ route('admin.soal.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium px-5 py-2 rounded-md">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection