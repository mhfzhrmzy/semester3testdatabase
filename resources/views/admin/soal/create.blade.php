@extends('layouts.app')
@section('title', 'Tambah Soal')

@section('content')
    <h1>Tambah Soal ({{ ucfirst($tipe) }})</h1>
    <p style="font-size:13px; color:#555;">
        Klik <strong>"+ Tambah Soal"</strong> untuk menambah form soal baru (bisa sampai puluhan soal),
        lalu klik <strong>Simpan Semua Soal</strong> di bagian bawah untuk menyimpan sekaligus.
    </p>

    <form action="{{ route('admin.soal.store') }}" method="POST" id="form-soal">
        @csrf

        <div class="field">
            <label>Materi</label>
            <select name="materi_id" required>
                <option value="">-- Pilih Materi --</option>
                @foreach($materis as $m)
                    <option value="{{ $m->id }}">{{ $m->judul }}</option>
                @endforeach
            </select>
        </div>

        <div class="field">
            <label>Tipe Soal</label>
            <select name="tipe" required>
                <option value="pretest" {{ $tipe == 'pretest' ? 'selected' : '' }}>Pretest</option>
                <option value="posttest" {{ $tipe == 'posttest' ? 'selected' : '' }}>Posttest</option>
            </select>
        </div>

        <hr>

        <div id="soal-container">
            <!-- Soal pertama (template awal) akan dimasukkan lewat JS saat halaman load -->
        </div>

        <button type="button" class="btn btn-primary" onclick="tambahSoal()">+ Tambah Soal</button>

        <hr>
        <button type="submit" class="btn btn-success">Simpan Semua Soal</button>
        <a href="{{ route('admin.soal.index') }}" class="btn btn-secondary">Batal</a>
    </form>

    <script>
        let soalIndex = 0;

        function tambahSoal() {
            const container = document.getElementById('soal-container');
            const idx = soalIndex;

            const div = document.createElement('div');
            div.className = 'soal-card';
            div.id = 'soal-' + idx;
            div.innerHTML = `
                <button type="button" class="btn btn-danger btn-remove" onclick="hapusSoal(${idx})">Hapus</button>
                <h4>Soal #${idx + 1}</h4>

                <div class="field">
                    <label>Pertanyaan</label>
                    <textarea name="soals[${idx}][pertanyaan]" rows="2" required></textarea>
                </div>

                <div class="field">
                    <label>Pilihan A</label>
                    <input type="text" name="soals[${idx}][pilihan_a]" required>
                </div>
                <div class="field">
                    <label>Pilihan B</label>
                    <input type="text" name="soals[${idx}][pilihan_b]" required>
                </div>
                <div class="field">
                    <label>Pilihan C</label>
                    <input type="text" name="soals[${idx}][pilihan_c]" required>
                </div>
                <div class="field">
                    <label>Pilihan D</label>
                    <input type="text" name="soals[${idx}][pilihan_d]" required>
                </div>

                <div class="field">
                    <label>Jawaban Benar</label>
                    <select name="soals[${idx}][jawaban_benar]" required>
                        <option value="a">A</option>
                        <option value="b">B</option>
                        <option value="c">C</option>
                        <option value="d">D</option>
                    </select>
                </div>
            `;
            container.appendChild(div);
            soalIndex++;
            renumberSoal();
        }

        function hapusSoal(idx) {
            const el = document.getElementById('soal-' + idx);
            if (el) el.remove();
            renumberSoal();
        }

        function renumberSoal() {
            const cards = document.querySelectorAll('#soal-container .soal-card');
            cards.forEach((card, i) => {
                card.querySelector('h4').innerText = 'Soal #' + (i + 1);
            });
        }

        // otomatis munculkan 1 form soal saat halaman dibuka
        window.onload = function () {
            tambahSoal();
        };
    </script>
@endsection
