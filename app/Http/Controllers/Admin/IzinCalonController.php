<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IzinCalon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IzinCalonController extends Controller
{
    public function index()
    {
        $izins = IzinCalon::withoutGlobalScopes()->with('desa')->latest()->paginate(15);

        return view('admin.izincalon.index', compact('izins'));
    }

    public function show($id)
    {
        $izincalon = IzinCalon::withoutGlobalScopes()->with('desa', 'verifikator')->findOrFail($id);

        return view('admin.izincalon.show', compact('izincalon'));
    }

    /**
     * Tahap 2+3: Verifikasi Inspektorat → Terbitkan atau Tolak Izin Bupati.
     *
     * Alur:
     *  - Jika has_temuan = true → sistem mengunci approve, paksa status = rejected
     *  - Jika has_temuan = false & status = approved → upload SK Izin + catat audit trail
     */
    public function verifikasi(Request $request, $id)
    {
        $izincalon = IzinCalon::withoutGlobalScopes()->findOrFail($id);

        $request->validate([
            'has_temuan_inspektorat' => 'required|in:0,1',
            'catatan_inspektorat' => 'nullable|string|max:1000',
        ]);

        $hasTemuan = (bool) $request->has_temuan_inspektorat;

        // ── GATEKEEPER: Jika ada temuan, kunci approve & tolak otomatis ──
        if ($hasTemuan) {
            $izincalon->update([
                'has_temuan_inspektorat' => true,
                'catatan_inspektorat' => $request->catatan_inspektorat ?? 'Calon terdeteksi memiliki temuan kerugian negara/desa yang belum diselesaikan. Mohon selesaikan terlebih dahulu.',
                'status' => 'rejected',
                'verified_by' => Auth::id(),
                'verified_at' => now(),
            ]);

            return redirect()->route('admin.izincalon.show', $izincalon)
                ->with('error', 'Permohonan ditolak: Calon memiliki temuan Inspektorat yang belum diselesaikan. Tidak dapat diterbitkan izin Bupati.');
        }

        // ── APPROVE PATH: Calon bersih → validasi upload SK Izin Bupati ──
        $request->validate([
            'surat_izin_bupati' => 'required|file|mimes:pdf|max:10240',
        ]);

        $skPath = $request->file('surat_izin_bupati')->store('izincalon/sk_bupati', 'public');

        $izincalon->update([
            'has_temuan_inspektorat' => false,
            'catatan_inspektorat' => $request->catatan_inspektorat,
            'surat_izin_bupati_path' => $skPath,
            'status' => 'approved',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        return redirect()->route('admin.izincalon.show', $izincalon)
            ->with('success', 'Surat Izin Bupati berhasil diterbitkan. Calon dinyatakan bebas temuan Inspektorat.');
    }
}
