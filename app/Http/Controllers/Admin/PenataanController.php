<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PenataanDesa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenataanController extends Controller
{
    public function index()
    {
        $penataan = PenataanDesa::withoutGlobalScopes()->with('desa')->latest()->paginate(15);

        return view('admin.penataan.index', compact('penataan'));
    }

    public function show($id)
    {
        $penataan = PenataanDesa::withoutGlobalScopes()->with('desa', 'prosesor')->findOrFail($id);

        // Auto-run Kalkulator UU setiap kali admin membuka halaman (read-only insight)
        $kalkulator = $penataan->runKalkulatorUU();

        return view('admin.penataan.show', compact('penataan', 'kalkulator'));
    }

    /**
     * TAHAP 2: Gatekeeper Kalkulator UU -> Menetapkan Desa Persiapan
     */
    public function setPersiapan(Request $request, $id)
    {
        $penataan = PenataanDesa::withoutGlobalScopes()->findOrFail($id);

        // 1. Otoritas Mesin: Apabila gagal syarat UU, sistem menolak tombol Approve (bypass protection)
        $hasilHitung = $penataan->runKalkulatorUU();
        if (! $hasilHitung['is_valid']) {
            $alasanGagal = implode(' ', $hasilHitung['messages']);
            $penataan->update([
                'status' => 'ditolak',
                'alasan_penolakan' => "Ditolak otomatis oleh sistem (Kalkulator UU Desa): {$alasanGagal}",
                'diproses_oleh' => Auth::id(),
                'diproses_at' => now(),
            ]);

            return redirect()->route('admin.penataan.show', $penataan)
                ->with('error', 'Sistem menolak usulan! Data demografis atau spasial di bawah ambang batas UU Desa.');
        }

        // 2. Jika lolos UU, validasi upload Perbup penetapan
        $request->validate([
            'perbup_persiapan' => 'required|file|mimes:pdf|max:10240',
            'tgl_mulai_persiapan' => 'required|date',
            'lama_uji_coba_tahun' => 'required|integer|in:1,2,3', // UU batasan uji coba 1-3 thn
        ]);

        $perbupPath = $request->file('perbup_persiapan')->store('penataan/perbup', 'public');

        // Set timeline persiapan
        $mulai = Carbon::parse($request->tgl_mulai_persiapan);
        $batas = $mulai->copy()->addYears($request->lama_uji_coba_tahun);

        $penataan->update([
            'perbup_persiapan_path' => $perbupPath,
            'status' => 'persiapan',
            'tgl_mulai_persiapan' => $mulai,
            'tgl_batas_persiapan' => $batas,
            'diproses_oleh' => Auth::id(),
            'diproses_at' => now(),
        ]);

        return redirect()->route('admin.penataan.show', $penataan)
            ->with('success', 'Usulan memenuhi syarat UU. Status berhasil ditingkatkan menjadi Desa Persiapan.');
    }

    /**
     * TAHAP 4: Sinkronisasi Status Final Definitif dari Kemendagri
     */
    public function setDefinitif(Request $request, $id)
    {
        $penataan = PenataanDesa::withoutGlobalScopes()->findOrFail($id);

        if ($penataan->status !== 'persiapan') {
            abort(403, 'Aksi ilegal. Desa ini belum melalui masa persiapan.');
        }

        $request->validate([
            'kode_desa_kemendagri' => 'required|string|unique:penataan_desas,kode_desa_kemendagri',
        ]);

        // Kunci kode definitif
        $penataan->update([
            'status' => 'definitif',
            'kode_desa_kemendagri' => $request->kode_desa_kemendagri,
            'diproses_oleh' => Auth::id(),
            'diproses_at' => now(),
        ]);

        // Opsional: Integrasi Master Data
        // \App\Models\Desa::create([ 'kode' => $request->kode, 'nama' => ... ])

        return redirect()->route('admin.penataan.show', $penataan)
            ->with('success', 'Selamat! Kode Kemendagri teregistrasi. Desa persiapan ini resmi menjadi Desa Definitif.');
    }
}
