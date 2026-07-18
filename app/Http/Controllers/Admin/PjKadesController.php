<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PjKades;
use App\Models\PerangkatDesa;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PjKadesController extends Controller
{
    public function index()
    {
        $pjkades = PjKades::withoutGlobalScopes()->with('desa')->latest()->paginate(15);
        return view('admin.pjkades.index', compact('pjkades'));
    }

    public function show($id)
    {
        $pjkades = PjKades::withoutGlobalScopes()->with('desa')->findOrFail($id);
        return view('admin.pjkades.show', compact('pjkades'));
    }

    /**
     * Tahap 3: Verifikasi Rekam Jejak — Setujui atau Tolak
     * Tahap 4: Penerbitan SK Bupati — Upload SK + Input Masa Berlaku
     */
    public function generateSk(Request $request, $id)
    {
        $pjkades = PjKades::withoutGlobalScopes()->findOrFail($id);

        $request->validate([
            'status_bebas_hukdis' => 'required|in:clean,has_issues',
        ]);

        // ── Tahap 3: Jika PNS bermasalah → Tolak ──
        if ($request->status_bebas_hukdis === 'has_issues') {
            $pjkades->update([
                'status_bebas_hukdis' => 'has_issues',
                'status' => 'rejected',
            ]);
            return redirect()->route('admin.pjkades.show', $pjkades)
                ->with('error', 'PNS sedang menjalani hukuman disiplin. Usulan ditolak, dikembalikan ke Camat.');
        }

        // ── Tahap 4: PNS bersih → Validasi SK Bupati + Masa Berlaku ──
        $request->validate([
            'sk_bupati' => 'required|file|mimes:pdf|max:10240',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after:tgl_mulai',
        ]);

        // Kunci: Masa jabatan max 1 tahun
        $tglMulai = Carbon::parse($request->tgl_mulai);
        $tglSelesai = Carbon::parse($request->tgl_selesai);
        $maxSelesai = $tglMulai->copy()->addYear();

        if ($tglSelesai->greaterThan($maxSelesai)) {
            return back()->withErrors([
                'tgl_selesai' => 'Masa jabatan Pj Kades tidak boleh lebih dari 1 (satu) tahun sejak tanggal mulai berlaku SK.'
            ])->withInput();
        }

        $skPath = $request->file('sk_bupati')->store('pjkades/sk_bupati', 'public');

        $pjkades->update([
            'status_bebas_hukdis' => 'clean',
            'sk_bupati_path' => $skPath,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'status' => 'approved',
        ]);

        // ── Integrasi Data Master: Sinkronisasi ke perangkat_desas ──
        // 1. Non-aktifkan Kades lama di desa ini
        PerangkatDesa::where('desa_id', $pjkades->desa_id)
            ->where('jabatan', 'Kepala Desa')
            ->update(['status_aktif' => false]);

        // 2. Buat/perbarui record Pj Kades sebagai "Kepala Desa" aktif
        PerangkatDesa::updateOrCreate(
            ['desa_id' => $pjkades->desa_id, 'jabatan' => 'Kepala Desa'],
            [
                'nama' => $pjkades->nama_pns . ' (Pj)',
                'status_aktif' => true,
                'tgl_mulai_jabatan' => $request->tgl_mulai,
                'no_sk_terakhir' => 'SK Bupati Pj Kades #' . $pjkades->id,
            ]
        );

        return redirect()->route('admin.pjkades.show', $pjkades)
            ->with('success', 'SK Bupati Pj Kades berhasil diterbitkan. Profil Kepala Desa telah diperbarui di data master.');
    }
}
