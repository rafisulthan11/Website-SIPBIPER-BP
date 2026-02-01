<?php

namespace App\Exports;

use App\Models\HargaIkanSegar;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class HargaIkanSegarExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = HargaIkanSegar::with(['kecamatan', 'desa']);

        // Apply filters
        if (!empty($this->filters['kecamatan'])) {
            $query->where('id_kecamatan', $this->filters['kecamatan']);
        }

        if (!empty($this->filters['jenis_ikan'])) {
            $query->where('jenis_ikan', 'like', '%' . $this->filters['jenis_ikan'] . '%');
        }

        if (!empty($this->filters['nama_pasar'])) {
            $query->where('nama_pasar', 'like', '%' . $this->filters['nama_pasar'] . '%');
        }

        if (!empty($this->filters['bulan'])) {
            $query->whereMonth('tanggal_input', $this->filters['bulan']);
        }

        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('nama_pasar', 'like', '%'.$search.'%')
                  ->orWhere('nama_pedagang', 'like', '%'.$search.'%')
                  ->orWhere('jenis_ikan', 'like', '%'.$search.'%');
            });
        }

        // filter single row when id provided
        if (!empty($this->filters['id'])) {
            $query->where('id_harga', $this->filters['id']);
        }

        return $query->orderBy('tanggal_input', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'NO',
            'TANGGAL INPUT',
            'NAMA PASAR',
            'NAMA PEDAGANG',
            'KECAMATAN',
            'DESA',
            'ASAL IKAN',
            'JENIS IKAN',
            'UKURAN',
            'SATUAN',
            'HARGA PRODUSEN (Rp)',
            'HARGA KONSUMEN (Rp)',
            'KUANTITAS PERMINGGU',
            'KETERANGAN',
            'TANGGAL DIBUAT'
        ];
    }

    public function map($item): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $item->tanggal_input ? Carbon::parse($item->tanggal_input)->format('d/m/Y') : '-',
            $item->nama_pasar ?? '-',
            $item->nama_pedagang ?? '-',
            optional($item->kecamatan)->nama_kecamatan ?? '-',
            optional($item->desa)->nama_desa ?? '-',
            $item->asal_ikan ?? '-',
            $item->jenis_ikan ?? '-',
            $item->ukuran ?? '-',
            $item->satuan ?? '-',
            $item->harga_produsen ? number_format($item->harga_produsen, 0, ',', '.') : '-',
            $item->harga_konsumen ? number_format($item->harga_konsumen, 0, ',', '.') : '-',
            $item->kuantitas_perminggu ?? '-',
            $item->keterangan ?? '-',
            $item->created_at ? Carbon::parse($item->created_at)->toDateTimeString() : '-',
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
            'B' => 15,
            'C' => 25,
            'D' => 25,
            'E' => 20,
            'F' => 20,
            'G' => 20,
            'H' => 15,
            'I' => 10,
        ];
    }
}
