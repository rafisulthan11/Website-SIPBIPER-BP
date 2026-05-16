<?php

namespace App\Exports;

use App\Models\Pemasar;
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

class RekapitulasiPemasarExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithCustomValueBinder
{
    protected $filters;

    protected function cleanText($value, int $limit = 32000): string
    {
        $text = trim((string) ($value ?? '-'));
        $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/u', '', $text) ?? '';

        if (function_exists('mb_substr')) {
            return mb_substr($text, 0, $limit);
        }

        return substr($text, 0, $limit);
    }

    public function bindValue(Cell $cell, $value)
    {
        if (is_string($value) && preg_match('/^\d{16,}$/', $value)) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }

        return parent::bindValue($cell, $value);
    }

    protected function formatPemasaranSummary($pemasar): string
    {
        $sections = collect();
        if ($pemasar->relationLoaded('pemasaran') && $pemasar->pemasaran->isNotEmpty()) {
            $sections = $pemasar->pemasaran->groupBy(function ($row) {
                return $row->section_index ?? 0;
            });
        }

        if ($sections->isEmpty()) {
            return '-';
        }

        $summary = $sections->map(function ($rows, $sectionIndex) {
            $firstRow = $rows->first();
            $bulanPemasaran = $firstRow->bulan_produksi ?? null;
            if (is_string($bulanPemasaran)) {
                $bulanPemasaran = json_decode($bulanPemasaran, true);
            }

            if (is_array($bulanPemasaran)) {
                $bulanPemasaran = implode(', ', $bulanPemasaran);
            }

            return sprintf(
                '[Data Pemasaran #%d: Kapasitas Terpasang: %s Kg | Hasil Pemasaran (Kg): %s | Hasil Pemasaran (Rp): %s | Bulan Pemasaran: %s | Distribusi Pemasaran: %s]',
                (int) $sectionIndex + 1,
                $firstRow->kapasitas_terpasang ?? '-',
                $firstRow->hasil_produksi_kg ?? '-',
                is_null($firstRow->hasil_produksi_rp) ? '-' : $firstRow->hasil_produksi_rp,
                $bulanPemasaran ?: '-',
                $firstRow->distribusi_pemasaran ?? '-'
            );
        })->implode(' ; ');

        return $this->cleanText($summary);
    }

    protected function formatDetailPemasaran(array $rows): string
    {
        if (count($rows) === 0) {
            return '-';
        }

        $detail = collect($rows)->map(function ($row, $index) {
            $jumlahIkan = data_get($row, 'jumlah_ikan', data_get($row, 'jumlah_volume', '-'));

            return sprintf(
                '[%d: %s | Asal: %s | Jumlah Ikan: %s | Harga Beli: Rp %s | Harga Jual: Rp %s]',
                $index + 1,
                $row['komoditas'] ?? $row['jenis_ikan'] ?? '-',
                $row['asal_ikan'] ?? '-',
                $jumlahIkan === null || $jumlahIkan === '' ? '-' : $jumlahIkan,
                isset($row['harga_beli']) ? number_format($row['harga_beli'], 0, ',', '.') : '-',
                isset($row['harga_jual']) ? number_format($row['harga_jual'], 0, ',', '.') : '-'
            );
        })->implode(' ; ');

        return $this->cleanText($detail);
    }

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Pemasar::with(['kecamatan', 'desa', 'kecamatanUsaha', 'desaUsaha', 'pemasaran'])
            ->where('status', 'verified'); // Hanya data yang sudah diverifikasi

        // Apply filters
        if (!empty($this->filters['kecamatan'])) {
            $query->where('id_kecamatan', $this->filters['kecamatan']);
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

        $backup = DB::table('pemasar_verified_backup as backup')
            ->join('pemasars as current', 'current.id_pemasar', '=', 'backup.id_pemasar')
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

                $pemasaranRows = [];
                if (isset($data['pemasaran']) && is_array($data['pemasaran'])) {
                    $pemasaranRows = $data['pemasaran'];
                    unset($data['pemasaran']);
                }

                $pemasar = new Pemasar();
                $pemasar->forceFill($data);
                $pemasar->exists = true;
                $pemasar->setAttribute('from_backup_snapshot', true);

                if (!empty($relations['kecamatan'])) {
                    $kecamatan = new MasterKecamatan();
                    $kecamatan->forceFill($relations['kecamatan']);
                    $kecamatan->exists = true;
                    $pemasar->setRelation('kecamatan', $kecamatan);
                }
                if (!empty($relations['desa'])) {
                    $desa = new MasterDesa();
                    $desa->forceFill($relations['desa']);
                    $desa->exists = true;
                    $pemasar->setRelation('desa', $desa);
                }
                if (!empty($relations['kecamatanUsaha'])) {
                    $kecamatanUsaha = new MasterKecamatan();
                    $kecamatanUsaha->forceFill($relations['kecamatanUsaha']);
                    $kecamatanUsaha->exists = true;
                    $pemasar->setRelation('kecamatanUsaha', $kecamatanUsaha);
                }
                if (!empty($relations['desaUsaha'])) {
                    $desaUsaha = new MasterDesa();
                    $desaUsaha->forceFill($relations['desaUsaha']);
                    $desaUsaha->exists = true;
                    $pemasar->setRelation('desaUsaha', $desaUsaha);
                }

                if (!empty($pemasaranRows)) {
                    $pemasar->setRelation(
                        'pemasaran',
                        collect($pemasaranRows)->map(function ($item) {
                            $pemasaran = new \App\Models\PemasarPemasaran();
                            $pemasaran->forceFill((array) $item);
                            $pemasaran->exists = true;
                            return $pemasaran;
                        })
                    );
                }

                return $pemasar;
            })
            ->filter(function ($item) {
                if (!$item) {
                    return false;
                }

                if (!empty($this->filters['kecamatan']) && (string) ($item->id_kecamatan ?? '') !== (string) $this->filters['kecamatan']) {
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

        $all = $verified->keyBy('id_pemasar');
        foreach ($backup as $backupItem) {
            $all[$backupItem->id_pemasar] = $backupItem;
        }

        return $all->values()->sortBy('nik_pemasar')->sortBy('tahun_pendataan')->values();
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
            'NPWP USAHA',
            'TELP USAHA',
            'EMAIL USAHA',
            'TAHUN MULAI USAHA',
            'STATUS USAHA',
            'SKALA USAHA',
            'KECAMATAN USAHA',
            'DESA USAHA',
            'ALAMAT LENGKAP USAHA',
            'LATITUDE',
            'LONGITUDE',
            'JENIS KEGIATAN USAHA',
            'IZIN NIB',
            'IZIN NPWP',
            'IZIN KUSUKA',
            'IZIN PENGESAHAN MENKUMHAM',
            'IZIN TDU/PHP',
            'IZIN SPPL',
            'IZIN SIUP PERDAGANGAN',
            'IZIN AKTA PENDIRI',
            'IZIN IMB',
            'IZIN SIUP PERIKANAN',
            'IZIN UKL/UPL',
            'IZIN AMDAL',
            'INVESTASI TANAH',
            'INVESTASI GEDUNG',
            'INVESTASI MESIN PERALATAN',
            'INVESTASI KENDARAAN',
            'INVESTASI LAIN-LAIN',
            'INVESTASI SUB JUMLAH',
            'MODAL KERJA 1 BULAN',
            'MODAL KERJA SUB JUMLAH',
            'MODAL SENDIRI',
            'LABA DITANAM',
            'MODAL PINJAM',
            'SERTIFIKAT LAHAN',
            'LUAS LAHAN (M²)',
            'NILAI LAHAN',
            'SERTIFIKAT BANGUNAN',
            'LUAS BANGUNAN (M²)',
            'NILAI BANGUNAN',
            'MESIN PERALATAN (Jenis | Kapasitas | Jumlah | Asal)',
            'DATA PEMASARAN (kapasitas terpasang | hasil pemasaran (Kg) | hasil pemasaran (Rp) | bulan pemasaran | distribusi pemasaran)',
            'DETAIL PEMASARAN (Komoditas | Asal | Jumlah Ikan | Harga Beli | Harga Jual)',
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
            'TOTAL HARGA JUAL/KG (DATA PEMASARAN)',
            'LAMPIRAN FILES',
        ];
    }

    public function map($pemasar): array
    {
        static $no = 0;
        static $previousNIK = null;
        $no++;

        // Cek apakah NIK ini berbeda dari sebelumnya
        $isNewNIK = ($previousNIK !== $pemasar->nik_pemasar);
        $previousNIK = $pemasar->nik_pemasar;

        // Format Sertifikat Lahan
        $sertifikatLahan = '-';
        if ($pemasar->sertifikat_lahan) {
            $decoded = is_string($pemasar->sertifikat_lahan) ? json_decode($pemasar->sertifikat_lahan, true) : $pemasar->sertifikat_lahan;
            if (is_array($decoded)) {
                $sertifikatLahan = $this->cleanText(implode(', ', $decoded));
            }
        }

        // Format Sertifikat Bangunan
        $sertifikatBangunan = '-';
        if ($pemasar->sertifikat_bangunan) {
            $decoded = is_string($pemasar->sertifikat_bangunan) ? json_decode($pemasar->sertifikat_bangunan, true) : $pemasar->sertifikat_bangunan;
            if (is_array($decoded)) {
                $sertifikatBangunan = $this->cleanText(implode(', ', $decoded));
            }
        }

        // Format Mesin Peralatan Detail
        $mesinPeralatanDetail = '-';
        if ($pemasar->mesin_peralatan) {
            $decoded = is_string($pemasar->mesin_peralatan) ? json_decode($pemasar->mesin_peralatan, true) : $pemasar->mesin_peralatan;
            if (is_array($decoded) && count($decoded) > 0) {
                $mesinPeralatanDetail = collect($decoded)->map(function($m, $idx) {
                    return sprintf(
                        '[%d: %s | Kapasitas: %s | Jumlah: %s | Asal: %s]',
                        $idx + 1,
                        $m['jenis_mesin'] ?? '-',
                        $m['kapasitas'] ?? '-',
                        $m['jumlah'] ?? '-',
                        $m['asal'] ?? '-'
                    );
                })->implode(' ; ');

                $mesinPeralatanDetail = $this->cleanText($mesinPeralatanDetail);
            }
        }

        $dataPemasaranRows = [];
        if (method_exists($pemasar, 'pemasaran') && $pemasar->relationLoaded('pemasaran')) {
            $dataPemasaranRows = $pemasar->pemasaran->toArray();
        } elseif ($pemasar->data_pemasaran) {
            $decoded = is_string($pemasar->data_pemasaran) ? json_decode($pemasar->data_pemasaran, true) : $pemasar->data_pemasaran;
            if (is_array($decoded)) {
                $dataPemasaranRows = $decoded;
            }
        }

        $dataPemasaranSummary = $this->formatPemasaranSummary($pemasar);
        $dataPemasaranDetail = $this->formatDetailPemasaran(is_array($dataPemasaranRows) ? $dataPemasaranRows : []);

        // Lampiran files
        $lampiranKeys = ['foto_ktp','foto_sertifikat','foto_cpib_cbib','foto_unit_usaha','foto_npwp','foto_izin_usaha','foto_produk','foto_sertifikat_pirt','foto_sertifikat_halal'];
        $lampiranFiles = [];
        foreach($lampiranKeys as $k){
            if (!empty($pemasar->{$k})) {
                $lampiranFiles[] = $this->cleanText($pemasar->{$k});
            }
        }

        // Hitung total harga jual dari data_pemasaran
        $totalHargaJual = 0;
        if (is_array($dataPemasaranRows)) {
            foreach ($dataPemasaranRows as $item) {
                $totalHargaJual += floatval($item['harga_jual'] ?? 0);
            }
        }

        return [
            $no,
            $isNewNIK ? ($pemasar->nama_lengkap ?? '-') : '',
            $isNewNIK ? ($pemasar->nik_pemasar ?? '-') : '',
            $pemasar->tahun_pendataan ?? '-',
            $isNewNIK ? ($pemasar->jenis_kelamin ?? '-') : '',
            $isNewNIK ? ($pemasar->tempat_lahir ?? '-') : '',
            $isNewNIK ? ($pemasar->tanggal_lahir ? Carbon::parse($pemasar->tanggal_lahir)->format('d-m-Y') : '-') : '',
            $isNewNIK ? ($pemasar->pendidikan_terakhir ?? '-') : '',
            $isNewNIK ? ($pemasar->status_perkawinan ?? '-') : '',
            $isNewNIK ? ($pemasar->jumlah_tanggungan ?? '-') : '',
            $isNewNIK ? ($pemasar->aset_pribadi ?? '-') : '',
            $isNewNIK ? ($pemasar->alamat ?? '-') : '',
            $isNewNIK ? (optional($pemasar->kecamatan)->nama_kecamatan ?? '-') : '',
            $isNewNIK ? (optional($pemasar->desa)->nama_desa ?? '-') : '',
            $isNewNIK ? ($pemasar->kontak ?? '-') : '',
            $isNewNIK ? ($pemasar->email ?? '-') : '',
            $isNewNIK ? ($pemasar->no_npwp ?? '-') : '',
            $pemasar->nama_usaha ?? '-',
            $pemasar->nama_kelompok ?? '-',
            $pemasar->npwp_usaha ?? '-',
            $pemasar->telp_usaha ?? '-',
            $pemasar->email_usaha ?? '-',
            $pemasar->tahun_mulai_usaha ?? '-',
            $pemasar->status_usaha ?? '-',
            $pemasar->skala_usaha ?? '-',
            optional($pemasar->kecamatanUsaha)->nama_kecamatan ?? '-',
            optional($pemasar->desaUsaha)->nama_desa ?? '-',
            $pemasar->alamat_usaha ?? '-',
            $pemasar->latitude ?? '-',
            $pemasar->longitude ?? '-',
            $pemasar->jenis_kegiatan_usaha ?? '-',
            $pemasar->nib ?? '-',
            $pemasar->npwp_izin ?? '-',
            $pemasar->kusuka ?? '-',
            $pemasar->pengesahan_menkumham ?? '-',
            $pemasar->tdu_php ?? '-',
            $pemasar->sppl ?? '-',
            $pemasar->siup_perdagangan ?? '-',
            $pemasar->akta_pendiri_usaha ?? '-',
            $pemasar->imb ?? '-',
            $pemasar->siup_perikanan ?? '-',
            $pemasar->ukl_upl ?? '-',
            $pemasar->amdal ?? '-',
            is_null($pemasar->investasi_tanah) ? '-' : $pemasar->investasi_tanah,
            is_null($pemasar->investasi_gedung) ? '-' : $pemasar->investasi_gedung,
            is_null($pemasar->investasi_mesin_peralatan) ? '-' : $pemasar->investasi_mesin_peralatan,
            is_null($pemasar->investasi_kendaraan) ? '-' : $pemasar->investasi_kendaraan,
            is_null($pemasar->investasi_lain_lain) ? '-' : $pemasar->investasi_lain_lain,
            is_null($pemasar->investasi_sub_jumlah) ? '-' : $pemasar->investasi_sub_jumlah,
            is_null($pemasar->modal_kerja_1_bulan) ? '-' : $pemasar->modal_kerja_1_bulan,
            is_null($pemasar->modal_kerja_sub_jumlah) ? '-' : $pemasar->modal_kerja_sub_jumlah,
            is_null($pemasar->modal_sendiri) ? '-' : $pemasar->modal_sendiri,
            is_null($pemasar->laba_ditanam) ? '-' : $pemasar->laba_ditanam,
            is_null($pemasar->modal_pinjam) ? '-' : $pemasar->modal_pinjam,
            $sertifikatLahan,
            $pemasar->luas_lahan ?? '-',
            is_null($pemasar->nilai_lahan) ? '-' : $pemasar->nilai_lahan,
            $sertifikatBangunan,
            $pemasar->luas_bangunan ?? '-',
            is_null($pemasar->nilai_bangunan) ? '-' : $pemasar->nilai_bangunan,
            $mesinPeralatanDetail,
            $dataPemasaranSummary,
            $dataPemasaranDetail,
            $pemasar->wni_laki_tetap ?? 0,
            $pemasar->wni_laki_tidak_tetap ?? 0,
            $pemasar->wni_laki_keluarga ?? 0,
            $pemasar->wni_perempuan_tetap ?? 0,
            $pemasar->wni_perempuan_tidak_tetap ?? 0,
            $pemasar->wni_perempuan_keluarga ?? 0,
            $pemasar->wna_laki_tetap ?? 0,
            $pemasar->wna_laki_tidak_tetap ?? 0,
            $pemasar->wna_laki_keluarga ?? 0,
            $pemasar->wna_perempuan_tetap ?? 0,
            $pemasar->wna_perempuan_tidak_tetap ?? 0,
            $pemasar->wna_perempuan_keluarga ?? 0,
            $totalHargaJual > 0 ? $totalHargaJual : '-',
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
                    'startColor' => ['rgb' => '0891B2']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
            ],
        ];
    }
}
