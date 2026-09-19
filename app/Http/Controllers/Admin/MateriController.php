<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class MateriController extends Controller
{
    /**
     * Menampilkan daftar semua materi
     */
    public function index()
    {
        $materi = Materi::latest()->get();
        return view('admin.materi.index', compact('materi'));
    }

    /**
     * Menampilkan halaman form tambah materi
     */
    public function create()
    {
        return view('admin.materi.create');
    }

    /**
     * Menyimpan materi baru dan mengonversi otomatis PPTX/PPT ke PDF
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'judul' => 'required|string|max:255',
            'file'  => 'required|mimes:pdf,pptx,ppt,doc,docx|max:25600', // Maksimal 25MB
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = strtolower($file->getClientOriginalExtension());
            $cleanName = preg_replace('/[^A-Za-z0-9\-]/', '_', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
            $filename = time() . '_' . $cleanName;

            // Folder tujuan penyimpanan: storage/app/public/materi
            $outputDir = storage_path('app/public/materi');
            if (!file_exists($outputDir)) {
                mkdir($outputDir, 0775, true);
            }

            // --- PROSES KONVERSI OTOMATIS JIKA FILE PPT / PPTX / DOCX ---
            if (in_array($extension, ['pptx', 'ppt', 'docx', 'doc'])) {
                // Simpan file asli sementara
                $tempFileName = $filename . '.' . $extension;
                $file->storeAs('public/materi', $tempFileName);

                $inputPath = storage_path('app/public/materi/' . $tempFileName);

                // Ambil path LibreOffice dari .env (default: 'soffice')
                $libreOfficePath = env('LIBREOFFICE_PATH', 'soffice');
                
                // Temporary Profile agar aman dari permission web server (SYSTEM / www-data)
                $tempProfile = storage_path('app/libreoffice_profile');

                // Jalankan perintah konversi CLI LibreOffice
                $command = "\"{$libreOfficePath}\" -env:UserInstallation=file:///\"{$tempProfile}\" --headless --convert-to pdf --outdir \"{$outputDir}\" \"{$inputPath}\"";

                $result = Process::run($command);

                // Periksa apakah konversi berhasil dan file PDF terbentuk
                if ($result->successful() && file_exists($outputDir . '/' . $filename . '.pdf')) {
                    $finalFileName = $filename . '.pdf';

                    // Hapus file PPTX asli agar hemat storage
                    if (file_exists($inputPath)) {
                        unlink($inputPath);
                    }
                } else {
                    // Catat log jika konversi gagal
                    Log::error('LibreOffice Conversion Failed: ' . $result->errorOutput());
                    return back()->with('error', 'Gagal mengonversi file ke PDF. Pastikan LibreOffice terinstall dengan benar.');
                }
            } 
            // --- JIKA FILE SUDAH BERBENTUK PDF ---
            else {
                $finalFileName = $filename . '.pdf';
                $file->storeAs('public/materi', $finalFileName);
            }

            // 2. Simpan nama file PDF ke Database
            Materi::create([
                'judul' => $request->judul,
                'file'  => $finalFileName,
            ]);

            return redirect()->back()->with('success', 'Materi berhasil diunggah dan otomatis dikonversi ke PDF!');
        }

        return redirect()->back()->with('error', 'File tidak ditemukan.');
    }

    /**
     * Menampilkan detail materi
     */
    public function show($id)
    {
        $materi = Materi::findOrFail($id);
        return view('portal.materi.show', compact('materi'));
    }

    /**
     * Menghapus materi
     */
    public function destroy($id)
    {
        $materi = Materi::findOrFail($id);
        
        if ($materi->file && Storage::exists('public/materi/' . $materi->file)) {
            Storage::delete('public/materi/' . $materi->file);
        }

        $materi->delete();

        return redirect()->back()->with('success', 'Materi berhasil dihapus.');
    }
}