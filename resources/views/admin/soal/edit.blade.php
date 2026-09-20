@extends('layouts.app')
@section('title', 'Edit Soal')
@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-xl mx-auto">
    <div class="flex items-center justify-between border-b pb-3 mb-4">
        <h2 class="text-lg font-bold text-gray-800">Edit Soal</h2>
        <a href="{{ route('admin.soal.index', $soal->id_quiz) }}" class="text-sm text-blue-600 hover:underline">&larr; Batal</a>
    </div>

    <form action="{{ route('admin.soal.update', $soal) }}" method="POST" class="space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">Pertanyaan</label>
            <textarea name="pertanyaan" rows="3" required class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ $soal->pertanyaan }}</textarea>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">Pilihan Jawaban</label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <input type="text" name="pilihan_a" value="{{ $soal->pilihan_a }}" required placeholder="Pilihan A" class="rounded-md border border-gray-300 px-3 py-2 text-sm">
                <input type="text" name="pilihan_b" value="{{ $soal->pilihan_b }}" required placeholder="Pilihan B" class="rounded-md border border-gray-300 px-3 py-2 text-sm">
                <input type="text" name="pilihan_c" value="{{ $soal->pilihan_c }}" required placeholder="Pilihan C" class="rounded-md border border-gray-300 px-3 py-2 text-sm">
                <input type="text" name="pilihan_d" value="{{ $soal->pilihan_d }}" required placeholder="Pilihan D" class="rounded-md border border-gray-300 px-3 py-2 text-sm">
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Jawaban Benar</label>
                <select name="jawaban_benar" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm font-bold text-blue-600">
                    @foreach(['a','b','c','d'] as $o)
                        <option value="{{ $o }}" {{ $soal->jawaban_benar == $o ? 'selected' : '' }}>Opsi {{ strtoupper($o) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Timer per Soal (Detik)</label>
                <input type="number" name="timer_per_soal" value="{{ $soal->timer_per_soal }}" min="5" required class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm font-semibold">
            </div>
        </div>
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2.5 rounded-md transition duration-150">Update Soal</button>
    </form>
</div>
@endsection