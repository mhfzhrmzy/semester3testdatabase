@extends('layouts.app')
@section('title', 'Kelola Quiz')
@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex items-center justify-between border-b pb-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Kelola Quiz</h2>
            <p class="text-sm text-gray-500">Buat dan atur kuis Pre-Test &amp; Post-Test untuk materi pembelajaran</p>
        </div>
    </div>

    <!-- Form Buat Quiz -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-5 mb-8">
        <h3 class="text-md font-semibold text-blue-900 mb-3">➕ Buat Quiz Baru</h3>
        <form action="{{ route('admin.quiz.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-3">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Pilih Materi</label>
                <select name="id_materi" required class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Materi --</option>
                    @foreach ($materis as $m)
                        <option value="{{ $m->id_materi }}">{{ $m->judul_materi }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Tipe Test</label>
                <select name="tipe_test" required class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="pretest">Pre-Test</option>
                    <option value="posttest">Post-Test</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Poin / Soal</label>
                <input type="number" name="poin" value="10" min="1" required placeholder="Contoh: 10" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Timer Total (Menit)</label>
                <input type="number" name="timer" value="30" min="1" required placeholder="Contoh: 30" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal (Otomatis)</label>
                <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm font-semibold bg-white">
            </div>
            <button type="submit" class="md:col-span-5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2.5 rounded-md transition duration-150">
                Simpan &amp; Buat Quiz
            </button>
        </form>
    </div>

    <!-- Tabel Daftar Quiz -->
    <h3 class="text-md font-semibold text-gray-800 mb-3">Daftar Quiz Pembelajaran</h3>
    <table class="w-full text-sm text-left border rounded-lg overflow-hidden">
        <thead>
            <tr class="border-b bg-gray-100 text-gray-700">
                <th class="px-3 py-2.5">Materi</th>
                <th class="px-3 py-2.5">Tipe Test</th>
                <th class="px-3 py-2.5">Poin / Soal</th>
                <th class="px-3 py-2.5">Tanggal Dibuat</th>
                <th class="px-3 py-2.5">Jumlah Soal</th>
                <th class="px-3 py-2.5 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($quizzes as $quiz)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-3 py-2.5 font-medium">{{ $quiz->materi->judul_materi ?? '-' }}</td>
                    <td class="px-3 py-2.5">
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $quiz->tipe_test == 'pretest' ? 'bg-amber-100 text-amber-800' : 'bg-green-100 text-green-800' }}">
                            {{ ucfirst($quiz->tipe_test) }}
                        </span>
                    </td>
                    <td class="px-3 py-2.5 font-semibold text-blue-600">{{ $quiz->poin }} Poin</td>
                    <td class="px-3 py-2.5 text-gray-600">{{ \Carbon\Carbon::parse($quiz->tanggal)->format('d M Y') }}</td>
                    <td class="px-3 py-2.5 font-bold">{{ $quiz->soal->count() }} Soal</td>
                    <td class="px-3 py-2.5 text-right">
                        <a href="{{ route('admin.soal.index', $quiz) }}" class="bg-blue-50 text-blue-600 hover:bg-blue-100 font-semibold px-3 py-1 rounded border border-blue-300 text-xs mr-2">
                            📝 Kelola Soal ({{ $quiz->soal->count() }})
                        </a>
                        <form action="{{ route('admin.quiz.destroy', $quiz) }}" method="POST" class="inline" onsubmit="return confirm('Hapus quiz ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline font-semibold text-xs">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-3 py-6 text-center text-gray-400">Belum ada quiz yang dibuat.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection