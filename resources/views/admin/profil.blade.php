@extends('layouts.app')

@section('title', 'Profil')

@section('content')
<h1 class="text-2xl font-bold mb-6">Profil &amp; Portofolio</h1>

<div class="max-w-md mx-auto space-y-6">

    <div class="border rounded-lg p-5 text-center">
        @if($admin->foto_profile)
            <img src="{{ asset('storage/'.$admin->foto_profile) }}" class="w-20 h-20 rounded-full object-cover mx-auto" alt="Foto">
        @else
            <div class="w-20 h-20 rounded-full bg-gray-300 mx-auto flex items-center justify-center text-xl font-bold">
                {{ strtoupper(substr($admin->nama_lengkap, 0, 3)) }}
            </div>
        @endif

                <div class="mt-2 flex items-center justify-center gap-2">
            {{-- Ubah / Upload Foto --}}
            <form action="{{ route('admin.profil.foto') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="foto" id="foto" accept="image/*" class="hidden" onchange="this.form.submit()">
                <label for="foto" class="cursor-pointer inline-block bg-gray-900 text-white text-[10px] px-3 py-1 rounded-full">
                    {{ $admin->foto_profile ? 'Ubah Foto' : 'Upload Foto' }}
                </label>
            </form>

            {{-- Hapus Foto (hanya tampil jika sudah ada foto) --}}
            @if($admin->foto_profile)
                <form action="{{ route('admin.profil.foto.hapus') }}" method="POST"
                      onsubmit="return confirm('Yakin ingin menghapus foto profil?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white text-[10px] px-3 py-1 rounded-full">
                        Hapus Foto
                    </button>
                </form>
            @endif
        </div>

        <h2 class="font-bold text-lg mt-2">{{ $admin->nama_lengkap }}</h2>
        <p class="text-xs text-gray-500">NIP: {{ $admin->nip }}</p>

        <div class="mt-4 space-y-2 text-xs text-gray-500">
            <div class="flex justify-between">
                <span>Bergabung</span>
                <span class="text-gray-800">{{ $admin->created_at?->translatedFormat('F Y') ?? '-' }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span>Status Akun</span>
                <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">Guru Aktif</span>
            </div>
        </div>
    </div>

    <div class="border rounded-lg p-5">
        <h3 class="font-bold mb-3">Informasi Pribadi Guru</h3>

        <label class="block text-xs text-gray-600 mt-3">NIP</label>
        <div class="bg-blue-50 rounded px-3 py-2 text-sm">{{ $admin->nip }}</div>

        <label class="block text-xs text-gray-600 mt-3">Nama Lengkap Guru</label>
        <div class="bg-blue-50 rounded px-3 py-2 text-sm">{{ $admin->nama_lengkap }}</div>

        <label class="block text-xs text-gray-600 mt-3">Password</label>
        <div class="bg-blue-50 rounded px-3 py-2 text-sm">••••••••</div>

        <button type="button" onclick="document.getElementById('form-password').classList.toggle('hidden')"
                class="mt-3 bg-gray-900 text-white text-xs px-3 py-1.5 rounded">
            Ubah Password
        </button>

        <form id="form-password" action="{{ route('admin.profil.password') }}" method="POST"
              class="{{ $errors->has('password_lama') || $errors->has('password_baru') ? '' : 'hidden' }} mt-3 border-t border-gray-900 pt-3">
            @csrf

            <label class="block text-xs text-gray-600">Password Lama</label>
            <div class="flex items-center gap-2">
                <input type="password" name="password_lama" class="w-full border rounded px-3 py-2 text-sm">
                <button type="button" onclick="togglePw(this)" class="text-gray-800">
    {{-- Mata dicoret (default, password tersembunyi) --}}
    <svg class="icon-hide w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
    </svg>
    {{-- Mata biasa (password terlihat) --}}
    <svg class="icon-show w-5 h-5 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
    </svg>
</button>
            </div>

            <label class="block text-xs text-gray-600 mt-3">Password Baru (min. 8 karakter)</label>
            <div class="flex items-center gap-2">
                <input type="password" name="password_baru" class="w-full border rounded px-3 py-2 text-sm">
                <button type="button" onclick="togglePw(this)" class="text-gray-800">
    {{-- Mata dicoret (default, password tersembunyi) --}}
    <svg class="icon-hide w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
    </svg>
    {{-- Mata biasa (password terlihat) --}}
    <svg class="icon-show w-5 h-5 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
    </svg>
</button>
            </div>

            <div class="flex gap-2 mt-3">
                <button class="bg-blue-600 text-white text-xs px-3 py-1.5 rounded">Simpan Password</button>
                <button type="button" onclick="document.getElementById('form-password').classList.add('hidden')"
                        class="bg-gray-200 text-xs px-3 py-1.5 rounded">Batal</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function togglePw(btn) {
    const input = btn.parentElement.querySelector('input');
    const isHidden = input.type === 'password';

    input.type = isHidden ? 'text' : 'password';
    btn.querySelector('.icon-hide').classList.toggle('hidden', isHidden);
    btn.querySelector('.icon-show').classList.toggle('hidden', !isHidden);
}
</script>
@endpush
@endsection