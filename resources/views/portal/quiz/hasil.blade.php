@extends('layouts.app')
@section('title', 'Hasil Quiz')
@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-md mx-auto text-center">
    <h2 class="text-lg font-semibold mb-2">Hasil {{ ucfirst($quiz->tipe_test) }}</h2>
    <p class="text-4xl font-bold text-blue-600 mb-2">{{ $totalPoin }}</p>
    <p class="text-sm text-gray-600">{{ $benar }} benar dari {{ $total }} soal</p>
    <a href="{{ route('portal.materi.show', $quiz->id_materi) }}" class="inline-block mt-6 bg-gray-700 hover:bg-gray-800 text-white text-sm px-5 py-2 rounded-md">Kembali</a>
</div>
@endsection