<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Desa;
use App\Models\Pilkades;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PilkadesController extends Controller
{
    public function index()
    {
        $pilkades = Pilkades::withoutGlobalScopes()->with('desa', 'pengesah')->latest()->paginate(15);
        $desas = Desa::orderBy('nama_desa')->get(); // Untuk dropdown buat pilkades baru

        return view('admin.pilkades.index', compact('pilkades', 'desas'));
    }

    public function show($id)
    {
        $pilkades = Pilkades::withoutGlobalScopes()->with(['desa', 'suaras', 'pengesah'])->findOrFail($id);

        return view('admin.pilkades.show', compact('pilkades'));
    }

    /**
     * Tahap 1: Setup Master Data Pilkades (Jadwal & Calon)
     */
    public function store(Request $request)
    {
        $request->validate([
            'desa_id' => 'required|exists:desas,id',
            'tanggal_pemungutan' => 'required|date',
            'total_tps' => 'required|integer|min:1',
            'total_dpt' => 'required|integer|min:1',
            'calon_1_nama' => 'nullable|string|max:255',
            'calon_2_nama' => 'nullable|string|max:255',
            'calon_3_nama' => 'nullable|string|max:255',
        ]);

        Pilkades::create([
            'desa_id' => $request->desa_id,
            'tanggal_pemungutan' => $request->tanggal_pemungutan,
            'total_tps' => $request->total_tps,
            'total_dpt' => $request->total_dpt,
            'calon_1_nama' => $request->calon_1_nama,
            'calon_2_nama' => $request->calon_2_nama,
            'calon_3_nama' => $request->calon_3_nama,
            'status' => 'persiapan',
        ]);

        return redirect()->route('admin.pilkades.index')
            ->with('success', 'Master Pelaksanaan Pilkades Serentak berhasil di-setup.');
    }

    /**
     * Tahap Terakhir: Penerbitan SK Bupati & Lock Data Suara
     */
    public function generateSk(Request $request, $id)
    {
        $pilkades = Pilkades::withoutGlobalScopes()->findOrFail($id);

        $request->validate([
            'sk_bupati' => 'required|file|mimes:pdf|max:10240',
        ]);

        // Hitung pemenang dari model quick count
        $pemenang = $pilkades->pemenang;

        if (! $pemenang) {
            return redirect()->back()->with('error', 'Gagal memproses SK: Belum ada data perolehan suara yang masuk dari desa.');
        }

        $skPath = $request->file('sk_bupati')->store('pilkades/sk_bupati', 'public');

        // 1. Kunci rekapitulasi data suara permanen
        $pilkades->suaras()->update(['is_locked' => true]);

        // 2. Terbitkan SK dan Sahkan
        $pilkades->update([
            'pemenang_nama' => $pemenang, // Pemenang otomatis ditarik dari hasil rekap
            'sk_bupati_path' => $skPath,
            'status' => 'selesai',
            'disahkan_oleh' => Auth::id(),
            'disahkan_at' => now(),
        ]);

        return redirect()->route('admin.pilkades.show', $pilkades)
            ->with('success', "SK Bupati berhasil diterbitkan. Data perolehan suara resmi dikunci permanen. Pemenang: {$pemenang}.");
    }
}
