<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PjKades;
use App\Models\Ajuan;

class DashboardController extends Controller
{
    public function index()
    {
        // Peringatan dini: Pj Kades yang masa jabatannya hampir habis (≤30 hari)
        $pjKadesAlert = PjKades::withoutGlobalScopes()
            ->where('status', 'approved')
            ->whereNotNull('tgl_selesai')
            ->get()
            ->filter(fn($pj) => $pj->hampir_berakhir || $pj->sudah_berakhir);

        return view('admin.dashboard', compact('pjKadesAlert'));
    }
}
