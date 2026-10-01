@extends('layouts.app')

@section('title', 'Profil')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Profil & Portofolio</h1>

    {{-- max-w-md + mx-auto = kartu berada di tengah --}}
    <div class="max-w-md mx-auto space-y-6">

        {{-- Kartu profil --}}
        <div class="border rounded-lg p-6 text-center">
            @if ($siswa->foto)
                <img src="{{ asset('storage/' . $siswa->foto) }}" alt="Foto profil"
                     class="w-24 h-24 mx-auto rounded-full object-cover">
            @else
                <div class="w-24 h-24 mx-auto rounded-full bg-gray-300 flex items-center justify-center font-bold text-xl">
                    {{ strtoupper(substr($siswa->nama_lengkap, 0, 3)) }}
                </div>
            @endif

            {{-- Tombol foto --}}
            <div class="mt-3 flex justify-center gap-2">
                <form action="{{ route('siswa.profil.foto') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label class="cursor-pointer text-xs bg-gray-900 text-white rounded-lg px-3 py-1 inline-block">
                        {{ $siswa->foto ? 'Ubah Foto' : 'Upload Foto' }}
                        <input type="file" name="foto" accept="image/png,image/jpeg" class="hidden"
                               onchange="this.form.submit()">
                    </label>
                </form>

                @if ($siswa->foto)
                    <form action="{{ route('siswa.profil.foto.hapus') }}" method="POST"
                          onsubmit="return confirm('Hapus foto profil?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs bg-red-600 text-white rounded-lg px-3 py-1">
                            Hapus Foto
                        </button>
                    </form>
                @endif
            </div>

            <h2 class="mt-3 text-xl font-bold">{{ $siswa->nama_lengkap }}</h2>
            <p class="text-sm text-gray-500">NISN: {{ $siswa->nisn }}</p>

            <div class="bg-blue-50 rounded-lg p-3 mt-4 text-left">
                <p class="text-xs text-gray-500">Total Poin</p>
                <p class="font-bold">{{ $siswa->poin }}</p>
            </div>

            <div class="flex justify-between text-sm mt-4">
                <span class="text-gray-500">Bergabung</span>
                <span>{{ $siswa->created_at->translatedFormat('F Y') }}</span>
            </div>
            <div class="flex justify-between text-sm mt-2">
                <span class="text-gray-500">Status Akun</span>
                <span class="bg-blue-100 text-xs rounded-full px-2 py-1">Siswa Aktif</span>
            </div>
        </div>

        {{-- Data pokok --}}
        <div class="border rounded-lg p-6 space-y-3">
            <h3 class="font-bold text-lg">Data Pokok Siswa</h3>

            @foreach ([
                'NISN'               => $siswa->nisn,
                'Nama Lengkap Siswa' => $siswa->nama_lengkap,
                'Kelas'              => $siswa->kelas ?? '-',
                'Jurusan'            => $siswa->jurusan ?? '-',
                'Password'           => '••••••••',
            ] as $label => $nilai)
                <div>
                    <p class="text-sm text-gray-600">{{ $label }}</p>
                    <div class="bg-blue-50 rounded-lg px-3 py-2 font-medium">{{ $nilai }}</div>
                </div>
            @endforeach

            {{-- Tombol buka form ubah password --}}
            <button type="button" onclick="document.getElementById('formPassword').classList.toggle('hidden')"
                    class="text-sm bg-gray-900 text-white rounded-lg px-4 py-2">
                Ubah Password
            </button>

            {{-- Form ubah password (tersembunyi sampai tombol diklik) --}}
            <form id="formPassword" action="{{ route('siswa.profil.password') }}" method="POST"
                  class="hidden border-t pt-4 space-y-3">
                @csrf
                @method('PUT')

                <div>
                    <label class="text-sm text-gray-600">Password Lama</label>
                    <div class="flex gap-2">
                        <input type="password" id="pwLama" name="password_lama" required
                               class="flex-1 border rounded-lg px-3 py-2">
                        <button type="button" onclick="togglePw('pwLama', this)" class="px-2 text-gray-700" aria-label="Tampilkan password">
                            <svg class="icon-tutup w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/>
                                <circle cx="12" cy="12" r="3"/>
                                <path d="M4 4l16 16"/>
                            </svg>
                            <svg class="icon-buka w-6 h-6 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="text-sm text-gray-600">Password Baru (min. 8 karakter)</label>
                    <div class="flex gap-2">
                        <input type="password" id="pwBaru" name="password_baru" required minlength="8"
                               class="flex-1 border rounded-lg px-3 py-2">
                            <button type="button" onclick="togglePw('pwBaru', this)" class="px-2 text-gray-700" aria-label="Tampilkan password">
                                <svg class="icon-tutup w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                    <path d="M4 4l16 16"/>
                                </svg>
                                <svg class="icon-buka w-6 h-6 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                    </div>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="text-sm bg-blue-600 text-white rounded-lg px-4 py-2">
                        Simpan Password
                    </button>
                    <button type="button" onclick="batalPassword()"
                    class="text-sm bg-gray-200 text-gray-800 rounded-lg px-4 py-2">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

    <script>
    function togglePw(id, tombol) {
        const input = document.getElementById(id);
        const tampil = input.type === 'password';

        input.type = tampil ? 'text' : 'password';

        tombol.querySelector('.icon-buka').classList.toggle('hidden', !tampil);
        tombol.querySelector('.icon-tutup').classList.toggle('hidden', tampil);
    }

    function batalPassword() {
        const form = document.getElementById('formPassword');

        form.reset();
        form.querySelectorAll('input[type="text"]')
            .forEach(i => i.type = 'password');
        form.querySelectorAll('.icon-buka')
            .forEach(i => i.classList.add('hidden'));
        form.querySelectorAll('.icon-tutup')
            .forEach(i => i.classList.remove('hidden'));

        form.classList.add('hidden');
    }
</script>
@endsection