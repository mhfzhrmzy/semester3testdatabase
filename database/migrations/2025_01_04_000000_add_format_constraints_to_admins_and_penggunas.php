<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Migration ini menambahkan batasan format ke tabel admins & penggunas:
     *   - admins.nip            -> integer, TEPAT 18 digit
     *   - penggunas.nisn_pengguna -> integer, TEPAT 10 digit
     *   - admins.nama_lengkap   -> hanya huruf (spasi, koma, titik masih boleh)
     *   - penggunas.nama_lengkap -> sama seperti di atas
     *
     * Ditulis idempotent (aman dijalankan berkali-kali / di database yang
     * sudah pernah diperbaiki manual lewat SQL) -- setiap langkah dicek
     * dulu apakah sudah pernah diterapkan sebelum benar-benar dijalankan.
     */
    public function up(): void
    {
        // 1) Bersihkan data lama yang formatnya tidak sesuai (kalau ada),
        //    supaya langkah ALTER TABLE di bawah tidak gagal.
        DB::statement("DELETE FROM `admins` WHERE `nip` NOT REGEXP '^[0-9]{18}$'");
        DB::statement("DELETE FROM `penggunas` WHERE `nisn_pengguna` NOT REGEXP '^[0-9]{10}$'");

        // 2) Lepas FK yang mengarah ke admins.nip dulu, supaya kolomnya
        //    bisa diubah tipenya dari varchar -> bigint.
        if ($this->foreignKeyExists('materis', 'materis_nip_foreign')) {
            DB::statement('ALTER TABLE `materis` DROP FOREIGN KEY `materis_nip_foreign`');
        }
        if ($this->foreignKeyExists('soals', 'soals_nip_foreign')) {
            DB::statement('ALTER TABLE `soals` DROP FOREIGN KEY `soals_nip_foreign`');
        }

        // 3) Ubah tipe kolom nip & nisn_pengguna -> bigint unsigned.
        //    (bigint dipilih, bukan int biasa, karena int cuma sanggup
        //    sampai ~10 digit, sedangkan nip butuh 18 digit)
        DB::statement('ALTER TABLE `admins` MODIFY `nip` BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE `materis` MODIFY `nip` BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE `soals` MODIFY `nip` BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE `penggunas` MODIFY `nisn_pengguna` BIGINT UNSIGNED NOT NULL');

        // 4) Pasang kembali FK yang tadi dilepas.
        if (! $this->foreignKeyExists('materis', 'materis_nip_foreign')) {
            DB::statement('
                ALTER TABLE `materis`
                ADD CONSTRAINT `materis_nip_foreign` FOREIGN KEY (`nip`)
                REFERENCES `admins` (`nip`) ON DELETE CASCADE
            ');
        }
        if (! $this->foreignKeyExists('soals', 'soals_nip_foreign')) {
            DB::statement('
                ALTER TABLE `soals`
                ADD CONSTRAINT `soals_nip_foreign` FOREIGN KEY (`nip`)
                REFERENCES `admins` (`nip`) ON DELETE CASCADE
            ');
        }

        // 5) Tambahkan CHECK constraint panjang digit (nip = 18, nisn = 10).
        //    "Hanya boleh angka" otomatis terjamin karena tipe datanya
        //    sudah BIGINT.
        if (! $this->checkConstraintExists('admins', 'chk_admins_nip_length')) {
            DB::statement("
                ALTER TABLE `admins`
                ADD CONSTRAINT `chk_admins_nip_length`
                CHECK (CHAR_LENGTH(CAST(`nip` AS CHAR)) = 18)
            ");
        }
        if (! $this->checkConstraintExists('penggunas', 'chk_penggunas_nisn_length')) {
            DB::statement("
                ALTER TABLE `penggunas`
                ADD CONSTRAINT `chk_penggunas_nisn_length`
                CHECK (CHAR_LENGTH(CAST(`nisn_pengguna` AS CHAR)) = 10)
            ");
        }

        // 6) Tambahkan CHECK constraint format nama (hanya huruf, spasi,
        //    koma & titik -- supaya nama bergelar seperti "Siti Rahmawati,
        //    M.T." tetap valid, tapi angka & simbol lain ditolak).
        if (! $this->checkConstraintExists('admins', 'chk_admins_nama_format')) {
            DB::statement("
                ALTER TABLE `admins`
                ADD CONSTRAINT `chk_admins_nama_format`
                CHECK (`nama_lengkap` REGEXP '^[A-Za-z ,.]+$')
            ");
        }
        if (! $this->checkConstraintExists('penggunas', 'chk_penggunas_nama_format')) {
            DB::statement("
                ALTER TABLE `penggunas`
                ADD CONSTRAINT `chk_penggunas_nama_format`
                CHECK (`nama_lengkap` REGEXP '^[A-Za-z ,.]+$')
            ");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (['chk_admins_nip_length', 'chk_admins_nama_format'] as $name) {
            if ($this->checkConstraintExists('admins', $name)) {
                DB::statement("ALTER TABLE `admins` DROP CONSTRAINT `{$name}`");
            }
        }

        foreach (['chk_penggunas_nisn_length', 'chk_penggunas_nama_format'] as $name) {
            if ($this->checkConstraintExists('penggunas', $name)) {
                DB::statement("ALTER TABLE `penggunas` DROP CONSTRAINT `{$name}`");
            }
        }

        DB::statement('ALTER TABLE `admins` MODIFY `nip` VARCHAR(20) NOT NULL');
        DB::statement('ALTER TABLE `materis` MODIFY `nip` VARCHAR(20) NOT NULL');
        DB::statement('ALTER TABLE `soals` MODIFY `nip` VARCHAR(20) NOT NULL');
        DB::statement('ALTER TABLE `penggunas` MODIFY `nisn_pengguna` VARCHAR(20) NOT NULL');
    }

    /**
     * Cek apakah sebuah FOREIGN KEY constraint sudah ada di suatu tabel.
     */
    private function foreignKeyExists(string $table, string $constraintName): bool
    {
        $result = DB::selectOne('
            SELECT COUNT(*) AS cnt FROM information_schema.TABLE_CONSTRAINTS
            WHERE CONSTRAINT_SCHEMA = DATABASE()
            AND TABLE_NAME = ?
            AND CONSTRAINT_NAME = ?
            AND CONSTRAINT_TYPE = "FOREIGN KEY"
        ', [$table, $constraintName]);

        return $result && $result->cnt > 0;
    }

    /**
     * Cek apakah sebuah CHECK constraint sudah ada di suatu tabel.
     */
    private function checkConstraintExists(string $table, string $constraintName): bool
    {
        $result = DB::selectOne('
            SELECT COUNT(*) AS cnt FROM information_schema.TABLE_CONSTRAINTS
            WHERE CONSTRAINT_SCHEMA = DATABASE()
            AND TABLE_NAME = ?
            AND CONSTRAINT_NAME = ?
            AND CONSTRAINT_TYPE = "CHECK"
        ', [$table, $constraintName]);

        return $result && $result->cnt > 0;
    }
};
