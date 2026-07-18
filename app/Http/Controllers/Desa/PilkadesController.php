<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\Pilkades;
use App\Models\PilkadesSuara;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PilkadesController extends Controller
{
    public function index()
    {
        $desaId = Auth::user()->desa_id;
        $pilkadesObj = Pilkades::withoutGlobalScopes()
            ->with(['suaras.inputter'])
            ->where('desa_id', $desaId)
            ->latest()
            ->first(); // Mengambil event Pilkades paling baru di desa ini

        return view('desa.pilkades.index', compact('pilkadesObj'));
    }

    public function show($id)
    {
        $pilkades = Pilkades::withoutGlobalScopes()->with('suaras')->findOrFail($id);

        if ($pilkades->desa_id !== Auth::user()->desa_id) {
            abort(403, 'Akses ditolak: Data Pilkades ini milik desa lain.');
        }

        return view('desa.pilkades.show', compact('pilkades'));
    }

    /**
     * Input Live Quick Count per TPS
     */
    public function storeSuara(Request $request, $id)
    {
        $pilkades = Pilkades::withoutGlobalScopes()->findOrFail($id);

        if ($pilkades->desa_id !== Auth::user()->desa_id) {
            abort(403);
        }

        // Kunci: Jangan izinkan input jika Pilkades sudah disahkan/dikunci Admin
        if ($pilkades->isLocked()) {
            return redirect()->back()->with('error', 'Gagal input: Data Pilkades telah disahkan oleh Bupati. Rekapitulasi suara sudah dikunci permanen.');
        }

        $request->validate([
            'tps_name' => 'required|string|max:100',
            'total_pemilih_hadir' => 'required|integer|min:0',
            'suara_sah' => 'required|integer|min:0',
            'suara_tidak_sah' => 'required|integer|min:0',
            'suara_calon_1' => 'required|integer|min:0',
            'suara_calon_2' => 'required|integer|min:0',
            'suara_calon_3' => 'required|integer|min:0',
        ]);

        // 1. Validasi Integritas TPS
        $totalHadir = $request->suara_sah + $request->suara_tidak_sah;
        if ($totalHadir !== (int) $request->total_pemilih_hadir) {
            return redirect()->back()->withInput()->withErrors([
                'total_pemilih_hadir' => 'Integritas Gagal: Total suara sah + tidak sah (' . $totalHadir . ') TIDAK SAMA dengan total pemilih hadir yang diinputkan (' . $request->total_pemilih_hadir . ').'
            ]);
        }

        // 2. Validasi Suara Calon
        $totalSuaraCalon = $request->suara_calon_1 + $request->suara_calon_2 + $request->suara_calon_3;
        if ($totalSuaraCalon !== (int) $request->suara_sah) {
            return redirect()->back()->withInput()->withErrors([
                'suara_sah' => 'Integritas Gagal: Akumulasi suara seluruh calon (' . $totalSuaraCalon . ') TIDAK SAMA dengan jumlah suara sah TPS (' . $request->suara_sah . ').'
            ]);
        }

        // 3. Pencegahan Duplikat TPS (hanya satu input per TPS, tapi bisa diedit kalau belum locked)
        $suara = PilkadesSuara::where('pilkades_id', $pilkades->id)
            ->where('tps_name', $request->tps_name)
            ->first();

        if ($suara && $suara->is_locked) {
            return redirect()->back()->with('error', "Gagal input: Data TPS {$request->tps_name} sudah dikunci.");
        }

        PilkadesSuara::updateOrCreate(
            ['pilkades_id' => $pilkades->id, 'tps_name' => $request->tps_name],
            [
                'total_pemilih_hadir' => $request->total_pemilih_hadir,
                'suara_sah' => $request->suara_sah,
                'suara_tidak_sah' => $request->suara_tidak_sah,
                'suara_calon_1' => $request->suara_calon_1,
                'suara_calon_2' => $request->suara_calon_2,
                'suara_calon_3' => $request->suara_calon_3,
                'input_by' => Auth::id(),
                'ip_address' => $request->ip(),
            ]
        );

        // Update status master jika ini TPS pertama
        if ($pilkades->status === 'persiapan') {
            $pilkades->update(['status' => 'pemungutan']);
        }

        return redirect()->route('desa.pilkades.index')
            ->with('success', "Live Quick Count untuk TPS {$request->tps_name} berhasil disimpan secara real-time.");
    }
}
