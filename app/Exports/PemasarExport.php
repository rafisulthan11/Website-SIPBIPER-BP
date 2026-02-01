<?php

namespace App\Exports;

use App\Models\Pemasar;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PemasarExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Pemasar::with(['kecamatan', 'desa', 'kecamatanUsaha', 'desaUsaha']);

        // Apply filters
        if (!empty($this->filters['kecamatan'])) {
            $query->where('id_kecamatan', $this->filters['kecamatan']);
        }

        if (!empty($this->filters['komoditas'])) {
            $query->where('jenis_kegiatan_usaha', 'like', '%' . $this->filters['komoditas'] . '%');
        }

        if (!empty($this->filters['kategori'])) {
            $query->where('jenis_pemasaran', $this->filters['kategori']);
        }

        if (!empty($this->filters['bulan'])) {
            $query->whereMonth('created_at', $this->filters['bulan']);
        }

        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%'.$search.'%')
                  ->orWhere('nama_usaha', 'like', '%'.$search.'%')
                  ->orWhere('jenis_kegiatan_usaha', 'like', '%'.$search.'%');
            });
        }

        // if exporting a single row, filter by id
        if (!empty($this->filters['id'])) {
            $query->where('id_pemasar', $this->filters['id']);
        }

        return $query->orderBy('nama_lengkap')->get();
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA LENGKAP',
            'NAMA KELOMPOK',
            'NIK',
            'TEMPAT LAHIR',
            'TANGGAL LAHIR',
            'JENIS KELAMIN',
            'PENDIDIKAN TERAKHIR',
            'STATUS PERKAWINAN',
            'TAHUN MULAI USAHA',
            'ASET PRIBADI',
            'JUMLAH TANGGUNGAN',
            'ALAMAT LENGKAP',
            'KECAMATAN',
            'DESA',
            'NO TELEPON/HP',
            'EMAIL',
            'NO NPWP',
            // usaha
            'NAMA USAHA',
            'NAMA KELOMPOK (USAHA)',
            'NPWP USAHA',
            'NO TELP USAHA',
            'EMAIL USAHA',
            'SKALA USAHA',
            'STATUS USAHA',
            'TAHUN MULAI USAHA (US)',
            'KOMODITAS (USAHAT)',
            // lokasi usaha
            'KECAMATAN USAHA',
            'DESA USAHA',
            'LATITUDE',
            'LONGITUDE',
            'ALAMAT LENGKAP USAHA',
            // izin
            'NIB',
            'NPWP IZIN',
            'KUSUKA',
            'PENGESAHAN MENKUMHAM',
            'TDU/PHP',
            'SPPL',
            'SIUP PERDAGANGAN',
            'AKTA PENDIRI USAHA',
            'IMB',
            'SIUP PERIKANAN',
            'UKL/UPL',
            'AMDAL',
            // investasi (summary)
            'MESIN/PERALATAN (RINGKAS)',
            'INVESTASI TANAH',
            'INVESTASI GEDUNG',
            'INVESTASI MESIN/PERALATAN',
            'INVESTASI KENDARAAN',
            'INVESTASI LAIN-LAIN',
            'INVESTASI SUB JUMLAH',
            'MODAL KERJA 1 BULAN',
            'MODAL KERJA SUB JUMLAH',
            'SUMBER PEMBIAYAAN (SENDIRI/LABA/PINJAM)',
            // sertifikat
            'JENIS SERTIFIKAT LAHAN',
            'LUAS LAHAN',
            'NILAI LAHAN',
            'JENIS SERTIFIKAT BANGUNAN',
            'LUAS BANGUNAN',
            'NILAI BANGUNAN',
            // produksi
            'KAPASITAS TERPASANG SETAHUN',
            'JUMLAH HARI PRODUKSI/BULAN',
            'BULAN PRODUKSI',
            'DISTRIBUSI PEMASARAN',
            // tenaga kerja (compact)
            'TENAGA KERJA SUMMARY',
            // lampiran
            'LAMPIRAN FILES',
            'TANGGAL DIBUAT'
        ];
    }

    public function map($pemasar): array
    {
        static $no = 0;
        $no++;

        // mesin/peralatan summary
        $mesinSummary = '-';
        if ($pemasar->mesin_peralatan) {
            $arr = json_decode($pemasar->mesin_peralatan, true);
            if (is_array($arr) && count($arr) > 0) {
                $lines = [];
                foreach ($arr as $idx => $m) {
                    $lines[] = ($m['jenis_mesin'] ?? '-') . ' (kap:' . ($m['kapasitas'] ?? '-') . ', jml:' . ($m['jumlah'] ?? '-') . ')';
                }
                $mesinSummary = implode(' | ', $lines);
            }
        }

        // bulan produksi
        $bulanProduksi = '-';
        if ($pemasar->bulan_produksi) {
            $bp = json_decode($pemasar->bulan_produksi, true);
            if (is_array($bp)) {
                $bulanProduksi = implode(', ', $bp);
            }
        }

        // tenaga kerja compact
        $tk = [
            'wni_laki_tetap' => $pemasar->wni_laki_tetap ?? 0,
            'wni_laki_tidak_tetap' => $pemasar->wni_laki_tidak_tetap ?? 0,
            'wni_laki_keluarga' => $pemasar->wni_laki_keluarga ?? 0,
            'wni_perempuan_tetap' => $pemasar->wni_perempuan_tetap ?? 0,
            'wni_perempuan_tidak_tetap' => $pemasar->wni_perempuan_tidak_tetap ?? 0,
            'wni_perempuan_keluarga' => $pemasar->wni_perempuan_keluarga ?? 0,
            'wna_laki_tetap' => $pemasar->wna_laki_tetap ?? 0,
            'wna_laki_tidak_tetap' => $pemasar->wna_laki_tidak_tetap ?? 0,
            'wna_laki_keluarga' => $pemasar->wna_laki_keluarga ?? 0,
            'wna_perempuan_tetap' => $pemasar->wna_perempuan_tetap ?? 0,
            'wna_perempuan_tidak_tetap' => $pemasar->wna_perempuan_tidak_tetap ?? 0,
            'wna_perempuan_keluarga' => $pemasar->wna_perempuan_keluarga ?? 0,
        ];
        $tkSummary = json_encode($tk, JSON_UNESCAPED_UNICODE);

        // lampiran list
        $lampiranKeys = ['foto_ktp','foto_sertifikat','foto_cpib_cbib','foto_unit_usaha','foto_npwp','foto_izin_usaha','foto_produk'];
        $files = [];
        foreach ($lampiranKeys as $k) {
            if (!empty($pemasar->$k)) {
                $files[] = $pemasar->$k;
            }
        }
        $filesStr = count($files) ? implode(' | ', $files) : '-';

        return [
            $no,
            $pemasar->nama_lengkap ?? '-',
            $pemasar->nama_kelompok ?? '-',
            $pemasar->nik_pemasar ?? '-',
            ($pemasar->tempat_lahir ?? '-'),
            $pemasar->tanggal_lahir ? Carbon::parse($pemasar->tanggal_lahir)->translatedFormat('d F Y') : '-',
            $pemasar->jenis_kelamin ?? '-',
            $pemasar->pendidikan_terakhir ?? '-',
            $pemasar->status_perkawinan ?? '-',
            $pemasar->tahun_mulai_usaha ?? '-',
            $pemasar->aset_pribadi ? 'Rp. ' . number_format($pemasar->aset_pribadi, 2, ',', '.') : '-',
            $pemasar->jumlah_tanggungan ?? '-',
            $pemasar->alamat ?? '-',
            optional($pemasar->kecamatan)->nama_kecamatan ?? '-',
            optional($pemasar->desa)->nama_desa ?? '-',
            $pemasar->kontak ?? '-',
            $pemasar->email ?? '-',
            $pemasar->no_npwp ?? '-',

            // usaha
            $pemasar->nama_usaha ?? '-',
            $pemasar->nama_kelompok ?? '-',
            $pemasar->npwp_usaha ?? '-',
            $pemasar->telp_usaha ?? '-',
            $pemasar->email_usaha ?? '-',
            $pemasar->skala_usaha ?? '-',
            $pemasar->status_usaha ?? '-',
            $pemasar->tahun_mulai_usaha ?? '-',
            $pemasar->komoditas ?? '-',

            // lokasi usaha
            optional($pemasar->kecamatanUsaha)->nama_kecamatan ?? '-',
            optional($pemasar->desaUsaha)->nama_desa ?? '-',
            $pemasar->latitude ?? '-',
            $pemasar->longitude ?? '-',
            $pemasar->alamat_usaha ?? '-',

            // izin
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

            // investasi
            $mesinSummary,
            $pemasar->investasi_tanah ? 'Rp. ' . number_format($pemasar->investasi_tanah, 2, ',', '.') : '-',
            $pemasar->investasi_gedung ? 'Rp. ' . number_format($pemasar->investasi_gedung, 2, ',', '.') : '-',
            $pemasar->investasi_mesin_peralatan ? 'Rp. ' . number_format($pemasar->investasi_mesin_peralatan, 2, ',', '.') : '-',
            $pemasar->investasi_kendaraan ? 'Rp. ' . number_format($pemasar->investasi_kendaraan, 2, ',', '.') : '-',
            $pemasar->investasi_lain_lain ? 'Rp. ' . number_format($pemasar->investasi_lain_lain, 2, ',', '.') : '-',
            $pemasar->investasi_sub_jumlah ? 'Rp. ' . number_format($pemasar->investasi_sub_jumlah, 2, ',', '.') : '-',
            $pemasar->modal_kerja_1_bulan ? 'Rp. ' . number_format($pemasar->modal_kerja_1_bulan, 2, ',', '.') : '-',
            $pemasar->modal_kerja_sub_jumlah ? 'Rp. ' . number_format($pemasar->modal_kerja_sub_jumlah, 2, ',', '.') : '-',
            ($pemasar->modal_sendiri || $pemasar->laba_ditanam || $pemasar->modal_pinjam) ?
                ('sendiri:' . ($pemasar->modal_sendiri ? 'Rp. ' . number_format($pemasar->modal_sendiri,2,',','.') : '0')
                . ' | laba:' . ($pemasar->laba_ditanam ? 'Rp. '.number_format($pemasar->laba_ditanam,2,',','.') : '0')
                . ' | pinjam:' . ($pemasar->modal_pinjam ? 'Rp. '.number_format($pemasar->modal_pinjam,2,',','.') : '0')) : '-',

            // sertifikat lahan/bangunan
            ($pemasar->sertifikat_lahan ? (is_array(json_decode($pemasar->sertifikat_lahan, true)) ? implode(', ', json_decode($pemasar->sertifikat_lahan, true)) : $pemasar->sertifikat_lahan) : '-'),
            $pemasar->luas_lahan ? $pemasar->luas_lahan . ' m2' : '-',
            $pemasar->nilai_lahan ? 'Rp. ' . number_format($pemasar->nilai_lahan, 2, ',', '.') : '-',
            ($pemasar->sertifikat_bangunan ? (is_array(json_decode($pemasar->sertifikat_bangunan, true)) ? implode(', ', json_decode($pemasar->sertifikat_bangunan, true)) : $pemasar->sertifikat_bangunan) : '-'),
            $pemasar->luas_bangunan ? $pemasar->luas_bangunan . ' m2' : '-',
            $pemasar->nilai_bangunan ? 'Rp. ' . number_format($pemasar->nilai_bangunan, 2, ',', '.') : '-',

            // produksi
            $pemasar->kapasitas_terpasang_setahun ? $pemasar->kapasitas_terpasang_setahun . ' Kg' : '-',
            $pemasar->jumlah_hari_produksi ? $pemasar->jumlah_hari_produksi . ' hari' : '-',
            $bulanProduksi,
            $pemasar->distribusi_pemasaran ?? '-',

            // tenaga kerja
            $tkSummary,

            // lampiran
            $filesStr,

            // created at
            $pemasar->created_at ? Carbon::parse($pemasar->created_at)->toDateTimeString() : '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 25,
            'C' => 25,
            'D' => 20,
            'E' => 20,
            'F' => 20,
            'G' => 20,
            'H' => 20,
            'I' => 30,
            'J' => 15,
        ];
    }
}
