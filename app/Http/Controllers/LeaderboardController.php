<?php

namespace App\Http\Controllers;

use App\Models\Leaderboard;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index(Request $request): View
    {
        $filterTest = $request->query('tipe_test');

        $query = Leaderboard::with(['quiz.materi', 'pengguna'])
            ->orderBy('total_poin', 'desc')
            ->orderBy('updated_at', 'asc');

        if ($filterTest && in_array($filterTest, ['pretest', 'posttest'])) {
            $query->whereHas('quiz', function ($q) use ($filterTest) {
                $q->where('tipe_test', $filterTest);
            });
        }

        $leaderboards = $query->get();

        return view('leaderboard.index', compact('leaderboards', 'filterTest'));
    }
}
