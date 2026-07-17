<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PenataanDesa;
use Illuminate\Http\Request;

class PenataanController extends Controller
{
    public function index()
    {
        $penataans = PenataanDesa::with('desa')->latest()->paginate(15);
        return view('admin.penataan.index', compact('penataans'));
    }

    public function show(PenataanDesa $penataan)
    {
        $penataan->load('desa');
        return view('admin.penataan.show', compact('penataan'));
    }

    public function verifikasi(Request $request, PenataanDesa $penataan)
    {
        $status = $request->input('status', 'approved');

        // business rule: pulau jawa minimal 6000 jiwa / 1200 KK
        if ($status === 'approved') {
            if ($penataan->jumlah_penduduk < 6000 || $penataan->jumlah_kk < 1200) {
                return redirect()->back()->with('error', 'Kalkulator Kelayakan UU Desa: Kriteria kelayakan minimal (6.000 jiwa atau 1.200 KK untuk Pulau Jawa) tidak terpenuhi.');
            }
        }

        $path = null;
        if ($request->hasFile('rekomendasi')) {
            $path = $request->file('rekomendasi')->store('penataan/rekomendasi', 'public');
        }

        $penataan->update([
            'status' => $status,
            'rekomendasi_dinas_path' => $path ?? $penataan->rekomendasi_dinas_path,
        ]);

        return redirect()->route('admin.penataan.show', $penataan)->with('success', 'Status usulan penataan desa diperbarui.');
    }
}
