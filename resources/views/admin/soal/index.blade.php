@extends('layouts.app')
@section('title', 'Soal Quiz')
@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-lg font-semibold mb-4">Soal untuk: {{ $quiz->materi->judul_materi }} ({{ ucfirst($quiz->tipe_test) }})</h2>

    <form action="{{ route('admin.soal.store', $quiz) }}" method="POST" id="form-soal" class="space-y-4 mb-6">
        @csrf
        <div id="soal-container" class="space-y-4"></div>
        <button type="button" onclick="tambahSoal()" class="bg-gray-700 hover:bg-gray-800 text-white text-sm font-medium px-4 py-2 rounded-md">+ Tambah Soal</button>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-md">Simpan Semua Soal</button>
    </form>

    <table class="w-full text-sm text-left">
        <thead><tr class="border-b bg-gray-50"><th class="px-3 py-2">Pertanyaan</th><th class="px-3 py-2">Kunci</th><th class="px-3 py-2 text-right">Aksi</th></tr></thead>
        <tbody>
            @forelse ($soals as $soal)
                <tr class="border-b">
                    <td class="px-3 py-2">{{ $soal->pertanyaan }}</td>
                    <td class="px-3 py-2 uppercase font-semibold">{{ $soal->jawaban_benar }}</td>
                    <td class="px-3 py-2 text-right">
                        <a href="{{ route('admin.soal.edit', $soal) }}" class="text-blue-600 hover:underline mr-3">Edit</a>
                        <form action="{{ route('admin.soal.destroy', $soal) }}" method="POST" class="inline" onsubmit="return confirm('Hapus?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="3" class="px-3 py-6 text-center text-gray-400">Belum ada soal.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
let idx = 0;
function tambahSoal() {
    const c = document.getElementById('soal-container');
    const i = idx++;
    const div = document.createElement('div');
    div.className = 'border border-gray-200 rounded-md p-4 bg-gray-50';
    div.innerHTML = `
        <textarea name="soals[${i}][pertanyaan]" rows="2" placeholder="Pertanyaan" required class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm mb-2"></textarea>
        <div class="grid grid-cols-2 gap-2 mb-2">
            <input type="text" name="soals[${i}][pilihan_a]" placeholder="Pilihan A" required class="rounded-md border border-gray-300 px-3 py-2 text-sm">
            <input type="text" name="soals[${i}][pilihan_b]" placeholder="Pilihan B" required class="rounded-md border border-gray-300 px-3 py-2 text-sm">
            <input type="text" name="soals[${i}][pilihan_c]" placeholder="Pilihan C" required class="rounded-md border border-gray-300 px-3 py-2 text-sm">
            <input type="text" name="soals[${i}][pilihan_d]" placeholder="Pilihan D" required class="rounded-md border border-gray-300 px-3 py-2 text-sm">
        </div>
        <select name="soals[${i}][jawaban_benar]" class="rounded-md border border-gray-300 px-3 py-2 text-sm">
            <option value="a">A</option><option value="b">B</option><option value="c">C</option><option value="d">D</option>
        </select>`;
    c.appendChild(div);
}
window.addEventListener('DOMContentLoaded', tambahSoal);
</script>
@endsection