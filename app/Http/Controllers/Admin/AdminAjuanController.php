<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ajuan;
use App\Models\ChecklistAjuan;
use App\Models\MilestoneTracking;
use Illuminate\Http\Request;

class AdminAjuanController extends Controller
{
    /**
     * Dashboard List Ajuan
     */
    public function index()
    {
        // Hanya tampilkan ajuan yang sudah di-submit ke atas (bukan draft)
        $ajuans = Ajuan::where('status', '!=', 'draft')
            ->with(['desa', 'jenisLayanan', 'perangkatDesa'])
            ->orderBy('tgl_diajukan', 'desc')
            ->get();

        return view('admin.ajuan.index', compact('ajuans'));
    }

    /**
     * Split-Screen Verification View
     */
    public function show(Ajuan $ajuan)
    {
        // Pastikan bukan draft
        if ($ajuan->status === 'draft') {
            abort(404, 'Ajuan belum disubmit oleh desa.');
        }

        $ajuan->load(['desa', 'jenisLayanan', 'perangkatDesa', 'checklistAjuans.templateChecklist', 'milestoneTrackings']);

        $dokumenList = $ajuan->checklistAjuans->sortBy('templateChecklist.urutan');

        return view('admin.ajuan.show', compact('ajuan', 'dokumenList'));
    }

    /**
     * Verifikasi Granular (Valid/Tolak per dokumen)
     */
    public function verifyDokumen(Request $request, Ajuan $ajuan, ChecklistAjuan $checklistAjuan)
    {
        $request->validate([
            'status' => 'required|in:menunggu,valid,kurang,tidak_sesuai',
            'catatan' => 'nullable|string'
        ]);

        $checklistAjuan->update([
            'status' => $request->status,
            'catatan' => $request->catatan,
        ]);

        // Jika mengubah status menjadi ditolak (kurang/tidak sesuai), otomatis ubah status Ajuan
        if (in_array($request->status, ['kurang', 'tidak_sesuai']) && $ajuan->status !== 'direvisi') {
            $ajuan->update(['status' => 'direvisi']);
        }

        return back()->with('success', 'Status dokumen berhasil diperbarui!');
    }

    /**
     * Disposisi Surat
     */
    public function updateDisposisi(Request $request, Ajuan $ajuan)
    {
        $request->validate([
            'posisi_baru' => 'required|string',
            'status_ajuan_baru' => 'nullable|in:submitted,direvisi,diproses,selesai,ditolak',
            'catatan_milestone' => 'nullable|string'
        ]);

        $ajuan->update([
            'posisi_surat' => $request->posisi_baru,
            'status' => $request->status_ajuan_baru ?? $ajuan->status
        ]);

        MilestoneTracking::create([
            'ajuan_id' => $ajuan->id,
            'user_id' => auth()->id(), // Admin ID
            'judul_tahapan' => 'Disposisi: ' . $request->posisi_baru,
            'deskripsi' => $request->catatan_milestone ?? '',
            'catatan_kekurangan' => null,
            'tgl_verifikasi' => now()
        ]);

        return back()->with('success', 'Sistem berhasil mendisposisikan ajuan ke ' . $request->posisi_baru);
    }
}
