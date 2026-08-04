<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ajuan;
use App\Models\AjuanBpd;
use App\Models\BimtekInformasi;
use App\Models\Bpd;
use App\Models\PengajuanPembinaan;
use App\Models\PerangkatDesa;
use App\Models\PjKades;
use App\Models\Regulasi;
use App\Models\RencanaP3d;

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

        $recentPjkades = PjKades::withoutGlobalScopes()->latest('updated_at')->take(3)->get()->map(function ($item) {
            return (object) [
                'icon' => 'person',
                'title' => 'Usulan SK Kades',
                'status' => $item->status,
                'admin' => $item->desa->nama_desa ?? 'Admin',
                'date' => $item->updated_at,
            ];
        });

        $recentAjuan = Ajuan::latest('updated_at')->take(3)->get()->map(function ($item) {
            return (object) [
                'icon' => 'approval',
                'title' => 'e-Rekomendasi',
                'status' => $item->status,
                'admin' => $item->desa->nama_desa ?? 'Admin',
                'date' => $item->updated_at,
            ];
        });

        $recentRegulasi = Regulasi::latest('updated_at')->take(3)->get()->map(function ($item) {
            return (object) [
                'icon' => 'gavel',
                'title' => 'Draft Regulasi',
                'status' => $item->status ?? 'draft',
                'admin' => $item->desa->nama_desa ?? 'Admin',
                'date' => $item->updated_at,
            ];
        });

        $aktivitas = $aktivitas->concat($recentPjkades)->concat($recentAjuan)->concat($recentRegulasi)
            ->sortByDesc('date')
            ->take(5);

        $berita = BimtekInformasi::latest('created_at')->take(4)->get();

        return view('admin.dashboard', compact('berita', 'counts', 'aktivitas'));
    }
}
