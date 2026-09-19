<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use Symfony\Component\HttpFoundation\Response;

class MateriController extends Controller
{
    public function index()
    {
        $materis = Materi::with('admin')->latest()->get();

        return view('portal.materi.index', compact('materis'));
    }

    public function show(Materi $materi)
    {
        $materi->load('admin');

        return view('portal.materi.show', compact('materi'));
    }

    /**
     * Tampilkan modul materi sebagai PDF, walau file aslinya PPTX/DOC/DOCX.
     * Kalau file aslinya sudah PDF, langsung ditampilkan.
     * Kalau bukan, dikonversi dulu ke PDF pakai LibreOffice (soffice), lalu
     * hasil konversinya di-cache supaya konversi cuma perlu terjadi sekali
     * per file (bukan setiap kali dibuka).
     */
    public function preview(Materi $materi)
    {
        abort_unless($materi->upload_file, 404);

        $originalPath = storage_path('app/public/' . $materi->upload_file);
        abort_unless(file_exists($originalPath), 404);

        $ext = strtolower(pathinfo($originalPath, PATHINFO_EXTENSION));

        // Sudah PDF -> langsung tampilkan, tidak perlu konversi.
        if ($ext === 'pdf') {
            return response()->file($originalPath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . basename($originalPath) . '"',
            ]);
        }

        if (! in_array($ext, ['doc', 'docx', 'ppt', 'pptx'])) {
            abort(415, 'Format file ini tidak didukung untuk preview.');
        }

        $cacheDir = storage_path('app/public/materi/preview');
        if (! is_dir($cacheDir)) {
            mkdir($cacheDir, 0775, true);
        }

        $baseName = pathinfo($originalPath, PATHINFO_FILENAME);
        $convertedPath = $cacheDir . DIRECTORY_SEPARATOR . $baseName . '.pdf';

        // Konversi ulang cuma kalau file PDF hasil konversi belum ada,
        // atau file aslinya lebih baru dari hasil konversi sebelumnya.
        $needsConversion = ! file_exists($convertedPath)
            || filemtime($originalPath) > filemtime($convertedPath);

        if ($needsConversion) {
            $converted = $this->convertToPdf($originalPath, $cacheDir);

            if (! $converted) {
                // LibreOffice tidak ada / gagal -> jangan bikin halaman error,
                // cukup suruh user download filenya langsung.
                return response(
                    'Preview otomatis belum tersedia untuk file ini (LibreOffice belum '
                    . 'terpasang / gagal dijalankan di server). Silakan download file aslinya.',
                    Response::HTTP_SERVICE_UNAVAILABLE
                );
            }
        }

        abort_unless(file_exists($convertedPath), 500, 'Konversi berhasil tapi file hasil tidak ditemukan.');

        return response()->file($convertedPath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $baseName . '.pdf"',
        ]);
    }

    /**
     * Jalankan LibreOffice (soffice) secara headless untuk convert 1 file
     * ke PDF. Path binary soffice bisa diatur lewat .env (LIBREOFFICE_PATH),
     * default-nya asumsi "soffice" sudah ada di PATH sistem.
     */
    private function convertToPdf(string $sourcePath, string $outputDir): bool
    {
        $sofficeBin = env('LIBREOFFICE_PATH', 'soffice');

        // -env:UserInstallation dipakai supaya tiap konversi pakai folder
        // profil sendiri (folder sementara), menghindari error "another
        // instance of soffice is already running" kalau ada request lain
        // yang jalan bersamaan.
        $profileDir = storage_path('app/libreoffice-profile-' . uniqid());

        $result = Process::timeout(90)->run([
            $sofficeBin,
            '--headless',
            '--norestore',
            '--convert-to', 'pdf',
            '--outdir', $outputDir,
            $sourcePath,
            '-env:UserInstallation=file:///' . str_replace('\\', '/', $profileDir),
        ]);

        // Bersihkan folder profil sementara setelah selesai (kalau ada).
        // Pakai penghapusan native PHP (bukan shell `rm`) supaya jalan
        // di Windows maupun Linux/macOS.
        $this->deleteDirectory($profileDir);

        if (! $result->successful()) {
            Log::warning('Konversi LibreOffice gagal', [
                'file' => $sourcePath,
                'exit_code' => $result->exitCode(),
                'error' => $result->errorOutput(),
            ]);

            return false;
        }

        return true;
    }

    /**
     * Hapus folder beserta isinya secara rekursif, pakai fungsi PHP native
     * (bukan shell command) supaya berfungsi baik di Windows maupun
     * Linux/macOS.
     */
    private function deleteDirectory(string $dir): void
    {
        if (! is_dir($dir)) {
            return;
        }

        $items = scandir($dir);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $dir . DIRECTORY_SEPARATOR . $item;

            if (is_dir($path)) {
                $this->deleteDirectory($path);
            } else {
                @unlink($path);
            }
        }

        @rmdir($dir);
    }
}
