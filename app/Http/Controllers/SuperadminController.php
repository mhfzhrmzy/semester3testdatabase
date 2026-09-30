<?php

namespace App\Http\Controllers;

use App\Models\AdminGuru;
use App\Models\Materi;
use App\Models\PenggunaSiswa;
use App\Models\Quiz;
use App\Models\Sertifikat;
use Illuminate\Support\Facades\Auth;

class SuperadminController extends Controller
{
    public function index()
    {
        $gurus = AdminGuru::orderBy('nama_lengkap')->get();
        $siswas = PenggunaSiswa::orderBy('nama_lengkap')->get();
        $materiCount = Materi::count();
        $quizCount = Quiz::count();
        $sertifikatCount = Sertifikat::count();
        $superadmin = Auth::guard('superadmin')->user();

        $menu = request()->query('menu', 'dashboard');

        if (! in_array($menu, ['dashboard', 'guru', 'siswa', 'pengaturan'], true)) {
            $menu = 'dashboard';
        }

        return view('superadmin.index', compact('gurus', 'siswas', 'materiCount', 'quizCount', 'sertifikatCount', 'menu', 'superadmin'));
    }
}
