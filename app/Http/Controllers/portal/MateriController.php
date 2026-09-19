<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use Illuminate\Support\Facades\Log;
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
     */
    public function preview(Materi $materi)
    {
        abort_unless($materi->upload_file, 404);

        $originalPath = storage_path('app/public/' . $materi->upload_file);
        abort_unless(file_exists($originalPath), 404);

        $ext = strtolower(pathinfo($originalPath, PATHINFO_EXTENSION));

        // Sudah PDF -> langsung tampilkan di browser/iframe
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

        // Cek apakah perlu konversi ulang
        $needsConversion = ! file_exists($convertedPath)
            || filemtime($originalPath) > filemtime($convertedPath);

        if ($needsConversion) {
            $converted = $this->convertToPdf($originalPath, $cacheDir);

            if (! $converted) {
                return response(
                    '<!DOCTYPE html><html><head><meta charset="utf-8"></head><body style="font-family:sans-serif;display:flex;align-items:center;justify-content:center;height:90vh;background:#f9fafb;color:#374151;text-align:center;"><div style="padding:20px;background:#fff;border:1px solid #e5e7eb;border-radius:8px;box-shadow:0 1px 3px rgba(0,0,0,0.1);"><h3>Preview PDF Belum Tersedia</h3><p style="font-size:14px;color:#6b7280;">Gagal mengonversi file ke PDF. Pastikan LibreOffice terpasang di komputer.<br>Silakan klik tombol <b>Download File Asli</b> di atas untuk membaca materi ini.</p></div></body></html>',
                    Response::HTTP_OK,
                    ['Content-Type' => 'text/html']
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
     * Konversi file ke PDF menggunakan eksekusi CLI Native Windows/Linux
     */
    private function convertToPdf(string $sourcePath, string $outputDir): bool
    {
        $sofficeBin = env('LIBREOFFICE_PATH', 'C:/Program Files/LibreOffice/program/soffice.exe');
        $sofficeBin = str_replace('/', DIRECTORY_SEPARATOR, trim($sofficeBin, '"\''));

        if (! file_exists($sofficeBin)) {
            Log::error('Executable LibreOffice tidak ditemukan di path: ' . $sofficeBin);
            return false;
        }

        $profileDir = storage_path('app/libreoffice-profile-' . uniqid());
        $profileUrl = 'file:///' . str_replace('\\', '/', $profileDir);

        // Menyiapkan string eksekusi command aman untuk Windows CLI
        $command = sprintf(
            '"%s" -env:UserInstallation="%s" --headless --norestore --convert-to pdf --outdir "%s" "%s"',
            $sofficeBin,
            $profileUrl,
            $outputDir,
            $sourcePath
        );

        // Eksekusi via shell exec
        $output = [];
        $returnCode = 0;
        exec($command, $output, $returnCode);

        // Bersihkan folder profil sementara
        $this->deleteDirectory($profileDir);

        $baseName = pathinfo($sourcePath, PATHINFO_FILENAME);
        $expectedPdf = $outputDir . DIRECTORY_SEPARATOR . $baseName . '.pdf';

        if (! file_exists($expectedPdf)) {
            Log::warning('Konversi LibreOffice gagal atau file PDF tidak terbentuk', [
                'command' => $command,
                'exit_code' => $returnCode,
                'output' => implode("\n", $output),
            ]);

            return false;
        }

        return true;
    }

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