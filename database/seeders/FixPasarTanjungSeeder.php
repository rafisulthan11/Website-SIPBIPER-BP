<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pasar;

class FixPasarTanjungSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Memperbaiki data Pasar Tanjung...');
        
        $pasar = Pasar::where('nama_pasar', 'pasar tanjung')->first();
        
        if ($pasar) {
            $pasar->kecamatan = 'Patrang';
            $pasar->desa = 'Jemberlor';
            $pasar->save();
            
            $this->command->info("✓ Pasar Tanjung berhasil diupdate");
            $this->command->info("  Kecamatan: {$pasar->kecamatan}");
            $this->command->info("  Desa: {$pasar->desa}");
        } else {
            $this->command->warn('Pasar Tanjung tidak ditemukan');
        }
    }
}
