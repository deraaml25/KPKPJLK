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
        $desaId = Auth::user()->desa_id;
        $penataans = PenataanDesa::where('desa_id', $desaId)->latest()->get();

        // Cek profile statis desanya
        $desa = Auth::user()->desa;

        return view('desa.penataan.index', compact('penataans', 'desa'));
    }

    public function create()
    {
        return view('desa.penataan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipe' => 'required|in:pemekaran,penggabungan,perubahan_status,perubahan_batas',
            'nama_wilayah_baru' => 'required|string',
            'jumlah_penduduk' => 'required|integer|min:0',
            'jumlah_kk' => 'required|integer|min:0',
            'proposal' => 'required|file|mimes:pdf|max:10240',
        ]);

        $desaId = Auth::user()->desa_id;
        $propPath = $request->file('proposal')->store('penataan/proposal', 'public');

        PenataanDesa::create([
            'desa_id' => $desaId,
            'tipe' => $request->tipe,
            'nama_wilayah_baru' => $request->nama_wilayah_baru,
            'jumlah_penduduk' => $request->jumlah_penduduk,
            'jumlah_kk' => $request->jumlah_kk,
            'proposal_path' => $propPath,
            'status' => 'evaluasi',
            'status_evaluasi_tahun' => 1
        ]);

        return redirect()->route('desa.penataan.index')->with('success', 'Usulan penataan desa berhasil dikirim.');
    }
}
