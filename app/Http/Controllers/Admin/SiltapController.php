<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PerangkatDesa;
use App\Models\Siltap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiltapController extends Controller
{
    public function index()
    {
        $siltaps = Siltap::with('desa')->latest()->paginate(20);

        $totalMenunggu = Siltap::where('status', 'menunggu_verifikasi')->count();
        $totalDisetujui = Siltap::where('status', 'disetujui')->count();

        return view('admin.siltap.index', compact('siltaps', 'totalMenunggu', 'totalDisetujui'));
    }

    public function show(Siltap $siltap)
    {
        $siltap->load(['desa', 'verifikator']);

        // Ambil jumlah perangkat aktif terkini untuk cross-check
        $perangkatAktifSekarang = PerangkatDesa::where('desa_id', $siltap->desa_id)
            ->where('status_aktif', true)
            ->count();

        return view('admin.siltap.show', compact('siltap', 'perangkatAktifSekarang'));
    }

    /**
     * Tahap 2: Verifikasi & Setujui / Tolak.
     */
    public function verifikasi(Request $request, Siltap $siltap)
    {
        $request->validate([
            'keputusan' => 'required|in:disetujui,ditolak',
            'catatan_verifikator' => 'nullable|string',
        ]);

        $data = [
            'status' => $request->keputusan,
            'catatan_verifikator' => $request->catatan_verifikator,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ];

        $siltap->update($data);

        $msg = $request->keputusan === 'disetujui'
            ? 'Pencairan Siltap Desa '.$siltap->desa->nama_desa.' DISETUJUI.'
            : 'Pencairan Siltap Desa '.$siltap->desa->nama_desa.' DITOLAK.';

        return back()->with('success', $msg);
    }

    /**
     * Tahap 3: Tandai sebagai "Dikirim ke BKAD/Bank".
     */
    public function kirimBkad(Request $request, Siltap $siltap)
    {
        if ($siltap->status !== 'disetujui') {
            return back()->with('error', 'Hanya pengajuan berstatus "Disetujui" yang bisa dikirim ke BKAD.');
        }

        $siltap->update([
            'status' => 'dikirim_bkad',
        ]);

        return back()->with('success', 'Status pencairan Desa '.$siltap->desa->nama_desa.' telah diperbarui: Dikirim ke BKAD/Bank.');
    }
}
