<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') — Sistem Pembelajaran</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen text-gray-800">
    <nav class="bg-gray-900 text-white px-6 py-4 shadow">
        <div class="max-w-6xl mx-auto flex items-center justify-between flex-wrap gap-3">
            <span class="font-semibold text-lg">Sistem Pembelajaran</span>
            <div class="flex gap-5 text-sm flex-wrap">
                <a href="{{ route('admin.index') }}"
                   class="hover:text-gray-300 {{ request()->routeIs('admin.index') ? 'text-blue-400 font-semibold' : '' }}">
                    Admin (Guru)
                </a>
                <a href="{{ route('siswa.index') }}"
                   class="hover:text-gray-300 {{ request()->routeIs('siswa.*') && !request()->routeIs('portal.*') ? 'text-blue-400 font-semibold' : '' }}">
                    Siswa
                </a>
                <a href="{{ route('materi.index') }}"
                   class="hover:text-gray-300 {{ request()->routeIs('materi.*') ? 'text-blue-400 font-semibold' : '' }}">
                    Materi
                </a>
                <a href="{{ route('admin.soal.index') }}"
                   class="hover:text-gray-300 {{ request()->routeIs('admin.soal.*') ? 'text-blue-400 font-semibold' : '' }}">
                    Bank Soal
                </a>
                <a href="{{ route('portal.materi.index') }}"
                   class="hover:text-gray-300 {{ request()->routeIs('portal.*') ? 'text-blue-400 font-semibold' : '' }}">
                    Portal Siswa
                </a>
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-6 py-8">
    @if (session('success'))
        <div class="mb-6 rounded-md bg-green-100 border border-green-300 text-green-800 px-4 py-3 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-md bg-red-100 border border-red-300 text-red-800 px-4 py-3 text-sm">
            <p class="font-semibold mb-1">Terjadi kesalahan pada input:</p>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Card Utama Pembungkus Konten -->
    <div class="bg-white rounded-lg shadow p-6">
        @yield('content')
    </div>
</main>
</body>
</html>