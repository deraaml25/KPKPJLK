<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\PengajuanPembinaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengajuanPembinaanController extends Controller
{
    public function index()
    {
        $desaId = Auth::user()->desa_id;

        $pengajuans = PengajuanPembinaan::where('desa_id', $desaId)
            ->latest()
            ->get();

        return view('desa.pengajuan-pembinaan.index', compact('pengajuans'));
    }

    public function create()
    {
        return view('desa.pengajuan-pembinaan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul_kegiatan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_diajukan' => 'required|date',
            'file_surat_permohonan' => 'nullable|file|mimes:pdf|max:10240',
            'file_undangan' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $data = [
            'desa_id' => Auth::user()->desa_id,
            'user_id' => Auth::user()->id,
            'judul_kegiatan' => $request->judul_kegiatan,
            'deskripsi' => $request->deskripsi,
            'tanggal_diajukan' => $request->tanggal_diajukan,
            'status' => 'menunggu',
        ];

        if ($request->hasFile('file_surat_permohonan')) {
            $data['file_surat_permohonan'] = $request->file('file_surat_permohonan')
                ->store('bimtek/pengajuan/surat-permohonan', 'public');
        }

        if ($request->hasFile('file_undangan')) {
            $data['file_undangan'] = $request->file('file_undangan')
                ->store('bimtek/pengajuan/undangan', 'public');
        }

        PengajuanPembinaan::create($data);

        return redirect()->route('desa.pengajuan-pembinaan.index')
            ->with('success', 'Pengajuan pembinaan berhasil dikirim ke Dinpermasdes. Mohon menunggu balasan.');
    }

    public function show(PengajuanPembinaan $pengajuanPembinaan)
    {
        // Pastikan hanya desa pemilik yang bisa lihat
        if ($pengajuanPembinaan->desa_id !== Auth::user()->desa_id) {
            abort(403);
        }

        return view('desa.pengajuan-pembinaan.show', compact('pengajuanPembinaan'));
    }
}
