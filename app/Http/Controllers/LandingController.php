<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembudidaya;
use App\Models\Pengolah;
use App\Models\Pemasar;
use App\Models\HargaIkanSegar;

class LandingController extends Controller
{
    public function index()
    {
        // Get statistics
        $totalPembudidaya = Pembudidaya::count();
        $totalPengolah = Pengolah::count();
        $totalPemasar = Pemasar::count();
        $totalHargaIkan = HargaIkanSegar::count();
        
        return view('welcome', compact(
            'totalPembudidaya',
            'totalPengolah',
            'totalPemasar',
            'totalHargaIkan'
        ));
    }
}
