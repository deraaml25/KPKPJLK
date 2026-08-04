<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanPembinaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    public function destroy(PengajuanPembinaan $pengajuanPembinaan)
    {
        if ($pengajuanPembinaan->file_surat_permohonan && Storage::disk('public')->exists($pengajuanPembinaan->file_surat_permohonan)) {
            Storage::disk('public')->delete($pengajuanPembinaan->file_surat_permohonan);
        }
        
        if ($pengajuanPembinaan->file_undangan && Storage::disk('public')->exists($pengajuanPembinaan->file_undangan)) {
            Storage::disk('public')->delete($pengajuanPembinaan->file_undangan);
        }
        
        if ($pengajuanPembinaan->file_balasan && Storage::disk('public')->exists($pengajuanPembinaan->file_balasan)) {
            Storage::disk('public')->delete($pengajuanPembinaan->file_balasan);
        }

        $pengajuanPembinaan->delete();

        return redirect()->route('admin.pengajuan-pembinaan.index')
            ->with('success', 'Data pengajuan pembinaan berhasil dihapus secara permanen.');
    }
}
