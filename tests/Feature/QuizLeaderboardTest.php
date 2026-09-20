<?php

namespace Tests\Feature;

use App\Models\AdminGuru;
use App\Models\Materi;
use App\Models\PenggunaSiswa;
use App\Models\Quiz;
use App\Models\Soal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class QuizLeaderboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_materi_file(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->create('modul.pdf', 100);
        $path = $file->store('materi', 'public');

        $admin = AdminGuru::create([
            'nip' => '198501012010011005',
            'nama_lengkap' => 'Guru Test',
            'email' => 'gurutest@sekolah.sch.id',
            'password' => 'password123',
            'role' => 'guru',
        ]);

        $materi = Materi::create([
            'judul_materi' => 'Matematika Dasar',
            'nip' => $admin->nip,
            'isi_materi' => 'Konten materi',
            'upload_file' => $path,
        ]);

        $response = $this->actingAs($admin, 'admin')->get(route('materi.show', $materi));
        $response->assertStatus(200);
    }

    public function test_quiz_creation_defaults_tanggal_to_today(): void
    {
        $admin = AdminGuru::create([
            'nip' => '198501012010011006',
            'nama_lengkap' => 'Guru Test 2',
            'email' => 'gurutest2@sekolah.sch.id',
            'password' => 'password123',
            'role' => 'guru',
        ]);

        $materi = Materi::create([
            'judul_materi' => 'Fisika Dasar',
            'nip' => $admin->nip,
        ]);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.quiz.store'), [
            'id_materi' => $materi->id_materi,
            'tipe_test' => 'pretest',
            'poin' => 10,
            'timer' => 30,
        ]);

        $this->assertDatabaseHas('quiz', [
            'id_materi' => $materi->id_materi,
            'tipe_test' => 'pretest',
            'tanggal' => now()->toDateString(),
        ]);

        $response->assertRedirect(route('admin.quiz.index'));
    }

    public function test_admin_can_import_csv_questions(): void
    {
        $admin = AdminGuru::create([
            'nip' => '198501012010011007',
            'nama_lengkap' => 'Guru Test 3',
            'email' => 'gurutest3@sekolah.sch.id',
            'password' => 'password123',
            'role' => 'guru',
        ]);

        $materi = Materi::create(['judul_materi' => 'Biologi', 'nip' => $admin->nip]);
        $quiz = Quiz::create([
            'id_materi' => $materi->id_materi,
            'nip' => $admin->nip,
            'tipe_test' => 'pretest',
            'poin' => 10,
            'timer' => 30,
            'tanggal' => now()->toDateString(),
        ]);

        $csvContent = "pertanyaan,pilihan_a,pilihan_b,pilihan_c,pilihan_d,jawaban_benar,timer_per_soal\n" .
                      "Berapa 1+1?,1,2,3,4,b,30\n" .
                      "Apa warna daun?,Hijau,Biru,Merah,Kuning,a,45\n";

        $file = UploadedFile::fake()->createWithContent('soal.csv', $csvContent);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.soal.import', $quiz), [
            'csv_file' => $file,
        ]);

        $this->assertDatabaseHas('soal', [
            'id_quiz' => $quiz->id_quiz,
            'pertanyaan' => 'Berapa 1+1?',
            'jawaban_benar' => 'b',
            'timer_per_soal' => 30,
        ]);

        $response->assertRedirect(route('admin.soal.index', $quiz));
    }

    public function test_leaderboard_page_renders_successfully(): void
    {
        $siswa = PenggunaSiswa::create([
            'nisn' => '0059999999',
            'nama_lengkap' => 'Siswa Pintar',
            'email' => 'pintar@sekolah.sch.id',
            'password' => 'password123',
        ]);

        $response = $this->actingAs($siswa, 'siswa')->get(route('leaderboard.index'));
        $response->assertStatus(200);
        $response->assertSee('Leaderboard');
    }
}
