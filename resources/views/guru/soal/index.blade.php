@extends('layouts.app')
@section('title', 'Soal Quiz')
@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex items-center justify-between border-b pb-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Kelola Soal Quiz</h2>
            <p class="text-sm text-gray-500">Materi: <strong>{{ $quiz->materi->judul_materi }}</strong> ({{ ucfirst($quiz->tipe_test) }})</p>
        </div>
        <a href="{{ route('admin.quiz.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali ke Daftar Quiz</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
            {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
            <p class="font-semibold mb-1">Terjadi kesalahan validasi:</p>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Section Import Spreadsheet / CSV -->
    @if($soals->isEmpty())
        {{-- Import CSV hanya ditampilkan jika belum ada soal --}}
        <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-5 mb-8">
            <h3 class="text-md font-semibold text-emerald-900 mb-2 flex items-center gap-2">
                📊 Import Soal dari Spreadsheet / CSV
                <span class="text-xs bg-emerald-200 text-emerald-800 font-semibold px-2 py-0.5 rounded-full">Hanya sekali</span>
            </h3>
            <p class="text-xs text-emerald-700 mb-4">
                Upload file CSV dari Excel/Google Sheets untuk menambahkan banyak soal secara instan. 
                <strong>Fitur ini hanya tersedia saat quiz belum memiliki soal.</strong>
                Setelah import, tambahkan soal baru secara manual.
            </p>

            <form action="{{ route('admin.soal.import', $quiz) }}" method="POST" enctype="multipart/form-data" class="flex flex-wrap items-center gap-3">
                @csrf
                <input type="file" name="csv_file" accept=".csv" required class="text-xs text-gray-600 file:mr-3 file:py-2 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700">
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-4 py-2 rounded-md transition duration-150">
                    Upload &amp; Import CSV
                </button>
                <a href="{{ route('admin.soal.template') }}" class="inline-flex items-center gap-1 border border-emerald-600 text-emerald-700 hover:bg-emerald-100 text-xs font-semibold px-3 py-2 rounded-md transition duration-150">
                    ⬇️ Unduh Template CSV
                </a>
            </form>
        </div>
    @else
        {{-- Tampilkan info bahwa import CSV sudah tidak bisa lagi --}}
        <div class="bg-gray-100 border border-gray-300 rounded-lg p-4 mb-8 flex items-start gap-3">
            <span class="text-xl">🔒</span>
            <div>
                <p class="text-sm font-semibold text-gray-700">Import CSV tidak tersedia</p>
                <p class="text-xs text-gray-500 mt-0.5">
                    Quiz ini sudah memiliki <strong>{{ $soals->count() }} soal</strong>. Import CSV hanya bisa dilakukan sekali saat quiz masih kosong. 
                    Tambahkan soal baru secara manual menggunakan form di bawah.
                </p>
            </div>
        </div>
    @endif

    <!-- Form Manual Tambah Soal -->
    <h3 class="text-md font-semibold text-gray-800 mb-3">Tambah Soal Secara Manual</h3>

    {{-- Daftar pertanyaan yang sudah ada (untuk validasi duplikat di JS) --}}
    <script>
        const existingPertanyaans = @json($soals->pluck('pertanyaan')->map(fn($p) => strtolower(trim($p)))->values());
    </script>

    <form action="{{ route('admin.soal.store', $quiz) }}" method="POST" id="form-soal" class="space-y-4 mb-8">
        @csrf
        <div id="soal-container" class="space-y-4"></div>

        {{-- Pesan duplikat --}}
        <div id="duplikat-warning" class="hidden bg-amber-50 border border-amber-300 text-amber-800 px-4 py-3 rounded text-sm"></div>

        <div class="flex gap-3">
            <button type="button" onclick="tambahSoal()" class="bg-gray-700 hover:bg-gray-800 text-white text-sm font-medium px-4 py-2 rounded-md">+ Tambah Form Soal</button>
            <button type="submit" id="btn-simpan" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-md">Simpan Semua Soal Manual</button>
        </div>
    </form>

    <!-- Daftar Soal -->
    <h3 class="text-md font-semibold text-gray-800 mb-3">Daftar Soal yang Sudah Ada ({{ $soals->count() }})</h3>
    <table class="w-full text-sm text-left border rounded-lg">
        <thead>
            <tr class="border-b bg-gray-100 text-gray-700">
                <th class="px-3 py-2">No</th>
                <th class="px-3 py-2">Pertanyaan</th>
                <th class="px-3 py-2">Pilihan Jawaban</th>
                <th class="px-3 py-2">Kunci</th>
                <th class="px-3 py-2">Timer per Soal</th>
                <th class="px-3 py-2 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($soals as $index => $soal)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-3 py-2 font-semibold text-gray-500">{{ $index + 1 }}</td>
                    <td class="px-3 py-2 font-medium">{{ $soal->pertanyaan }}</td>
                    <td class="px-3 py-2 text-xs space-y-1">
                        <div><strong class="{{ $soal->jawaban_benar == 'a' ? 'text-green-600' : '' }}">A:</strong> {{ $soal->pilihan_a }}</div>
                        <div><strong class="{{ $soal->jawaban_benar == 'b' ? 'text-green-600' : '' }}">B:</strong> {{ $soal->pilihan_b }}</div>
                        <div><strong class="{{ $soal->jawaban_benar == 'c' ? 'text-green-600' : '' }}">C:</strong> {{ $soal->pilihan_c }}</div>
                        <div><strong class="{{ $soal->jawaban_benar == 'd' ? 'text-green-600' : '' }}">D:</strong> {{ $soal->pilihan_d }}</div>
                    </td>
                    <td class="px-3 py-2 uppercase font-bold text-blue-600">{{ $soal->jawaban_benar }}</td>
                    <td class="px-3 py-2 font-semibold text-amber-600">{{ $soal->timer_per_soal }} Detik</td>
                    <td class="px-3 py-2 text-right">
                        <a href="{{ route('admin.soal.edit', $soal) }}" class="text-blue-600 hover:underline mr-3 font-semibold">Edit</a>
                        <form action="{{ route('admin.soal.destroy', $soal) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus soal ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline font-semibold">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-3 py-6 text-center text-gray-400">Belum ada soal untuk quiz ini. Silakan buat secara manual atau import CSV.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
let idx = 0;

// Daftar pertanyaan dalam form saat ini (cek duplikat antar form baru)
let currentFormPertanyaans = [];

function getNormalizedValue(value) {
    return value.trim().toLowerCase();
}

function checkDuplicates() {
    const textareas = document.querySelectorAll('#soal-container textarea[data-pertanyaan]');
    const dupWarn = document.getElementById('duplikat-warning');
    const seenInForm = [];
    const duplicateLabels = [];

    textareas.forEach(function (ta) {
        const val = getNormalizedValue(ta.value);
        if (!val) return;

        const formNo = ta.dataset.formNo;

        // Cek duplikat dengan soal yang sudah ada di database
        if (existingPertanyaans.includes(val)) {
            ta.classList.add('border-red-400', 'bg-red-50');
            ta.classList.remove('border-gray-300');
            duplicateLabels.push('Form Soal #' + formNo + ': sama dengan soal yang sudah ada');
        // Cek duplikat antar form baru
        } else if (seenInForm.includes(val)) {
            ta.classList.add('border-amber-400', 'bg-amber-50');
            ta.classList.remove('border-gray-300', 'border-red-400');
            duplicateLabels.push('Form Soal #' + formNo + ': sama dengan form soal lain di atas');
        } else {
            ta.classList.remove('border-red-400', 'bg-red-50', 'border-amber-400', 'bg-amber-50');
            ta.classList.add('border-gray-300');
            seenInForm.push(val);
        }
    });

    if (duplicateLabels.length > 0) {
        dupWarn.innerHTML = '⚠️ <strong>Pertanyaan duplikat terdeteksi:</strong><ul class="list-disc list-inside mt-1">' +
            duplicateLabels.map(d => '<li>' + d + '</li>').join('') + '</ul>';
        dupWarn.classList.remove('hidden');
    } else {
        dupWarn.classList.add('hidden');
    }
}

function tambahSoal() {
    const c = document.getElementById('soal-container');
    const i = idx++;
    const formNo = i + 1;
    const div = document.createElement('div');
    div.className = 'border border-gray-300 rounded-md p-4 bg-gray-50 space-y-3 relative';
    div.id = 'soal-form-' + i;
    div.innerHTML = `
        <div class="flex justify-between items-center mb-1">
            <span class="font-bold text-xs text-gray-600 uppercase">Form Soal #${formNo}</span>
            <button type="button" onclick="hapusForm(${i})" class="text-xs text-red-500 hover:text-red-700 font-semibold border border-red-300 rounded px-2 py-0.5 hover:bg-red-50">✕ Hapus Form</button>
        </div>
        <textarea name="soals[${i}][pertanyaan]" rows="2" placeholder="Tuliskan teks pertanyaan di sini..." required
            data-pertanyaan="true" data-form-no="${formNo}"
            oninput="checkDuplicates()"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
        <div id="dup-hint-${i}" class="hidden text-xs text-red-600 font-medium"></div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
            <input type="text" name="soals[${i}][pilihan_a]" placeholder="Opsi A" required class="rounded-md border border-gray-300 px-3 py-2 text-sm">
            <input type="text" name="soals[${i}][pilihan_b]" placeholder="Opsi B" required class="rounded-md border border-gray-300 px-3 py-2 text-sm">
            <input type="text" name="soals[${i}][pilihan_c]" placeholder="Opsi C" required class="rounded-md border border-gray-300 px-3 py-2 text-sm">
            <input type="text" name="soals[${i}][pilihan_d]" placeholder="Opsi D" required class="rounded-md border border-gray-300 px-3 py-2 text-sm">
        </div>
        <div class="flex items-center gap-4 pt-1">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Jawaban Benar</label>
                <select name="soals[${i}][jawaban_benar]" class="rounded-md border border-gray-300 px-3 py-1.5 text-sm font-bold text-blue-600">
                    <option value="a">Opsi A</option>
                    <option value="b">Opsi B</option>
                    <option value="c">Opsi C</option>
                    <option value="d">Opsi D</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Timer per Soal (Detik, Maks. 300)</label>
                <input type="number" name="soals[${i}][timer_per_soal]" value="60" min="5" max="300" required class="w-36 rounded-md border border-gray-300 px-3 py-1.5 text-sm font-semibold">
                <p class="text-[10px] text-gray-400 mt-0.5">Maks. 300 detik (5 menit)</p>
            </div>
        </div>`;
    c.appendChild(div);
}

function hapusForm(i) {
    const el = document.getElementById('soal-form-' + i);
    if (el) {
        el.remove();
        checkDuplicates();
    }
}

window.addEventListener('DOMContentLoaded', tambahSoal);
</script>
@endsection