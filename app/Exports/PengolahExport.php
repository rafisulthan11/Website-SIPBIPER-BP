<?php

namespace App\Exports;

use App\Models\Pengolah;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PengolahExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Pengolah::with(['kecamatan', 'desa', 'kecamatanUsaha', 'desaUsaha']);

        if (!empty($this->filters['id'])) {
            $query->where('id_pengolah', $this->filters['id']);
        }

        // Apply filters
        if (!empty($this->filters['kecamatan'])) {
            $query->where('id_kecamatan', $this->filters['kecamatan']);
        }

        if (!empty($this->filters['komoditas'])) {
            $query->where('jenis_kegiatan_usaha', 'like', '%' . $this->filters['komoditas'] . '%');
        }

        if (!empty($this->filters['kategori'])) {
            $query->where('jenis_pengolahan', $this->filters['kategori']);
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
            'PENDIDIKAN TERAKHIR',
            'STATUS PERKAWINAN',
            'JUMLAH TANGGUNGAN',
            'ALAMAT LENGKAP',
            'KECAMATAN',
            'DESA',
            'NO TELEPON/HP',
            'EMAIL',
            'NO NPWP',
            'NAMA USAHA',
            'NAMA KELOMPOK',
            'TAHUN MULAI USAHA',
            'KECAMATAN USAHA',
            'DESA USAHA',
            'LATITUDE',
            'LONGITUDE',
            'ALAMAT LENGKAP USAHA',
            'JENIS KEGIATAN USAHA',
            'JENIS PENGOLAHAN',
            'PRODUK_COUNT',
            'PRODUK_SUMMARY',
            'PRODUK_BAHAN_SUMMARY',
            'PRODUK_BIAYA',
            'PRODUK_HARGA_JUAL',
            'PRODUK_JUMLAH_PRODUK',
            'TENAGA_KERJA_SUMMARY',
            'LAMPIRAN_FILES'
        ];
    }

    public function map($pengolah): array
    {
        static $no = 0;
        $no++;
        $produkSummary = [];
        $bahanSummary = [];
        $produksiCount = 0;

        if ($pengolah->produksi_data && is_array($pengolah->produksi_data)) {
            $produksiCount = count($pengolah->produksi_data);
            foreach($pengolah->produksi_data as $pIndex => $produksi) {
                $parts = [];
                $parts[] = 'Nama Merk: ' . ($produksi['nama_merk'] ?? '-');
                $parts[] = 'Periode: ' . ($produksi['periode'] ?? '-');
                $parts[] = 'Kapasitas Terpasang: ' . (isset($produksi['kapasitas_terpasang']) ? number_format($produksi['kapasitas_terpasang'],2) . ' Kg' : '-');
                $parts[] = 'Biaya Produksi: Rp ' . (isset($produksi['biaya_produksi']) ? number_format($produksi['biaya_produksi'],0,',','.') : '-');
                $parts[] = 'Harga Jual: Rp ' . (isset($produksi['harga_jual']) ? number_format($produksi['harga_jual'],0,',','.') : '-');
                $parts[] = 'Jumlah Produk: ' . (isset($produksi['jumlah_produk_qty']) ? number_format($produksi['jumlah_produk_qty'],2) . ' Kg' : '-') . ' - ' . ($produksi['jumlah_produk_pack'] ?? '-');
                $produkSummary[] = implode(' | ', $parts);

                if (isset($produksi['bahan_baku']) && is_array($produksi['bahan_baku'])) {
                    foreach($produksi['bahan_baku'] as $b) {
                        $bahanSummary[] = implode(' | ', [
                            $b['bahan'] ?? '-',
                            $b['asal'] ?? '-',
                            isset($b['harga']) ? 'Rp ' . number_format($b['harga'],0,',','.') : '-',
                            isset($b['qty']) ? number_format($b['qty'],2) . ' kg' : '-'
                        ]);
                    }
                }
            }
        }

        // tenaga kerja summary (pengolah uses tenaga_kerja_data array)
        $tkSummary = '-';
        if ($pengolah->tenaga_kerja_data && is_array($pengolah->tenaga_kerja_data)) {
            $tkSummary = json_encode($pengolah->tenaga_kerja_data, JSON_UNESCAPED_UNICODE);
        }

        $lampiranKeys = ['foto_ktp','foto_sertifikat','foto_cpib_cbib','foto_unit_usaha','foto_kusuka','foto_nib'];
        $lampiranFiles = [];
        foreach($lampiranKeys as $k){
            if (!empty($pengolah->{$k})) {
                $lampiranFiles[] = $pengolah->{$k};
            }
        }

        return [
            $no,
            $pengolah->nama_lengkap ?? '-',
            $pengolah->nik_pengolah ?? ($pengolah->nik ?? '-'),
            $pengolah->jenis_kelamin ?? '-',
            $pengolah->tempat_lahir ?? '-',
            $pengolah->tanggal_lahir ? Carbon::parse($pengolah->tanggal_lahir)->format('d-m-Y') : '-',
            $pengolah->pendidikan_terakhir ?? '-',
            $pengolah->status_perkawinan ?? '-',
            $pengolah->jumlah_tanggungan ?? '-',
            $pengolah->alamat ?? '-',
            optional($pengolah->kecamatan)->nama_kecamatan ?? '-',
            optional($pengolah->desa)->nama_desa ?? '-',
            $pengolah->kontak ?? '-',
            $pengolah->email ?? '-',
            $pengolah->no_npwp ?? '-',
            $pengolah->nama_usaha ?? '-',
            $pengolah->nama_kelompok ?? '-',
            $pengolah->tahun_mulai_usaha ?? '-',
            optional($pengolah->kecamatanUsaha)->nama_kecamatan ?? '-',
            optional($pengolah->desaUsaha)->nama_desa ?? '-',
            $pengolah->latitude ?? '-',
            $pengolah->longitude ?? '-',
            $pengolah->alamat_usaha ?? '-',
            $pengolah->jenis_kegiatan_usaha ?? '-',
            $pengolah->jenis_pengolahan ?? '-',
            $produksiCount,
            implode(' || ', $produkSummary) ?: '-',
            implode(' || ', $bahanSummary) ?: '-',
            // aggregate biaya/harga from first produk if exists
            ($pengolah->produksi_data[0]['biaya_produksi'] ?? null) ? 'Rp ' . number_format($pengolah->produksi_data[0]['biaya_produksi'],0,',','.') : '-',
            ($pengolah->produksi_data[0]['harga_jual'] ?? null) ? 'Rp ' . number_format($pengolah->produksi_data[0]['harga_jual'],0,',','.') : '-',
            isset($pengolah->produksi_data[0]['jumlah_produk_qty']) ? (number_format($pengolah->produksi_data[0]['jumlah_produk_qty'],2) . ' Kg - ' . ($pengolah->produksi_data[0]['jumlah_produk_pack'] ?? '-')) : '-',
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
