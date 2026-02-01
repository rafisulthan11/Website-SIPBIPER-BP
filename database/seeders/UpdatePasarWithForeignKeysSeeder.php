<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pasar;
use App\Models\MasterKecamatan;
use App\Models\MasterDesa;

class UpdatePasarWithForeignKeysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Mengupdate data pasar dengan id_kecamatan dan id_desa...');
        
        $pasars = Pasar::all();
        $updated = 0;
        $notFound = 0;
        
        foreach ($pasars as $pasar) {
            // Cari kecamatan berdasarkan nama (case insensitive)
            $kecamatan = MasterKecamatan::whereRaw('LOWER(nama_kecamatan) = ?', [strtolower(trim($pasar->kecamatan))])
                ->first();
            
            if (!$kecamatan) {
                $this->command->warn("Kecamatan tidak ditemukan untuk pasar: {$pasar->nama_pasar} (kecamatan: {$pasar->kecamatan})");
                $notFound++;
                continue;
            }
            
            // Cari desa berdasarkan nama dan id_kecamatan (case insensitive)
            $desa = MasterDesa::where('id_kecamatan', $kecamatan->id_kecamatan)
                ->whereRaw('LOWER(nama_desa) = ?', [strtolower(trim($pasar->desa))])
                ->first();
            
            if (!$desa) {
                $this->command->warn("Desa tidak ditemukan untuk pasar: {$pasar->nama_pasar} (desa: {$pasar->desa}, kecamatan: {$pasar->kecamatan})");
                $notFound++;
                continue;
            }
            
            // Update pasar dengan id_kecamatan dan id_desa
            $pasar->id_kecamatan = $kecamatan->id_kecamatan;
            $pasar->id_desa = $desa->id_desa;
            $pasar->save();
            
            $this->command->info("✓ Updated: {$pasar->nama_pasar} → Kecamatan: {$kecamatan->nama_kecamatan}, Desa: {$desa->nama_desa}");
            $updated++;
        }
        
        $this->command->info("\nSelesai!");
        $this->command->info("Total pasar: " . $pasars->count());
        $this->command->info("Berhasil diupdate: {$updated}");
        $this->command->info("Tidak ditemukan: {$notFound}");
    }
}
