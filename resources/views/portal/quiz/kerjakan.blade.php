@extends('layouts.app')
@section('title', 'Kerjakan Quiz')
@section('content')
<div class="max-w-3xl mx-auto py-4">
    <!-- Header Quiz -->
    <div class="bg-white rounded-lg border shadow-sm p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 border-b pb-4">
            <div>
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $quiz->tipe_test == 'pretest' ? 'bg-amber-100 text-amber-800' : 'bg-green-100 text-green-800' }}">
                    {{ ucfirst($quiz->tipe_test) }}
                </span>
                <h1 class="text-2xl font-bold text-gray-800 mt-2">{{ $quiz->materi->judul_materi ?? 'Quiz' }}</h1>
            </div>
            <div class="text-right">
                <div class="text-xs text-gray-500">Poin Per Soal</div>
                <div class="text-xl font-bold text-blue-600">{{ $quiz->poin }} Poin</div>
            </div>
        </div>

        <!-- Progress Bar & Per-Soal Timer Header -->
        <div class="mt-4 flex items-center justify-between">
            <div class="text-sm font-semibold text-gray-700">
                Soal <span id="current-question-num">1</span> dari {{ $quiz->soal->count() }}
            </div>

            <!-- Per-Soal Countdown Badge -->
            <div class="flex items-center gap-2 bg-amber-50 border border-amber-300 text-amber-900 px-4 py-1.5 rounded-full font-mono text-sm font-bold shadow-sm" id="timer-box">
                ⏱️ Sisa Waktu Soal: <span id="timer-countdown" class="text-base text-red-600">60</span>s
            </div>
        </div>
        <div class="w-full bg-gray-200 h-2 rounded-full mt-3 overflow-hidden">
            <div id="quiz-progress-bar" class="bg-blue-600 h-full transition-all duration-300" style="width: 0%;"></div>
        </div>
    </div>

    <!-- Form Pengerjaan Quiz -->
    @if ($quiz->soal->isEmpty())
        <div class="bg-white rounded-lg border p-8 text-center text-gray-500">
            Belum ada soal pada quiz ini.
        </div>
    @else
        <form action="{{ route('portal.quiz.submit', $quiz) }}" method="POST" id="quiz-form">
            @csrf
            @foreach ($quiz->soal as $index => $soal)
                <div class="soal-slide bg-white rounded-lg border shadow-sm p-6 mb-6 {{ $index > 0 ? 'hidden' : '' }}" data-index="{{ $index }}" data-timer="{{ $soal->timer_per_soal ?? 60 }}">
                    <div class="text-xs font-bold text-blue-600 uppercase mb-2">Pertanyaan #{{ $index + 1 }}</div>
                    <p class="text-base font-semibold text-gray-800 mb-6 leading-relaxed">{{ $soal->pertanyaan }}</p>

                    <div class="space-y-3 mb-6">
                        @foreach (['a', 'b', 'c', 'd'] as $opt)
                            <label class="flex items-center p-3.5 border rounded-lg hover:bg-blue-50 cursor-pointer transition duration-150 group">
                                <input type="radio" name="jawaban[{{ $soal->id_soal }}]" value="{{ $opt }}" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="ml-3 text-sm font-medium text-gray-700 group-hover:text-blue-900">
                                    <strong class="uppercase text-blue-600 mr-1">{{ $opt }}.</strong> {{ $soal->{'pilihan_' . $opt} }}
                                </span>
                            </label>
                        @endforeach
                    </div>

                    <div class="flex justify-between items-center pt-4 border-t">
                        @if ($index > 0)
                            <button type="button" onclick="prevSoal()" class="border border-gray-300 hover:bg-gray-100 text-gray-700 text-sm font-semibold px-4 py-2 rounded-md">
                                &larr; Sebelumnya
                            </button>
                        @else
                            <div></div>
                        @endif

                        @if ($index < $quiz->soal->count() - 1)
                            <button type="button" onclick="nextSoal()" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2 rounded-md">
                                Selanjutnya &rarr;
                            </button>
                        @else
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white text-sm font-bold px-6 py-2 rounded-md shadow">
                                🚀 Kumpulkan Quiz
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </form>
    @endif
</div>

<script>
let currentIdx = 0;
const slides = document.querySelectorAll('.soal-slide');
const totalSoal = slides.length;
let timerInterval = null;
let currentSeconds = 60;

function startTimer() {
    clearInterval(timerInterval);
    if (totalSoal === 0) return;

    const currentSlide = slides[currentIdx];
    currentSeconds = parseInt(currentSlide.getAttribute('data-timer')) || 60;
    
    updateTimerDisplay();

    timerInterval = setInterval(() => {
        currentSeconds--;
        updateTimerDisplay();

        if (currentSeconds <= 0) {
            clearInterval(timerInterval);
            // Auto advance when question timer reaches 0
            if (currentIdx < totalSoal - 1) {
                nextSoal();
            } else {
                document.getElementById('quiz-form').submit();
            }
        }
    }, 1000);
}

function updateTimerDisplay() {
    const countdownEl = document.getElementById('timer-countdown');
    if (countdownEl) {
        countdownEl.innerText = currentSeconds;
    }
}

function showSlide(index) {
    slides.forEach((s, idx) => {
        if (idx === index) {
            s.classList.remove('hidden');
        } else {
            s.classList.add('hidden');
        }
    });

    document.getElementById('current-question-num').innerText = index + 1;
    const progressPercent = ((index + 1) / totalSoal) * 100;
    document.getElementById('quiz-progress-bar').style.width = progressPercent + '%';

    currentIdx = index;
    startTimer();
}

function nextSoal() {
    if (currentIdx < totalSoal - 1) {
        showSlide(currentIdx + 1);
    }
}

function prevSoal() {
    if (currentIdx > 0) {
        showSlide(currentIdx - 1);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (totalSoal > 0) {
        showSlide(0);
    }
});
</script>
@endsection
