<?php

namespace App\Exports;

use App\Models\Pengolah;
use App\Models\MasterDesa;
use App\Models\MasterKecamatan;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RekapitulasiPengolahExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithCustomValueBinder
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function bindValue(Cell $cell, $value)
    {
        if (is_string($value) && preg_match('/^\d{16,}$/', $value)) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }

        return parent::bindValue($cell, $value);
    }

    public function collection()
    {
        $query = Pengolah::with(['kecamatan', 'desa', 'kecamatanUsaha', 'desaUsaha'])
            ->where('status', 'verified'); // Hanya data yang sudah diverifikasi

        // Apply filters
        if (!empty($this->filters['kecamatan'])) {
            $query->where('id_kecamatan', $this->filters['kecamatan']);
        }

        if (!empty($this->filters['komoditas'])) {
            $query->where('komoditas', 'like', '%' . $this->filters['komoditas'] . '%');
        }

        if (!empty($this->filters['kategori'])) {
            $query->where('skala_usaha', $this->filters['kategori']);
        }

        if (!empty($this->filters['jenis_kegiatan_usaha'])) {
            $query->where('jenis_kegiatan_usaha', $this->filters['jenis_kegiatan_usaha']);
        }

        // Filter berdasarkan tahun pendataan
        if (!empty($this->filters['tahun'])) {
            $query->where('tahun_pendataan', (int) $this->filters['tahun']);
        }

        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%'.$search.'%')
                  ->orWhere('nama_usaha', 'like', '%'.$search.'%')
                  ->orWhere('jenis_kegiatan_usaha', 'like', '%'.$search.'%');
            });
        }

        $verified = $query->get();

        // Ambil snapshot terakhir terverifikasi untuk data yang saat ini pending/rejected.
        $backup = DB::table('pengolah_verified_backup as backup')
            ->join('pengolahs as current', 'current.id_pengolah', '=', 'backup.id_pengolah')
            ->whereIn('current.status', ['pending', 'rejected'])
            ->select('backup.*')
            ->get()
            ->map(function ($row) {
                $data = json_decode($row->data_verified, true);
                if (!is_array($data)) {
                    return null;
                }

                $relations = [];
                foreach (['kecamatan', 'desa', 'kecamatanUsaha', 'desaUsaha'] as $rel) {
                    if (isset($data[$rel])) {
                        $relations[$rel] = $data[$rel];
                        unset($data[$rel]);
                    }
                }

                $pengolah = new Pengolah();
                $pengolah->forceFill($data);
                $pengolah->exists = true;
                $pengolah->setAttribute('from_backup_snapshot', true);

                if (!empty($relations['kecamatan'])) {
                    $kecamatan = new MasterKecamatan();
                    $kecamatan->forceFill($relations['kecamatan']);
                    $kecamatan->exists = true;
                    $pengolah->setRelation('kecamatan', $kecamatan);
                }
                if (!empty($relations['desa'])) {
                    $desa = new MasterDesa();
                    $desa->forceFill($relations['desa']);
                    $desa->exists = true;
                    $pengolah->setRelation('desa', $desa);
                }
                if (!empty($relations['kecamatanUsaha'])) {
                    $kecamatanUsaha = new MasterKecamatan();
                    $kecamatanUsaha->forceFill($relations['kecamatanUsaha']);
                    $kecamatanUsaha->exists = true;
                    $pengolah->setRelation('kecamatanUsaha', $kecamatanUsaha);
                }
                if (!empty($relations['desaUsaha'])) {
                    $desaUsaha = new MasterDesa();
                    $desaUsaha->forceFill($relations['desaUsaha']);
                    $desaUsaha->exists = true;
                    $pengolah->setRelation('desaUsaha', $desaUsaha);
                }

                return $pengolah;
            })
            ->filter(function ($item) {
                if (!$item) {
                    return false;
                }

                if (!empty($this->filters['kecamatan']) && (string) ($item->id_kecamatan ?? '') !== (string) $this->filters['kecamatan']) {
                    return false;
                }
                if (!empty($this->filters['komoditas']) && !str_contains(strtolower((string) ($item->komoditas ?? '')), strtolower((string) $this->filters['komoditas']))) {
                    return false;
                }
                if (!empty($this->filters['kategori']) && (string) ($item->skala_usaha ?? '') !== (string) $this->filters['kategori']) {
                    return false;
                }
                if (!empty($this->filters['jenis_kegiatan_usaha']) && (string) ($item->jenis_kegiatan_usaha ?? '') !== (string) $this->filters['jenis_kegiatan_usaha']) {
                    return false;
                }
                if (!empty($this->filters['tahun']) && (int) ($item->tahun_pendataan ?? 0) !== (int) $this->filters['tahun']) {
                    return false;
                }
                if (!empty($this->filters['search'])) {
                    $search = strtolower((string) $this->filters['search']);
                    $haystack = strtolower((string) ($item->nama_lengkap ?? '')) . ' ' . strtolower((string) ($item->nama_usaha ?? '')) . ' ' . strtolower((string) ($item->jenis_kegiatan_usaha ?? ''));
                    if (!str_contains($haystack, $search)) {
                        return false;
                    }
                }

                return true;
            })
            ->values();

        $pengolahs = $verified->keyBy('id_pengolah');
        foreach ($backup as $backupItem) {
            $pengolahs[$backupItem->id_pengolah] = $backupItem;
        }
        $pengolahs = $pengolahs->values()->sortBy('nik_pengolah')->sortBy('tahun_pendataan')->values();

        // Hitung total produksi dan simpan detail untuk setiap pengolah
        $pengolahs->each(function($item) {
            $totalProduksi = 0;
            $detailProduksi = [];
            
            if ($item->produksi_data && is_array($item->produksi_data)) {
                foreach ($item->produksi_data as $produk) {
                    // Filter bulan jika ada
                    if (!empty($this->filters['bulan'])) {
                        $bulanProduksi = $produk['bulan_produksi'] ?? [];
                        
                        if (!in_array($this->filters['bulan'], $bulanProduksi)) {
                            continue;
                        }
                    }
                    
                    $hasilProduksiKg = floatval($produk['harga_produksi_qty'] ?? $produk['hasil_produksi_qty'] ?? 0);
                    $totalProduksi += $hasilProduksiKg;
                    
                    // Simpan detail produksi
                    $detailProduksi[] = $produk;
                }
            }
            
            $item->total_produksi = $totalProduksi;
            $item->detail_produksi = $detailProduksi;
            $item->has_produksi = $totalProduksi > 0;
        });

        // Filter out records dengan produksi 0 jika ada filter bulan
        if (!empty($this->filters['bulan'])) {
            $pengolahs = $pengolahs->filter(function($item) {
                return $item->has_produksi;
            })->values();
        }

        return $pengolahs;
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA LENGKAP',
            'NIK',
            'TAHUN PENDATAAN',
            'JENIS KELAMIN',
            'TEMPAT LAHIR',
            'TANGGAL LAHIR',
            'PENDIDIKAN TERAKHIR',
            'STATUS PERKAWINAN',
            'JUMLAH TANGGUNGAN',
            'ASET PRIBADI',
            'ALAMAT LENGKAP',
            'KECAMATAN',
            'DESA',
            'KONTAK',
            'EMAIL',
            'NO NPWP',
            'NAMA USAHA',
            'NAMA KELOMPOK',
            'TAHUN MULAI USAHA',
            'STATUS USAHA',
            'SKALA USAHA',
            'KECAMATAN USAHA',
            'DESA USAHA',
            'ALAMAT LENGKAP USAHA',
            'LATITUDE',
            'LONGITUDE',
            'JENIS KEGIATAN USAHA',
            'KOMODITAS',
            'IZIN NIB',
            'IZIN NPWP USAHA',
            'IZIN KUSUKA',
            'IZIN PENGESAHAN MENKUMHAM',
            'IZIN TDU-PHP',
            'IZIN AKTA PENDIRIAN USAHA',
            'IZIN IMB',
            'IZIN SIUP PERIKANAN',
            'IZIN SIUP PERDAGANGAN',
            'IZIN SPPL',
            'IZIN UKL-UPL',
            'IZIN AMDAL',
            'JUMLAH PRODUK',
            'DETAIL PRODUKSI (Nama Merk | Komoditas | Kapasitas | Hari Produksi | Bulan | Sertifikat | Biaya Produksi | Biaya Lain | Harga Jual | Hasil Produksi | Pemasaran | Harga Jual/Pack | Bahan Baku)',
            'TOTAL PRODUKSI (KG)',
            'TENAGA KERJA WNI LAKI-LAKI TETAP',
            'TENAGA KERJA WNI LAKI-LAKI TIDAK TETAP',
            'TENAGA KERJA WNI LAKI-LAKI KELUARGA',
            'TENAGA KERJA WNI PEREMPUAN TETAP',
            'TENAGA KERJA WNI PEREMPUAN TIDAK TETAP',
            'TENAGA KERJA WNI PEREMPUAN KELUARGA',
            'TENAGA KERJA WNA LAKI-LAKI TETAP',
            'TENAGA KERJA WNA LAKI-LAKI TIDAK TETAP',
            'TENAGA KERJA WNA LAKI-LAKI KELUARGA',
            'TENAGA KERJA WNA PEREMPUAN TETAP',
            'TENAGA KERJA WNA PEREMPUAN TIDAK TETAP',
            'TENAGA KERJA WNA PEREMPUAN KELUARGA',
            'LAMPIRAN FILES',
        ];
    }

    public function map($pengolah): array
    {
        static $no = 0;
        static $previousNIK = null;
        $no++;

        // Cek apakah NIK ini berbeda dari sebelumnya
        $isNewNIK = ($previousNIK !== $pengolah->nik_pengolah);
        $previousNIK = $pengolah->nik_pengolah;

        // Detail Produksi - Lengkap
        $produksiDetail = collect($pengolah->detail_produksi ?? [])->map(function($p) {
            $bulanList = is_array($p['bulan_produksi'] ?? null) ? implode(', ', $p['bulan_produksi']) : '-';
            $sertifikatList = is_array($p['sertifikat_lahan'] ?? null) ? implode(', ', $p['sertifikat_lahan']) : '-';
            $komoditasList = $p['komoditas'] ?? ($p['jenis_ikan'] ?? '-');
            if (is_array($komoditasList)) {
                $komoditasList = implode(', ', array_filter(array_map('strval', $komoditasList)));
            }
            if ($komoditasList === '' || $komoditasList === null) {
                $komoditasList = '-';
            }
            
            // Bahan Baku Detail
            $bahanBakuDetail = '';
            if (isset($p['bahan_baku']) && is_array($p['bahan_baku']) && count($p['bahan_baku']) > 0) {
                $bahanBakuDetail = collect($p['bahan_baku'])->map(function($b, $idx) {
                    return sprintf(
                        '[%d: %s dari %s, Rp %s, %s kg]',
                        $idx + 1,
                        $b['bahan'] ?? '-',
                        $b['asal'] ?? '-',
                        isset($b['harga']) ? number_format($b['harga'], 0, ',', '.') : '-',
                        isset($b['qty']) ? number_format($b['qty'], 2, ',', '.') : '-'
                    );
                })->implode(', ');
            } else {
                $bahanBakuDetail = '-';
            }
            
            $qty = floatval($p['jumlah_produk_qty'] ?? 0);
            $pack = floatval($p['jumlah_produk_pack'] ?? 0);
            $hasilProduksiKg = floatval($p['harga_produksi_qty'] ?? $p['hasil_produksi_qty'] ?? 0);
            
            return implode(' | ', [
                'Nama Merk: ' . ($p['nama_merk'] ?? '-'),
                'Komoditas: ' . $komoditasList,
                'Kapasitas: ' . (isset($p['kapasitas_terpasang']) ? number_format($p['kapasitas_terpasang'], 2, ',', '.') . ' Kg' : '-'),
                'Hari Produksi: ' . ($p['jumlah_hari_produksi'] ?? '-') . ' hari',
                'Bulan: ' . $bulanList,
                'Sertifikat: ' . $sertifikatList,
                'Biaya Produksi: Rp ' . (isset($p['biaya_produksi']) ? number_format($p['biaya_produksi'], 0, ',', '.') : '-'),
                'Biaya Lain: Rp ' . (isset($p['biaya_lain']) ? number_format($p['biaya_lain'], 0, ',', '.') : '-'),
                'Harga Jual: Rp ' . (isset($p['harga_jual']) ? number_format($p['harga_jual'], 0, ',', '.') : '-'),
                'Hasil Produksi: ' . (isset($p['harga_produksi_qty']) ? number_format($p['harga_produksi_qty'], 2, ',', '.') . ' Kg' : '-') . ' - Rp ' . (isset($p['harga_produksi_harga']) ? number_format($p['harga_produksi_harga'], 0, ',', '.') : '-'),
                'Pemasaran: ' . ($p['pemasaran'] ?? '-'),
                'Harga Jual/Pack: Rp ' . (isset($p['harga_jual_pack']) ? number_format($p['harga_jual_pack'], 0, ',', '.') : '-'),
                'Qty×Pack: ' . number_format($qty, 2, ',', '.') . ' × ' . number_format($pack, 2, ',', '.'),
                'Total Produksi (Hasil Produksi): ' . number_format($hasilProduksiKg, 2, ',', '.') . ' kg',
                'Bahan Baku: ' . $bahanBakuDetail
            ]);
        })->implode(' ;; ');

        // Tenaga Kerja
        $tenagaKerjaData = $pengolah->tenaga_kerja_data ?? [];
        $tkWniLakiTetap = 0;
        $tkWniLakiTidakTetap = 0;
        $tkWniLakiKeluarga = 0;
        $tkWniPerempuanTetap = 0;
        $tkWniPerempuanTidakTetap = 0;
        $tkWniPerempuanKeluarga = 0;
        $tkWnaLakiTetap = 0;
        $tkWnaLakiTidakTetap = 0;
        $tkWnaLakiKeluarga = 0;
        $tkWnaPerempuanTetap = 0;
        $tkWnaPerempuanTidakTetap = 0;
        $tkWnaPerempuanKeluarga = 0;

        // Data tenaga kerja disimpan sebagai object, bukan array of objects
        if (is_array($tenagaKerjaData) && !empty($tenagaKerjaData)) {
            $tkWniLakiTetap = intval($tenagaKerjaData['wni_laki_tetap'] ?? 0);
            $tkWniLakiTidakTetap = intval($tenagaKerjaData['wni_laki_tidak_tetap'] ?? 0);
            $tkWniLakiKeluarga = intval($tenagaKerjaData['wni_laki_keluarga'] ?? 0);
            $tkWniPerempuanTetap = intval($tenagaKerjaData['wni_perempuan_tetap'] ?? 0);
            $tkWniPerempuanTidakTetap = intval($tenagaKerjaData['wni_perempuan_tidak_tetap'] ?? 0);
            $tkWniPerempuanKeluarga = intval($tenagaKerjaData['wni_perempuan_keluarga'] ?? 0);
            $tkWnaLakiTetap = intval($tenagaKerjaData['wna_laki_tetap'] ?? 0);
            $tkWnaLakiTidakTetap = intval($tenagaKerjaData['wna_laki_tidak_tetap'] ?? 0);
            $tkWnaLakiKeluarga = intval($tenagaKerjaData['wna_laki_keluarga'] ?? 0);
            $tkWnaPerempuanTetap = intval($tenagaKerjaData['wna_perempuan_tetap'] ?? 0);
            $tkWnaPerempuanTidakTetap = intval($tenagaKerjaData['wna_perempuan_tidak_tetap'] ?? 0);
            $tkWnaPerempuanKeluarga = intval($tenagaKerjaData['wna_perempuan_keluarga'] ?? 0);
        }

        // Lampiran files
        $lampiranKeys = ['foto_ktp','foto_sertifikat','foto_cpib_cbib','foto_unit_usaha','foto_kusuka','foto_nib','foto_sertifikat_pirt','foto_sertifikat_halal'];
        $lampiranFiles = [];
        foreach($lampiranKeys as $k){
            if (!empty($pengolah->{$k})) {
                $lampiranFiles[] = $pengolah->{$k};
            }
        }

        return [
            $no,
            $isNewNIK ? ($pengolah->nama_lengkap ?? '-') : '',
            $isNewNIK ? ($pengolah->nik_pengolah ?? '-') : '',
            $pengolah->tahun_pendataan ?? '-',
            $isNewNIK ? ($pengolah->jenis_kelamin ?? '-') : '',
            $isNewNIK ? ($pengolah->tempat_lahir ?? '-') : '',
            $isNewNIK ? ($pengolah->tanggal_lahir ? Carbon::parse($pengolah->tanggal_lahir)->format('d-m-Y') : '-') : '',
            $isNewNIK ? ($pengolah->pendidikan_terakhir ?? '-') : '',
            $isNewNIK ? ($pengolah->status_perkawinan ?? '-') : '',
            $isNewNIK ? ($pengolah->jumlah_tanggungan ?? '-') : '',
            $isNewNIK ? ($pengolah->aset_pribadi ? number_format($pengolah->aset_pribadi, 0, ',', '.') : '-') : '',
            $isNewNIK ? ($pengolah->alamat ?? '-') : '',
            $isNewNIK ? (optional($pengolah->kecamatan)->nama_kecamatan ?? '-') : '',
            $isNewNIK ? (optional($pengolah->desa)->nama_desa ?? '-') : '',
            $isNewNIK ? ($pengolah->kontak ?? '-') : '',
            $isNewNIK ? ($pengolah->email ?? '-') : '',
            $isNewNIK ? ($pengolah->no_npwp ?? '-') : '',
            $pengolah->nama_usaha ?? '-',
            $pengolah->nama_kelompok ?? '-',
            $pengolah->tahun_mulai_usaha ?? '-',
            $pengolah->status_usaha ?? '-',
            $pengolah->skala_usaha ?? '-',
            optional($pengolah->kecamatanUsaha)->nama_kecamatan ?? '-',
            optional($pengolah->desaUsaha)->nama_desa ?? '-',
            $pengolah->alamat_usaha ?? '-',
            $pengolah->latitude ?? '-',
            $pengolah->longitude ?? '-',
            $pengolah->jenis_kegiatan_usaha ?? '-',
            $pengolah->komoditas ?? '-',
            $pengolah->nib ?? '-',
            $pengolah->npwp_usaha ?? '-',
            $pengolah->kusuka ?? '-',
            $pengolah->pengesahan_menkumham ?? '-',
            $pengolah->tdu_php ?? '-',
            $pengolah->akta_pendirian_usaha ?? '-',
            $pengolah->imb ?? '-',
            $pengolah->siup_perikanan ?? '-',
            $pengolah->siup_perdagangan ?? '-',
            $pengolah->sppl ?? '-',
            $pengolah->ukl_upl ?? '-',
            $pengolah->amdal ?? '-',
            count($pengolah->detail_produksi ?? []),
            $produksiDetail ?: '-',
            number_format($pengolah->total_produksi ?? 0, 2, ',', '.'),
            $tkWniLakiTetap,
            $tkWniLakiTidakTetap,
            $tkWniLakiKeluarga,
            $tkWniPerempuanTetap,
            $tkWniPerempuanTidakTetap,
            $tkWniPerempuanKeluarga,
            $tkWnaLakiTetap,
            $tkWnaLakiTidakTetap,
            $tkWnaLakiKeluarga,
            $tkWnaPerempuanTetap,
            $tkWnaPerempuanTidakTetap,
            $tkWnaPerempuanKeluarga,
            implode(' ; ', $lampiranFiles) ?: '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4299E1']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
            ],
        ];
    }
}
