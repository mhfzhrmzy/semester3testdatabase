@extends('layouts.app')
@section('title', 'Kelola Quiz')
@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-lg font-semibold mb-4">Buat Quiz</h2>
    <form action="{{ route('admin.quiz.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-3 mb-6">
        @csrf
        <select name="id_materi" class="rounded-md border border-gray-300 px-3 py-2 text-sm">
            <option value="">-- Materi --</option>
            @foreach ($materis as $m)
                <option value="{{ $m->id_materi }}">{{ $m->judul_materi }}</option>
            @endforeach
        </select>
        <select name="tipe_test" class="rounded-md border border-gray-300 px-3 py-2 text-sm">
            <option value="pretest">Pretest</option>
            <option value="posttest">Posttest</option>
        </select>
        <input type="number" name="poin" placeholder="Poin/soal" class="rounded-md border border-gray-300 px-3 py-2 text-sm">
        <input type="number" name="timer" placeholder="Timer (menit)" class="rounded-md border border-gray-300 px-3 py-2 text-sm">
        <input type="date" name="tanggal" class="rounded-md border border-gray-300 px-3 py-2 text-sm">
        <button type="submit" class="md:col-span-5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 rounded-md">Buat Quiz</button>
    </form>

    <table class="w-full text-sm text-left">
        <thead><tr class="border-b bg-gray-50"><th class="px-3 py-2">Materi</th><th class="px-3 py-2">Tipe</th><th class="px-3 py-2">Jml Soal</th><th class="px-3 py-2 text-right">Aksi</th></tr></thead>
        <tbody>
            @forelse ($quizzes as $quiz)
                <tr class="border-b">
                    <td class="px-3 py-2">{{ $quiz->materi->judul_materi ?? '-' }}</td>
                    <td class="px-3 py-2">{{ ucfirst($quiz->tipe_test) }}</td>
                    <td class="px-3 py-2">{{ $quiz->soal->count() }}</td>
                    <td class="px-3 py-2 text-right">
                        <a href="{{ route('admin.soal.index', $quiz) }}" class="text-blue-600 hover:underline mr-3">Kelola Soal</a>
                        <form action="{{ route('admin.quiz.destroy', $quiz) }}" method="POST" class="inline" onsubmit="return confirm('Hapus?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-3 py-6 text-center text-gray-400">Belum ada quiz.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection