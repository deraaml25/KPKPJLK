<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PjKades;
use App\Models\Ajuan;
use App\Models\Regulasi;
use App\Models\PengajuanPembinaan;
use App\Models\RencanaP3d;
use App\Models\PerangkatDesa;
use App\Models\Bpd;
use App\Models\AjuanBpd;

class DashboardController extends Controller
{
    public function index()
    {
        $counts = [
            'regulasi' => Regulasi::count(),
            'pembinaan' => PengajuanPembinaan::count(),
            'ajuan' => Ajuan::count(),
            'pjkades' => PjKades::count(),
            'rencana_p3d' => RencanaP3d::count(),
            'perangkat_desa' => PerangkatDesa::count(),
            'bpd' => Bpd::count(),
            'ajuan_bpd' => AjuanBpd::count(),
        ];

        // Aktivitas Terkini (gabungan dari beberapa model yang sering diupdate)
        $aktivitas = collect();
        
        $recentPjkades = PjKades::withoutGlobalScopes()->latest('updated_at')->take(3)->get()->map(function($item) {
            return (object) [
                'icon' => 'person',
                'title' => 'Usulan SK Kades',
                'status' => $item->status,
                'admin' => $item->desa->nama_desa ?? 'Admin',
                'date' => $item->updated_at
            ];
        });
        
        $recentAjuan = Ajuan::latest('updated_at')->take(3)->get()->map(function($item) {
            return (object) [
                'icon' => 'approval',
                'title' => 'e-Rekomendasi',
                'status' => $item->status,
                'admin' => $item->desa->nama_desa ?? 'Admin',
                'date' => $item->updated_at
            ];
        });

        $recentRegulasi = Regulasi::latest('updated_at')->take(3)->get()->map(function($item) {
            return (object) [
                'icon' => 'gavel',
                'title' => 'Draft Regulasi',
                'status' => $item->status ?? 'draft',
                'admin' => $item->desa->nama_desa ?? 'Admin',
                'date' => $item->updated_at
            ];
        });

        $aktivitas = $aktivitas->concat($recentPjkades)->concat($recentAjuan)->concat($recentRegulasi)
            ->sortByDesc('date')
            ->take(5);

        // Peringatan dini: Pj Kades yang masa jabatannya hampir habis (≤30 hari)
        $pjKadesAlert = PjKades::withoutGlobalScopes()
            ->where('status', 'approved')
            ->whereNotNull('tgl_selesai')
            ->get()
            ->filter(fn($pj) => $pj->hampir_berakhir || $pj->sudah_berakhir);

        return view('admin.dashboard', compact('pjKadesAlert', 'counts', 'aktivitas'));
    }
}
