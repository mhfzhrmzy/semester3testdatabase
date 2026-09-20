<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Leaderboard;
use App\Models\Materi;
use App\Models\PenggunaSiswa;
use App\Models\Quiz;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalMateri' => Materi::count(),
            'totalQuiz' => Quiz::count(),
            'totalSiswa' => PenggunaSiswa::count(),
            'totalHasilPosttest' => Leaderboard::whereHas('quiz', fn ($q) => $q->where('tipe_test', 'posttest'))->count(),
        ]);
    }
}
