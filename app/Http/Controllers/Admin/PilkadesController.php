<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pilkades;
use Illuminate\Http\Request;

class PilkadesController extends Controller
{
    public function index()
    {
        $pilkades = Pilkades::with('desa')->latest()->paginate(15);
        return view('admin.pilkades.index', compact('pilkades'));
    }

    public function show(Pilkades $pilkades)
    {
        $pilkades->load(['desa', 'suaras']);
        return view('admin.pilkades.show', compact('pilkades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'desa_id' => 'required|exists:desas,id',
            'tanggal_pemungutan' => 'required|date',
            'total_tps' => 'required|integer',
        ]);

        Pilkades::create([
            'desa_id' => $request->desa_id,
            'tanggal_pemungutan' => $request->tanggal_pemungutan,
            'total_tps' => $request->total_tps,
            'status' => 'persiapan'
        ]);

        return redirect()->route('admin.pilkades.index')->with('success', 'Fasilitasi Pilkades berhasil didaftarkan.');
    }

    public function generateSk(Request $request, Pilkades $pilkades)
    {
        $request->validate([
            'pemenang_nama' => 'required',
        ]);

        $fileName = 'SK_Bupati_KadesTerpilih_' . $pilkades->id . '.pdf';
        $path = 'pilkades/sk/' . $fileName;

        $pilkades->update([
            'pemenang_nama' => $request->pemenang_nama,
            'sk_bupati_path' => $path,
            'status' => 'selesai'
        ]);

        return redirect()->route('admin.pilkades.show', $pilkades)->with('success', 'SK Kades Terpilih berhasil dibuat.');
    }
}
