<?php

namespace App\Exports;

use App\Models\Pembudidaya;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\Request;

class PembudidayaExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Pembudidaya::with(['kecamatan', 'desa', 'kecamatanUsaha', 'desaUsaha', 'izin', 'investasi', 'produksi', 'kolam', 'ikan', 'tenagaKerja']);

        // Apply filters
        if (!empty($this->filters['id'])) {
            $query->where('id_pembudidaya', $this->filters['id']);
        }
        if (!empty($this->filters['kecamatan'])) {
            $query->where('id_kecamatan', $this->filters['kecamatan']);
        }

        if (!empty($this->filters['komoditas'])) {
            $query->where('jenis_kegiatan_usaha', 'like', '%' . $this->filters['komoditas'] . '%');
        }

        if (!empty($this->filters['kategori'])) {
            $query->where('jenis_budidaya', $this->filters['kategori']);
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

        return $query->orderBy('nama_lengkap')->get();
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA LENGKAP',
            'NIK',
            'JENIS KELAMIN',
            'TEMPAT LAHIR',
            'TANGGAL LAHIR',
            'STATUS PERKAWINAN',
            'ALAMAT LENGKAP',
            'KECAMATAN',
            'DESA',
            'NO TELEPON/HP',
            'EMAIL',
            'NO NPWP',
            'NAMA USAHA',
            'NAMA KELOMPOK',
            'NPWP USAHA',
            'NO TELP USAHA',
            'EMAIL USAHA',
            'TAHUN MULAI USAHA',
            'STATUS USAHA',
            'KECAMATAN USAHA',
            'DESA USAHA',
            'LATITUDE USAHA',
            'LONGITUDE USAHA',
            'ALAMAT LENGKAP USAHA',
            'JENIS KEGIATAN USAHA',
            'JENIS BUDIDAYA',
            'IZIN_NIB',
            'IZIN_NPWP',
            'IZIN_KUSUKA',
            'IZIN_PENGESAHAN_MENKUMHAM',
            'IZIN_CBIB',
            'IZIN_SKAI',
            'IZIN_SURAT_PEMBUDIDAYAAN_IKAN',
            'IZIN_AKTA_PENDIRIAN',
            'IZIN_IMB',
            'IZIN_SUP_PERIKANAN',
            'IZIN_SUP_PERDAGANGAN',
            'INV_NILAI_ASSET',
            'INV_LABA_DITANAM',
            'INV_SEWA',
            'INV_PINJAMAN',
            'INV_MODAL_SENDIRI',
            'INV_LAHAN_STATUS',
            'INV_LUAS_M2',
            'INV_NILAI_BANGUNAN',
            'INV_BANGUNAN',
            'INV_SERTIFIKAT',
            'PROD_TOTAL_LUAS_KOLAM',
            'PROD_TOTAL_PRODUKSI',
            'PROD_SATUAN',
            'PROD_HARGA_PER_SATUAN',
            'TOTAL_KOLAM',
            'KOLAM_SUMMARY',
            'TOTAL_36_JUMLAH_IKAN',
            'IKAN_SUMMARY',
            'TENAGA_KERJA_SUMMARY',
            'LAMPIRAN_FILES',
        ];
    }

    public function map($pembudidaya): array
    {
        static $no = 0;
        $no++;
        // helper closures
        $get = fn($key, $fallback = '-') => $pembudidaya->{$key} ?? $fallback;

        $izin = $pembudidaya->izin;
        $inv = $pembudidaya->investasi;
        $prod = $pembudidaya->produksi;

        $kolamSummary = collect($pembudidaya->kolam)->map(function($k) {
            return implode(' | ', [
                $k->jenis_kolam ?? '-',
                $k->ukuran ?? '-',
                $k->jumlah ?? '-',
                $k->komoditas ?? '-'
            ]);
        })->implode(' ; ');

        $ikanSummary = collect($pembudidaya->ikan)->map(function($i) {
            return implode(' | ', [
                $i->jenis_ikan ?? '-',
                $i->jenis_indukan ?? '-',
                $i->jumlah ?? '-',
                $i->asal ?? '-'
            ]);
        })->implode(' ; ');

        $lampiranKeys = ['foto_ktp','foto_sertifikat','foto_cpib_cbib','foto_unit_usaha','foto_kusuka','foto_nib'];
        $lampiranFiles = [];
        foreach($lampiranKeys as $k){
            if (!empty($pembudidaya->{$k})) {
                $lampiranFiles[] = $pembudidaya->{$k};
            }
        }

        $tenaga = $pembudidaya->tenagaKerja;
        $tkSummary = '-';
        if ($tenaga) {
            $tkSummary = json_encode([
                'wni_laki' => [$tenaga->wni_laki_tetap, $tenaga->wni_laki_tidak_tetap, $tenaga->wni_laki_keluarga],
                'wni_perempuan' => [$tenaga->wni_perempuan_tetap, $tenaga->wni_perempuan_tidak_tetap, $tenaga->wni_perempuan_keluarga],
                'wna_laki' => [$tenaga->wna_laki_tetap, $tenaga->wna_laki_tidak_tetap, $tenaga->wna_laki_keluarga],
                'wna_perempuan' => [$tenaga->wna_perempuan_tetap, $tenaga->wna_perempuan_tidak_tetap, $tenaga->wna_perempuan_keluarga],
            ], JSON_UNESCAPED_UNICODE);
        }

        return [
            $no,
            $pembudidaya->nama_lengkap ?? '-',
            $pembudidaya->nik_pembudidaya ?? ($pembudidaya->nik ?? '-'),
            $pembudidaya->jenis_kelamin ?? '-',
            $pembudidaya->tempat_lahir ?? '-',
            $pembudidaya->tanggal_lahir ? Carbon::parse($pembudidaya->tanggal_lahir)->format('d-m-Y') : '-',
            $pembudidaya->status_perkawinan ?? '-',
            $pembudidaya->alamat ?? '-',
            optional($pembudidaya->kecamatan)->nama_kecamatan ?? '-',
            optional($pembudidaya->desa)->nama_desa ?? '-',
            $pembudidaya->kontak ?? ($pembudidaya->no_hp ?? '-'),
            $pembudidaya->email ?? '-',
            $pembudidaya->no_npwp ?? '-',
            $pembudidaya->nama_usaha ?? '-',
            $pembudidaya->nama_kelompok ?? '-',
            $pembudidaya->npwp_usaha ?? '-',
            $pembudidaya->telp_usaha ?? '-',
            $pembudidaya->email_usaha ?? '-',
            $pembudidaya->tahun_mulai_usaha ?? '-',
            $pembudidaya->status_usaha ?? '-',
            optional($pembudidaya->kecamatanUsaha)->nama_kecamatan ?? '-',
            optional($pembudidaya->desaUsaha)->nama_desa ?? '-',
            $pembudidaya->latitude_usaha ?? '-',
            $pembudidaya->longitude_usaha ?? '-',
            $pembudidaya->alamat_lengkap_usaha ?? ($pembudidaya->alamat_usaha ?? '-'),
            $pembudidaya->jenis_kegiatan_usaha ?? '-',
            $pembudidaya->jenis_budidaya ?? '-',
            $izin->nib ?? '-',
            $izin->npwp ?? '-',
            $izin->kusuka ?? '-',
            $izin->pengesahan_menkumham ?? '-',
            $izin->cbib ?? '-',
            $izin->skai ?? '-',
            $izin->surat_ijin_pembudidayaan_ikan ?? '-',
            $izin->akta_pendirian_usaha ?? '-',
            $izin->imb ?? '-',
            $izin->sup_perikanan ?? '-',
            $izin->sup_perdagangan ?? '-',
            $inv->nilai_asset ? number_format($inv->nilai_asset,0,',','.') : '-',
            $inv->laba_ditanam ? number_format($inv->laba_ditanam,0,',','.') : '-',
            $inv->sewa ? number_format($inv->sewa,0,',','.') : '-',
            is_null($inv->pinjaman) ? '-' : ($inv->pinjaman ? 'Ya' : 'Tidak'),
            $inv->modal_sendiri ? number_format($inv->modal_sendiri,0,',','.') : '-',
            (is_array($inv->lahan_status) ? implode(', ', $inv->lahan_status) : (is_string($inv->lahan_status) ? $inv->lahan_status : '-')),
            $inv->luas_m2 ?? '-',
            $inv->nilai_bangunan ? number_format($inv->nilai_bangunan,0,',','.') : '-',
            $inv->bangunan ?? '-',
            $inv->sertifikat ?? '-',
            $prod->total_luas_kolam ?? '-',
            $prod->total_produksi ?? '-',
            $prod->satuan_produksi ?? '-',
            $prod->harga_per_satuan ? number_format($prod->harga_per_satuan,0,',','.') : '-',
            $pembudidaya->kolam->count() ?? 0,
            $kolamSummary ?: '-',
            $pembudidaya->ikan->count() ?? 0,
            $ikanSummary ?: '-',
            $tkSummary,
            implode(' ; ', $lampiranFiles) ?: '-',
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
