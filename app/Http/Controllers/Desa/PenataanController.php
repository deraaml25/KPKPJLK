<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\PenataanDesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenataanController extends Controller
{
    public function index()
    {
        $penataan = PenataanDesa::where('desa_id', Auth::user()->desa_id)->latest()->first();

        return view('desa.penataan.index', compact('penataan'));
    }

    public function store(Request $request)
    {
        // Hanya bisa 1 pengajuan per desa pada satu waktu
        $existing = PenataanDesa::where('desa_id', Auth::user()->desa_id)
            ->whereIn('status', ['diajukan', 'persiapan'])
            ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'Desa Anda masih memiliki usulan penataan dalam proses.');
        }

        $request->validate([
            'jumlah_penduduk' => 'required|integer|min:1',
            'jumlah_kk' => 'required|integer|min:1',
            'luas_wilayah_km2' => 'required|numeric|min:0.1',
            'peta_geospasial' => 'required|file|mimes:pdf,zip|max:20480', // zip u/ format shp/geojson spatial
        ]);

        $petaPath = $request->file('peta_geospasial')->store('penataan/peta', 'public');

        PenataanDesa::create([
            'desa_id' => Auth::user()->desa_id,
            'jumlah_penduduk' => $request->jumlah_penduduk,
            'jumlah_kk' => $request->jumlah_kk,
            'luas_wilayah_km2' => $request->luas_wilayah_km2,
            'peta_geospasial_path' => $petaPath,
            'status' => 'diajukan',
        ]);

        return redirect()->route('desa.penataan.index')
            ->with('success', 'Usulan Penataan / Pemekaran Desa berhasil diajukan. Menunggu proses hitung otomatis dari Dinpermasdes.');
    }
}
