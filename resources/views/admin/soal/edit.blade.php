@extends('layouts.app')
@section('title', 'Edit Soal')
@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-xl mx-auto">
    <form action="{{ route('admin.soal.update', $soal) }}" method="POST" class="space-y-4">
        @csrf @method('PUT')
        <textarea name="pertanyaan" rows="2" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">{{ $soal->pertanyaan }}</textarea>
        <div class="grid grid-cols-2 gap-3">
            <input type="text" name="pilihan_a" value="{{ $soal->pilihan_a }}" class="rounded-md border border-gray-300 px-3 py-2 text-sm">
            <input type="text" name="pilihan_b" value="{{ $soal->pilihan_b }}" class="rounded-md border border-gray-300 px-3 py-2 text-sm">
            <input type="text" name="pilihan_c" value="{{ $soal->pilihan_c }}" class="rounded-md border border-gray-300 px-3 py-2 text-sm">
            <input type="text" name="pilihan_d" value="{{ $soal->pilihan_d }}" class="rounded-md border border-gray-300 px-3 py-2 text-sm">
        </div>
        <select name="jawaban_benar" class="rounded-md border border-gray-300 px-3 py-2 text-sm">
            @foreach(['a','b','c','d'] as $o)
                <option value="{{ $o }}" {{ $soal->jawaban_benar==$o?'selected':'' }}>{{ strtoupper($o) }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-md">Update</button>
    </form>
</div>
@endsection