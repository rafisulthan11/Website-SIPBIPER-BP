<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KecamatanJemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Data Kecamatan Kabupaten Jember (31 Kecamatan)
     * Sumber: Permendagri No. 72 Tahun 2019
     */
    public function run(): void
    {
        // Hapus data lama
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('master_kecamatans')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $kecamatans = [
            ['id_kecamatan' => 1, 'nama_kecamatan' => 'Kencong', 'kode_kecamatan' => '3509010'],
            ['id_kecamatan' => 2, 'nama_kecamatan' => 'Gumukmas', 'kode_kecamatan' => '3509020'],
            ['id_kecamatan' => 3, 'nama_kecamatan' => 'Puger', 'kode_kecamatan' => '3509021'],
            ['id_kecamatan' => 4, 'nama_kecamatan' => 'Wuluhan', 'kode_kecamatan' => '3509030'],
            ['id_kecamatan' => 5, 'nama_kecamatan' => 'Ambulu', 'kode_kecamatan' => '3509040'],
            ['id_kecamatan' => 6, 'nama_kecamatan' => 'Tempurejo', 'kode_kecamatan' => '3509050'],
            ['id_kecamatan' => 7, 'nama_kecamatan' => 'Silo', 'kode_kecamatan' => '3509060'],
            ['id_kecamatan' => 8, 'nama_kecamatan' => 'Mayang', 'kode_kecamatan' => '3509070'],
            ['id_kecamatan' => 9, 'nama_kecamatan' => 'Mumbulsari', 'kode_kecamatan' => '3509080'],
            ['id_kecamatan' => 10, 'nama_kecamatan' => 'Jenggawah', 'kode_kecamatan' => '3509081'],
            ['id_kecamatan' => 11, 'nama_kecamatan' => 'Ajung', 'kode_kecamatan' => '3509090'],
            ['id_kecamatan' => 12, 'nama_kecamatan' => 'Rambipuji', 'kode_kecamatan' => '3509100'],
            ['id_kecamatan' => 13, 'nama_kecamatan' => 'Balung', 'kode_kecamatan' => '3509101'],
            ['id_kecamatan' => 14, 'nama_kecamatan' => 'Umbulsari', 'kode_kecamatan' => '3509102'],
            ['id_kecamatan' => 15, 'nama_kecamatan' => 'Semboro', 'kode_kecamatan' => '3509110'],
            ['id_kecamatan' => 16, 'nama_kecamatan' => 'Jombang', 'kode_kecamatan' => '3509111'],
            ['id_kecamatan' => 17, 'nama_kecamatan' => 'Tanggul', 'kode_kecamatan' => '3509120'],
            ['id_kecamatan' => 18, 'nama_kecamatan' => 'Bangsalsari', 'kode_kecamatan' => '3509121'],
            ['id_kecamatan' => 19, 'nama_kecamatan' => 'Panti', 'kode_kecamatan' => '3509130'],
            ['id_kecamatan' => 20, 'nama_kecamatan' => 'Sukorambi', 'kode_kecamatan' => '3509140'],
            ['id_kecamatan' => 21, 'nama_kecamatan' => 'Arjasa', 'kode_kecamatan' => '3509150'],
            ['id_kecamatan' => 22, 'nama_kecamatan' => 'Pakusari', 'kode_kecamatan' => '3509160'],
            ['id_kecamatan' => 23, 'nama_kecamatan' => 'Kalisat', 'kode_kecamatan' => '3509170'],
            ['id_kecamatan' => 24, 'nama_kecamatan' => 'Ledokombo', 'kode_kecamatan' => '3509180'],
            ['id_kecamatan' => 25, 'nama_kecamatan' => 'Sumberjambe', 'kode_kecamatan' => '3509190'],
            ['id_kecamatan' => 26, 'nama_kecamatan' => 'Sukowono', 'kode_kecamatan' => '3509200'],
            ['id_kecamatan' => 27, 'nama_kecamatan' => 'Jelbuk', 'kode_kecamatan' => '3509210'],
            ['id_kecamatan' => 28, 'nama_kecamatan' => 'Kaliwates', 'kode_kecamatan' => '3509220'],
            ['id_kecamatan' => 29, 'nama_kecamatan' => 'Sumbersari', 'kode_kecamatan' => '3509230'],
            ['id_kecamatan' => 30, 'nama_kecamatan' => 'Patrang', 'kode_kecamatan' => '3509240'],
            ['id_kecamatan' => 31, 'nama_kecamatan' => 'Mangli', 'kode_kecamatan' => '3509250'],
        ];

        foreach ($kecamatans as $kecamatan) {
            DB::table('master_kecamatans')->insert([
                'id_kecamatan' => $kecamatan['id_kecamatan'],
                'nama_kecamatan' => $kecamatan['nama_kecamatan'],
                'kode_kecamatan' => $kecamatan['kode_kecamatan'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('✓ 31 Kecamatan Kabupaten Jember berhasil di-seed');
    }
}
