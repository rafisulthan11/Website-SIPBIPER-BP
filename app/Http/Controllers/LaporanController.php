<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembudidaya;
use App\Models\Pengolah;
use App\Models\Pemasar;
use App\Models\HargaIkanSegar;
use App\Models\MasterKecamatan;
use App\Models\Komoditas;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PembudidayaExport;
use App\Exports\PengolahExport;
use App\Exports\PemasarExport;
use App\Exports\HargaIkanSegarExport;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /**
     * Show rekapitulasi pembudidaya with filters and pagination.
     */
    public function rekapitulasiPembudidaya(Request $request)
    {
        $query = Pembudidaya::with(['kecamatan','desa']);

        // filters
        if ($request->filled('kecamatan')) {
            $query->where('id_kecamatan', $request->kecamatan);
        }

        if ($request->filled('komoditas')) {
            $query->where('jenis_kegiatan_usaha', 'like', '%'.$request->komoditas.'%');
        }

        if ($request->filled('kategori')) {
            $query->where('jenis_kegiatan_usaha', $request->kategori);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%'.$search.'%')
                  ->orWhere('nama_usaha', 'like', '%'.$search.'%')
                  ->orWhere('jenis_kegiatan_usaha', 'like', '%'.$search.'%');
            });
        }

        $perPage = (int) $request->input('per_page', 10);
        $pembudidayas = $query->orderBy('nama_lengkap')->paginate($perPage)->withQueryString();

        $kecamatans = MasterKecamatan::orderBy('nama_kecamatan')->get();

        // collect komoditas distinct values for filter
        $komoditas = Komoditas::where('status', 'aktif')
            ->orderBy('nama_komoditas')
            ->pluck('nama_komoditas');

        // collect kategori (jenis_kegiatan_usaha) distinct values for filter
        $kategoris = ['Pembenihan', 'Pembenihan/Pembenih', 'Pembesaran', 'Tambak'];

        return view('pages.laporan.rekapitulasi-pembudidaya', compact('pembudidayas','kecamatans','komoditas','kategoris'));
    }

    /**
     * Rekapitulasi Pengolah.
     */
    public function rekapitulasiPengolah(Request $request)
    {
        $query = Pengolah::with(['kecamatan','desa']);

        // filters
        if ($request->filled('kecamatan')) {
            $query->where('id_kecamatan', $request->kecamatan);
        }

        if ($request->filled('komoditas')) {
            $query->where('komoditas', 'like', '%'.$request->komoditas.'%');
        }

        if ($request->filled('kategori')) {
            $query->where('skala_usaha', $request->kategori);
        }

        if ($request->filled('jenis_kegiatan_usaha')) {
            $query->where('jenis_kegiatan_usaha', $request->jenis_kegiatan_usaha);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%'.$search.'%')
                  ->orWhere('nama_usaha', 'like', '%'.$search.'%')
                  ->orWhere('jenis_kegiatan_usaha', 'like', '%'.$search.'%');
            });
        }

        $perPage = (int) $request->input('per_page', 10);
        $pengolahs = $query->orderBy('nama_lengkap')->paginate($perPage)->withQueryString();

        $kecamatans = MasterKecamatan::orderBy('nama_kecamatan')->get();

        // collect komoditas distinct values for filter
        $komoditas = Komoditas::where('status', 'aktif')
            ->orderBy('nama_komoditas')
            ->pluck('nama_komoditas');

        // collect kategori (jenis_pengolahan) distinct values for filter
        $kategoris = ['Mikro', 'Kecil', 'Menengah', 'Besar'];
        
        // collect jenis_kegiatan_usaha distinct values for filter
        $jenis_kegiatan_usaha_list = ['Pengalengan', 'Pembekuan', 'Penggaraman/Pengeringan', 'Pemindangan', 'Pengasapan/Pemanggangan', 'Fermentasi/Peragian', 'Pereduksian/Ekstraksi', 'Pelumatan Daging/Surimi'];

        return view('pages.laporan.rekapitulasi-pengolah', compact('pengolahs','kecamatans','komoditas','kategoris','jenis_kegiatan_usaha_list'));
    }

    /**
     * Rekapitulasi Pemasar.
     */
    public function rekapitulasiPemasar(Request $request)
    {
        $query = Pemasar::with(['kecamatan','desa']);

        // filters
        if ($request->filled('kecamatan')) {
            $query->where('id_kecamatan', $request->kecamatan);
        }

        if ($request->filled('komoditas')) {
            $query->where('komoditas', 'like', '%'.$request->komoditas.'%');
        }

        if ($request->filled('kategori')) {
            $query->where('skala_usaha', $request->kategori);
        }

        if ($request->filled('jenis_kegiatan_usaha')) {
            $query->where('jenis_kegiatan_usaha', $request->jenis_kegiatan_usaha);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%'.$search.'%')
                  ->orWhere('nama_usaha', 'like', '%'.$search.'%')
                  ->orWhere('jenis_kegiatan_usaha', 'like', '%'.$search.'%');
            });
        }

        $perPage = (int) $request->input('per_page', 10);
        $pemasars = $query->orderBy('nama_lengkap')->paginate($perPage)->withQueryString();

        $kecamatans = MasterKecamatan::orderBy('nama_kecamatan')->get();

        // collect komoditas distinct values for filter
        $komoditas = Komoditas::where('status', 'aktif')
            ->orderBy('nama_komoditas')
            ->pluck('nama_komoditas');

        // collect kategori (jenis_pemasaran) distinct values for filter
        $kategoris = ['Mikro', 'Kecil', 'Menengah', 'Besar'];
        
        // collect jenis_kegiatan_usaha distinct values for filter
        $jenis_kegiatan_usaha_list = ['Pemasar Ikan Segar Pengecer', 'Pemasar Ikan Segar Pedagang Besar', 'Pemasar Ikan Pindang/Asap', 'Pemasar Ikan Hias', 'Pemasar Ikan Asin'];

        return view('pages.laporan.rekapitulasi-pemasar', compact('pemasars','kecamatans','komoditas','kategoris','jenis_kegiatan_usaha_list'));
    }

    /**
     * Rekap Harga Ikan Segar.
     */
    public function rekapHargaIkanSegar(Request $request)
    {
        $query = HargaIkanSegar::with(['kecamatan','desa']);

        // filters
        if ($request->filled('kecamatan')) {
            $query->where('id_kecamatan', $request->kecamatan);
        }

        if ($request->filled('jenis_ikan')) {
            $query->where('jenis_ikan', 'like', '%'.$request->jenis_ikan.'%');
        }

        if ($request->filled('nama_pasar')) {
            $query->where('nama_pasar', 'like', '%'.$request->nama_pasar.'%');
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_input', $request->bulan);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_pasar', 'like', '%'.$search.'%')
                  ->orWhere('nama_pedagang', 'like', '%'.$search.'%')
                  ->orWhere('jenis_ikan', 'like', '%'.$search.'%');
            });
        }

        $perPage = (int) $request->input('per_page', 10);
        $items = $query->orderBy('tanggal_input', 'desc')->paginate($perPage)->withQueryString();

        $kecamatans = MasterKecamatan::orderBy('nama_kecamatan')->get();

        // collect jenis_ikan distinct values for filter
        $jenisIkans = HargaIkanSegar::select('jenis_ikan')
            ->whereNotNull('jenis_ikan')
            ->groupBy('jenis_ikan')
            ->orderBy('jenis_ikan')
            ->pluck('jenis_ikan');

        // collect nama_pasar distinct values for filter
        $namaPasars = HargaIkanSegar::select('nama_pasar')
            ->whereNotNull('nama_pasar')
            ->groupBy('nama_pasar')
            ->orderBy('nama_pasar')
            ->pluck('nama_pasar');

        return view('pages.laporan.rekap_harga_ikan_segar', compact('items','kecamatans','jenisIkans','namaPasars'));
    }

    /**
     * Export Pembudidaya to Excel.
     */
    public function exportPembudidaya(Request $request)
    {
        $filters = $request->only(['kecamatan', 'komoditas', 'kategori', 'bulan', 'search', 'id']);
        $filename = 'Rekapitulasi_Pembudidaya_' . date('Y-m-d_His') . '.xlsx';
        
        return Excel::download(new PembudidayaExport($filters), $filename);
    }

    /**
     * Export Pengolah to Excel.
     */
    public function exportPengolah(Request $request)
    {
        $filters = $request->only(['kecamatan', 'komoditas', 'kategori', 'bulan', 'search', 'id']);
        $filename = 'Rekapitulasi_Pengolah_' . date('Y-m-d_His') . '.xlsx';
        
        return Excel::download(new PengolahExport($filters), $filename);
    }

    /**
     * Export Pemasar to Excel.
     */
    public function exportPemasar(Request $request)
    {
        $filters = $request->only(['kecamatan', 'komoditas', 'kategori', 'bulan', 'search', 'id']);
        $filename = 'Rekapitulasi_Pemasar_' . date('Y-m-d_His') . '.xlsx';
        
        return Excel::download(new PemasarExport($filters), $filename);
    }

    /**
     * Export Harga Ikan Segar to Excel.
     */
    public function exportHargaIkanSegar(Request $request)
    {
        $filters = $request->only(['kecamatan', 'jenis_ikan', 'nama_pasar', 'bulan', 'search', 'id']);
        $filename = 'Rekap_Harga_Ikan_Segar_' . date('Y-m-d_His') . '.xlsx';
        
        return Excel::download(new HargaIkanSegarExport($filters), $filename);
    }

    /**
     * Generate PDF detail Pembudidaya.
     */
    public function pdfPembudidaya($id)
    {
        $pembudidaya = Pembudidaya::with(['kecamatan', 'desa'])->findOrFail($id);
        
        $pdf = Pdf::loadView('pages.laporan.pdf.pembudidaya', compact('pembudidaya'));
        $filename = 'Detail_Pembudidaya_' . $pembudidaya->nama_lengkap . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Generate PDF detail Pengolah.
     */
    public function pdfPengolah($id)
    {
        $pengolah = Pengolah::with(['kecamatan', 'desa'])->findOrFail($id);
        
        $pdf = Pdf::loadView('pages.laporan.pdf.pengolah', compact('pengolah'));
        $filename = 'Detail_Pengolah_' . $pengolah->nama_lengkap . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Generate PDF detail Pemasar.
     */
    public function pdfPemasar($id)
    {
        $pemasar = Pemasar::with(['kecamatan', 'desa'])->findOrFail($id);
        
        $pdf = Pdf::loadView('pages.laporan.pdf.pemasar', compact('pemasar'));
        $filename = 'Detail_Pemasar_' . $pemasar->nama_lengkap . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Generate PDF detail Harga Ikan Segar.
     */
    public function pdfHargaIkanSegar($id)
    {
        $harga = HargaIkanSegar::with(['kecamatan', 'desa'])->findOrFail($id);
        
        $pdf = Pdf::loadView('pages.laporan.pdf.harga-ikan-segar', compact('harga'));
        $filename = 'Detail_Harga_Ikan_' . $harga->jenis_ikan . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }
}
