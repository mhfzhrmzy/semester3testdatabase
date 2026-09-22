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
            <div class="flex gap-5 text-sm flex-wrap items-center">
                @if (auth('admin')->check())
                    @php $currentAdmin = auth('admin')->user(); @endphp

                    <a href="{{ route('materi.index') }}"
                       class="hover:text-gray-300 {{ request()->routeIs('materi.*') ? 'text-blue-400 font-semibold' : '' }}">
                        Materi
                    </a>
                    <a href="{{ route('admin.quiz.index') }}"
                       class="hover:text-gray-300 {{ request()->routeIs('admin.quiz.*') || request()->routeIs('admin.soal.*') ? 'text-blue-400 font-semibold' : '' }}">
                        Quiz &amp; Soal
                    </a>
                    <a href="{{ route('sertifikat.index') }}"
                       class="hover:text-gray-300 {{ request()->routeIs('sertifikat.*') ? 'text-blue-400 font-semibold' : '' }}">
                        Sertifikat
                    </a>
                    <a href="{{ route('leaderboard.index') }}"
                       class="hover:text-gray-300 {{ request()->routeIs('leaderboard.*') ? 'text-amber-400 font-semibold' : '' }}">
                        Leaderboard
                    </a>

                    @if ($currentAdmin->role === 'superadmin')
                        <a href="{{ route('admin.index') }}"
                           class="hover:text-gray-300 {{ request()->routeIs('admin.index') ? 'text-blue-400 font-semibold' : '' }}">
                            Akun Guru
                        </a>
                        <a href="{{ route('siswa.index') }}"
                           class="hover:text-gray-300 {{ request()->routeIs('siswa.index') ? 'text-blue-400 font-semibold' : '' }}">
                            Akun Siswa
                        </a>
                        <a href="{{ route('superadmin.index') }}"
                           class="hover:text-gray-300 {{ request()->routeIs('superadmin.*') ? 'text-blue-400 font-semibold' : '' }}">
                            Superadmin
                        </a>
                    @endif

                    <span class="text-gray-500">|</span>
                    <span class="text-gray-300">{{ $currentAdmin->nama_lengkap }}</span>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="hover:text-red-400">Logout</button>
                    </form>
                @elseif (auth('siswa')->check())
                    <a href="{{ route('portal.materi.index') }}"
                       class="hover:text-gray-300 {{ request()->routeIs('portal.*') ? 'text-blue-400 font-semibold' : '' }}">
                        Portal Siswa
                    </a>
                    <a href="{{ route('leaderboard.index') }}"
                       class="hover:text-gray-300 {{ request()->routeIs('leaderboard.*') ? 'text-amber-400 font-semibold' : '' }}">
                        Leaderboard
                    </a>
                    <a href="{{ route('siswa.sertifikat.index') }}"
                       class="hover:text-gray-300 {{ request()->routeIs('siswa.sertifikat.*') ? 'text-blue-400 font-semibold' : '' }}">
                        Sertifikat
                    </a>
                    <span class="text-gray-500">|</span>
                    <span class="text-gray-300">{{ auth('siswa')->user()->nama_lengkap }}</span>
                    <form action="{{ route('siswa.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="hover:text-red-400">Logout</button>
                    </form>
                @else
                    <a href="{{ route('admin.login') }}" class="hover:text-gray-300 {{ request()->routeIs('admin.login') ? 'text-blue-400 font-semibold' : '' }}">Login Guru</a>
                    <a href="{{ route('admin.register') }}" class="hover:text-gray-300 {{ request()->routeIs('admin.register') ? 'text-blue-400 font-semibold' : '' }}">Registrasi Guru</a>
                    <span class="text-gray-600">|</span>
                    <a href="{{ route('siswa.login') }}" class="hover:text-gray-300 {{ request()->routeIs('siswa.login') ? 'text-amber-400 font-semibold' : '' }}">Login Siswa</a>
                    <a href="{{ route('siswa.register') }}" class="hover:text-gray-300 {{ request()->routeIs('siswa.register') ? 'text-amber-400 font-semibold' : '' }}">Registrasi Siswa</a>
                @endif
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

        <div class="bg-white rounded-lg shadow p-6">
            @yield('content')
        </div>
    </main>
</body>
</html>