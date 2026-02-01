<?php

namespace App\Http\Controllers;

use App\Models\Pembudidaya;
use App\Models\Pemasar;
use App\Models\Pengolah;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Count from actual data tables
        $pembudidayaCount = Pembudidaya::count();
        $pemasarCount = Pemasar::count();
        $pengolahCount = Pengolah::count();

        return view('dashboard', compact('pembudidayaCount', 'pemasarCount', 'pengolahCount'));
    }
}
