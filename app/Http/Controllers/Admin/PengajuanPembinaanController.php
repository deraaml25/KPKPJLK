<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanPembinaan;
use Illuminate\Http\Request;

class PengajuanPembinaanController extends Controller
{
    public function index()
    {
        $pengajuans = PengajuanPembinaan::with('desa')
            ->latest()
            ->paginate(20);

        $totalMenunggu = PengajuanPembinaan::where('status', 'menunggu')->count();
        $totalDisetujui = PengajuanPembinaan::where('status', 'disetujui')->count();

        return view('admin.pengajuan-pembinaan.index', compact('pengajuans', 'totalMenunggu', 'totalDisetujui'));
    }

    public function show(PengajuanPembinaan $pengajuanPembinaan)
    {
        $pengajuanPembinaan->load('desa', 'user');

        return view('admin.pengajuan-pembinaan.show', compact('pengajuanPembinaan'));
    }

    public function balas(Request $request, PengajuanPembinaan $pengajuanPembinaan)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak,selesai',
            'catatan_admin' => 'required|string|max:2000',
        ]);

        $pengajuanPembinaan->update([
            'status' => $request->status,
            'catatan_admin' => $request->catatan_admin,
            'dibalas_at' => now(),
        ]);

        $namaDesa = $pengajuanPembinaan->desa->nama_desa ?? '-';
        $statusLabel = match ($request->status) {
            'disetujui' => 'disetujui',
            'ditolak' => 'ditolak',
            'selesai' => 'ditandai selesai',
            default => 'diperbarui',
        };

        return back()->with('success', "Pengajuan dari Desa {$namaDesa} berhasil {$statusLabel}.");
    }
}
