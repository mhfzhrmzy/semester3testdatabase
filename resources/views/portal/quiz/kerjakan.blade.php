@extends('layouts.app')
@section('title', 'Kerjakan Quiz — ' . ($quiz->materi->judul_materi ?? 'Quiz'))
@section('content')
<div class="max-w-2xl mx-auto py-2">

    {{-- Header Quiz --}}
    <div class="rounded-2xl border border-gray-200 shadow-sm bg-white p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100 pb-5">
            <div>
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $quiz->tipe_test === 'pretest' ? 'bg-amber-100 text-amber-800' : 'bg-green-100 text-green-800' }}">
                    {{ ucfirst($quiz->tipe_test) }}
                </span>
                <h1 class="text-xl font-bold text-gray-800 mt-2">{{ $quiz->materi->judul_materi ?? 'Quiz' }}</h1>
            </div>
            <div class="text-right shrink-0">
                <div class="text-xs text-gray-400 mb-0.5">Poin per soal benar</div>
                <div class="text-2xl font-black text-blue-600">{{ $quiz->poin }} <span class="text-sm font-semibold text-gray-400">Poin</span></div>
            </div>
        </div>

        {{-- Progress & Timer --}}
        <div class="mt-5 flex items-center justify-between gap-4">
            <div class="text-sm font-semibold text-gray-600">
                Soal <span id="current-question-num" class="text-blue-600 font-bold">1</span>
                <span class="text-gray-400">/ {{ $quiz->soal->count() }}</span>
            </div>

            {{-- Timer Badge --}}
            <div id="timer-box"
                 class="flex items-center gap-2 bg-red-50 border border-red-200 text-red-700 px-4 py-2 rounded-full font-mono text-sm font-bold shadow-sm transition-colors duration-500">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                </svg>
                <span id="timer-countdown">60</span>
                <span class="text-xs font-semibold opacity-70">detik</span>
            </div>
        </div>

        {{-- Progress Bar --}}
        <div class="w-full bg-gray-100 h-2.5 rounded-full mt-3 overflow-hidden">
            <div id="quiz-progress-bar"
                 class="bg-gradient-to-r from-blue-500 to-indigo-600 h-full rounded-full transition-all duration-500 ease-out"
                 style="width: 0%;"></div>
        </div>
    </div>

    {{-- Form / Soal --}}
    @if ($quiz->soal->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-200 p-10 text-center">
            <div class="text-4xl mb-3">📭</div>
            <p class="text-gray-500 font-medium">Belum ada soal pada quiz ini.</p>
            <a href="{{ route('portal.quiz.index', $quiz->materi) }}"
               class="inline-block mt-4 text-sm text-blue-600 hover:underline">← Kembali ke daftar quiz</a>
        </div>
    @else
        <form action="{{ route('portal.quiz.submit', $quiz) }}" method="POST" id="quiz-form">
            @csrf

            @foreach ($quiz->soal as $index => $soal)
                <div class="soal-slide bg-white rounded-2xl border border-gray-200 shadow-sm p-6 mb-4 {{ $index > 0 ? 'hidden' : '' }}"
                     data-index="{{ $index }}"
                     data-timer="{{ $soal->timer_per_soal ?? 60 }}">

                    {{-- Question number + text --}}
                    <div class="mb-5">
                        <span class="text-xs font-bold text-blue-500 uppercase tracking-widest">Pertanyaan {{ $index + 1 }}</span>
                        <p class="text-base font-semibold text-gray-800 mt-2 leading-relaxed">
                            {{ $soal->pertanyaan }}
                        </p>
                    </div>

                    {{-- Answer choices --}}
                    <div class="space-y-3 mb-6" id="choices-{{ $index }}">
                        @foreach (['a', 'b', 'c', 'd'] as $opt)
                            <label class="answer-label flex items-start gap-3 p-4 border-2 border-gray-200 rounded-xl cursor-pointer
                                          hover:border-blue-400 hover:bg-blue-50 transition-all duration-150 group"
                                   id="label-{{ $index }}-{{ $opt }}">
                                <input type="radio"
                                       name="jawaban[{{ $soal->id_soal }}]"
                                       value="{{ $opt }}"
                                       class="hidden answer-radio"
                                       id="radio-{{ $index }}-{{ $opt }}">
                                <span class="flex-shrink-0 w-7 h-7 rounded-full border-2 border-gray-300 flex items-center justify-center
                                             text-xs font-bold text-gray-500 group-hover:border-blue-500 group-hover:text-blue-600
                                             transition-all duration-150 choice-circle"
                                      id="circle-{{ $index }}-{{ $opt }}">
                                    {{ strtoupper($opt) }}
                                </span>
                                <span class="text-sm text-gray-700 group-hover:text-gray-900 pt-0.5 leading-relaxed">
                                    {{ $soal->{'pilihan_' . $opt} }}
                                </span>
                            </label>
                        @endforeach
                    </div>

                    {{-- Navigation buttons --}}
                    <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                        @if ($index > 0)
                            <button type="button" onclick="prevSoal()"
                                    class="flex items-center gap-1.5 border border-gray-300 hover:bg-gray-100 text-gray-600 text-sm font-semibold px-4 py-2 rounded-lg transition">
                                ← Sebelumnya
                            </button>
                        @else
                            <div></div>
                        @endif

                        @if ($index < $quiz->soal->count() - 1)
                            <button type="button" onclick="nextSoal()"
                                    class="flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2 rounded-lg shadow transition">
                                Selanjutnya →
                            </button>
                        @else
                            <button type="submit"
                                    class="flex items-center gap-1.5 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white text-sm font-bold px-6 py-2.5 rounded-lg shadow-md transition">
                                🚀 Kumpulkan Jawaban
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

// Style answer labels with selected state
document.querySelectorAll('.answer-label').forEach(label => {
    label.addEventListener('click', () => {
        const name = label.querySelector('input').name;
        // Reset all in this question
        document.querySelectorAll(`input[name="${name}"]`).forEach(inp => {
            const lbl = inp.closest('label');
            const circle = lbl.querySelector('.choice-circle');
            lbl.classList.remove('border-blue-500', 'bg-blue-50', 'ring-2', 'ring-blue-200');
            lbl.classList.add('border-gray-200');
            circle.classList.remove('bg-blue-500', 'text-white', 'border-blue-500');
            circle.classList.add('border-gray-300', 'text-gray-500');
        });
        // Style selected
        const circle = label.querySelector('.choice-circle');
        label.classList.add('border-blue-500', 'bg-blue-50', 'ring-2', 'ring-blue-200');
        label.classList.remove('border-gray-200');
        circle.classList.add('bg-blue-500', 'text-white', 'border-blue-500');
        circle.classList.remove('border-gray-300', 'text-gray-500');
    });
});

function startTimer() {
    clearInterval(timerInterval);
    if (totalSoal === 0) { return; }

    const currentSlide = slides[currentIdx];
    currentSeconds = parseInt(currentSlide.getAttribute('data-timer')) || 60;
    updateTimerDisplay();

    timerInterval = setInterval(() => {
        currentSeconds--;
        updateTimerDisplay();

        if (currentSeconds <= 0) {
            clearInterval(timerInterval);
            if (currentIdx < totalSoal - 1) {
                nextSoal();
            } else {
                document.getElementById('quiz-form').submit();
            }
        }
    }, 1000);
}

function updateTimerDisplay() {
    const el = document.getElementById('timer-countdown');
    const box = document.getElementById('timer-box');
    if (!el) { return; }
    el.innerText = currentSeconds;

    // Color shift as time runs low
    box.classList.remove('bg-red-50', 'border-red-200', 'text-red-700',
                         'bg-amber-50', 'border-amber-200', 'text-amber-700',
                         'bg-green-50', 'border-green-200', 'text-green-700');
    if (currentSeconds <= 10) {
        box.classList.add('bg-red-50', 'border-red-200', 'text-red-700');
    } else if (currentSeconds <= 20) {
        box.classList.add('bg-amber-50', 'border-amber-200', 'text-amber-700');
    } else {
        box.classList.add('bg-green-50', 'border-green-200', 'text-green-700');
    }
}

function showSlide(index) {
    slides.forEach((s, idx) => {
        s.classList.toggle('hidden', idx !== index);
    });
    document.getElementById('current-question-num').innerText = index + 1;
    const pct = ((index + 1) / totalSoal) * 100;
    document.getElementById('quiz-progress-bar').style.width = pct + '%';
    currentIdx = index;
    startTimer();
}

function nextSoal() {
    if (currentIdx < totalSoal - 1) { showSlide(currentIdx + 1); }
}

function prevSoal() {
    if (currentIdx > 0) { showSlide(currentIdx - 1); }
}

document.addEventListener('DOMContentLoaded', () => {
    if (totalSoal > 0) { showSlide(0); }
});
</script>
@endsection
