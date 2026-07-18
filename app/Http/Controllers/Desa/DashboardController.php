<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $desaId = auth()->user()->desa_id;

        $totalAjuan = \App\Models\Ajuan::where('desa_id', $desaId)->count();
        $sedangDiproses = \App\Models\Ajuan::where('desa_id', $desaId)
            ->whereNotIn('status', ['draft', 'selesai', 'ditolak'])
            ->count();
        $perluTindakan = \App\Models\Ajuan::where('desa_id', $desaId)
            ->whereIn('status', ['draft', 'revisi'])
            ->count();

        // Ambil 5 ajuan terbaru yang aktif
        $ajuans = \App\Models\Ajuan::with(['jenisLayanan', 'pesertas.perangkatDesa', 'milestoneTrackings'])
            ->where('desa_id', $desaId)
            ->latest()
            ->take(5)
            ->get();

        return view('desa.dashboard', compact('totalAjuan', 'sedangDiproses', 'perluTindakan', 'ajuans'));
    }
}
