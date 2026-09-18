@extends('layouts.app')

@section('title', 'Bank Soal')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <h2 class="text-lg font-semibold">Bank Soal (Pretest & Posttest)</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.soal.create', ['tipe' => 'pretest']) }}"
               class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-md">
                + Soal Pretest
            </a>
            <a href="{{ route('admin.soal.create', ['tipe' => 'posttest']) }}"
               class="bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium px-4 py-2 rounded-md">
                + Soal Posttest
            </a>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.soal.index') }}" class="flex flex-wrap gap-4 mb-6">
        <div>
            <label class="block text-xs font-medium mb-1">Filter Materi</label>
            <select name="id_materi" onchange="this.form.submit()"
                    class="rounded-md border border-gray-300 px-3 py-2 text-sm">
                <option value="">-- Semua Materi --</option>
                @foreach ($materis as $m)
                    <option value="{{ $m->id_materi }}" {{ request('id_materi') == $m->id_materi ? 'selected' : '' }}>
                        {{ $m->judul_materi }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium mb-1">Filter Tipe</label>
            <select name="tipe" onchange="this.form.submit()"
                    class="rounded-md border border-gray-300 px-3 py-2 text-sm">
                <option value="">-- Semua Tipe --</option>
                <option value="pretest" {{ request('tipe') == 'pretest' ? 'selected' : '' }}>Pretest</option>
                <option value="posttest" {{ request('tipe') == 'posttest' ? 'selected' : '' }}>Posttest</option>
            </select>
        </div>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="border-b bg-gray-50">
                    <th class="px-3 py-2">Materi</th>
                    <th class="px-3 py-2">Tipe</th>
                    <th class="px-3 py-2">Pertanyaan</th>
                    <th class="px-3 py-2">Pilihan</th>
                    <th class="px-3 py-2">Kunci</th>
                    <th class="px-3 py-2 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($soals as $soal)
                    <tr class="border-b align-top">
                        <td class="px-3 py-2">{{ $soal->materi->judul_materi ?? '-' }}</td>
                        <td class="px-3 py-2">
                            <span class="inline-block px-2 py-0.5 rounded-full text-xs
                                {{ $soal->tipe === 'pretest' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ ucfirst($soal->tipe) }}
                            </span>
                        </td>
                        <td class="px-3 py-2">{{ $soal->pertanyaan }}</td>
                        <td class="px-3 py-2 text-xs text-gray-600">
                            a. {{ $soal->pilihan_a }}<br>
                            b. {{ $soal->pilihan_b }}<br>
                            c. {{ $soal->pilihan_c }}<br>
                            d. {{ $soal->pilihan_d }}
                        </td>
                        <td class="px-3 py-2 font-semibold uppercase">{{ $soal->jawaban_benar }}</td>
                        <td class="px-3 py-2 text-right whitespace-nowrap">
                            <a href="{{ route('admin.soal.edit', $soal) }}" class="text-blue-600 hover:underline mr-3">Edit</a>
                            <form action="{{ route('admin.soal.destroy', $soal) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Hapus soal ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-3 py-6 text-center text-gray-400">Belum ada soal.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection