<?php

namespace Tests\Feature;

use App\Models\AdminGuru;
use App\Models\PenggunaSiswa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_register_screen_can_be_rendered(): void
    {
        $response = $this->get('/register/guru');

        $response->assertStatus(200);
        $response->assertSee('Registrasi Guru Baru');
    }

    public function test_admin_can_register_and_be_authenticated(): void
    {
        $response = $this->post('/register/guru', [
            'nip' => '198501012010011001',
            'nama_lengkap' => 'Guru Budi',
            'email' => 'budi@sekolah.sch.id',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertDatabaseHas('admin_guru', [
            'nip' => '198501012010011001',
            'email' => 'budi@sekolah.sch.id',
            'nama_lengkap' => 'Guru Budi',
            'role' => 'guru',
        ]);

        $admin = AdminGuru::where('email', 'budi@sekolah.sch.id')->first();
        $this->assertAuthenticatedAs($admin, 'admin');
        $response->assertRedirect('/materi');
    }

    public function test_admin_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login/guru');

        $response->assertStatus(200);
        $response->assertSee('Login Guru / Admin');
    }

    public function test_admin_can_login(): void
    {
        $admin = AdminGuru::create([
            'nip' => '198501012010011002',
            'nama_lengkap' => 'Guru Ani',
            'email' => 'ani@sekolah.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'guru',
        ]);

        $response = $this->post('/login/guru', [
            'nip' => '198501012010011002',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($admin->fresh(), 'admin');
        $response->assertRedirect('/materi');
    }

    public function test_admin_can_logout(): void
    {
        $admin = AdminGuru::create([
            'nip' => '198501012010011003',
            'nama_lengkap' => 'Guru Candra',
            'email' => 'candra@sekolah.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'guru',
        ]);

        $this->actingAs($admin, 'admin');

        $response = $this->post('/logout/guru');

        $this->assertGuest('admin');
        $response->assertRedirect('/');
    }

    public function test_siswa_register_screen_can_be_rendered(): void
    {
        $response = $this->get('/register/siswa');

        $response->assertStatus(200);
        $response->assertSee('Registrasi Siswa Baru');
    }

    public function test_siswa_can_register_and_be_authenticated(): void
    {
        $response = $this->post('/register/siswa', [
            'nisn' => '0051234567',
            'nama_lengkap' => 'Siswa Doni',
            'email' => 'doni@sekolah.sch.id',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertDatabaseHas('pengguna_siswa', [
            'email' => 'doni@sekolah.sch.id',
            'nama_lengkap' => 'Siswa Doni',
            'poin' => 0,
        ]);

        $siswa = PenggunaSiswa::where('email', 'doni@sekolah.sch.id')->first();
        $this->assertAuthenticatedAs($siswa, 'siswa');
        $response->assertRedirect('/portal/materi');
    }

    public function test_siswa_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login/siswa');

        $response->assertStatus(200);
        $response->assertSee('Login Siswa');
    }

    public function test_siswa_can_login(): void
    {
        $siswa = PenggunaSiswa::create([
            'nisn' => '0051234568',
            'nama_lengkap' => 'Siswa Eka',
            'email' => 'eka@sekolah.sch.id',
            'password' => Hash::make('password123'),
            'poin' => 0,
        ]);

        $response = $this->post('/login/siswa', [
            'nisn' => '0051234568',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($siswa->fresh(), 'siswa');
        $response->assertRedirect('/portal/materi');
    }

    public function test_siswa_can_logout(): void
    {
        $siswa = PenggunaSiswa::create([
            'nisn' => '0051234569',
            'nama_lengkap' => 'Siswa Fajar',
            'email' => 'fajar@sekolah.sch.id',
            'password' => Hash::make('password123'),
            'poin' => 0,
        ]);

        $this->actingAs($siswa, 'siswa');

        $response = $this->post('/logout/siswa');

        $this->assertGuest('siswa');
        $response->assertRedirect('/');
    }

    public function test_registration_validation_fails_for_invalid_nip_and_nisn(): void
    {
        $responseGuru = $this->post('/register/guru', [
            'nip' => '12345',
            'nama_lengkap' => 'Guru Invalid',
            'email' => 'invalid-email',
            'password' => '123',
            'password_confirmation' => '456',
        ]);

        $responseGuru->assertSessionHasErrors(['nip', 'email', 'password']);

        $responseSiswa = $this->post('/register/siswa', [
            'nisn' => '123',
            'nama_lengkap' => 'Siswa Invalid',
            'email' => 'invalid-email',
            'password' => '123',
            'password_confirmation' => '456',
        ]);

        $responseSiswa->assertSessionHasErrors(['nisn', 'email', 'password']);
    }
}
