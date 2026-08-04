<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ajuan;
use App\Models\ChecklistAjuan;
use App\Models\JenisLayanan;
use App\Models\LogKekurangan;
use App\Models\MilestoneTracking;
use App\Models\TemplateChecklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AjuanController extends Controller
{
    public function index(Request $request)
    {
        $query = Ajuan::with(['desa.kecamatan', 'jenisLayanan', 'pesertas.perangkatDesa'])
            ->whereNotIn('status', ['draft']);

        if ($request->filled('jenis_layanan_id')) {
            $query->where('jenis_layanan_id', $request->jenis_layanan_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('desa_id')) {
            $query->where('desa_id', $request->desa_id);
        }

        $ajuans = $query->latest()->paginate(15);
        $jenisLayanans = JenisLayanan::all();

        return view('admin.ajuan.index', compact('ajuans', 'jenisLayanans'));
    }

    public function show(Ajuan $ajuan)
    {
        $ajuan->load([
            'desa.kecamatan',
            'jenisLayanan',
            'alasanPemberhentian',
            'pesertas.perangkatDesa',
            'checklistAjuans.templateChecklist',
            'checklistAjuans.logKekurangans',
            'milestoneTrackings',
            'arsipRekom',
        ]);

        $templates = TemplateChecklist::where('jenis_layanan_id', $ajuan->jenis_layanan_id)
            ->where(function ($query) use ($ajuan) {
                $query->whereNull('alasan_pemberhentian_id')
                    ->orWhere('alasan_pemberhentian_id', $ajuan->alasan_pemberhentian_id);
            })
            ->orderBy('urutan')
            ->get();

        $existingTemplateIds = $ajuan->checklistAjuans->pluck('template_checklist_id')->filter()->all();

        foreach ($templates as $template) {
            if (! in_array($template->id, $existingTemplateIds, true)) {
                $ajuan->checklistAjuans()->create([
                    'template_checklist_id' => $template->id,
                    'status' => 'pending',
                    'versi' => 1,
                ]);
                $existingTemplateIds[] = $template->id;
            }
        }

        $ajuan->load('checklistAjuans.templateChecklist');
        $tahapAktif = $this->hitungTahapAktif($ajuan->milestoneTrackings);

        return view('admin.ajuan.show', compact('ajuan', 'tahapAktif'));
    }

    public function verifikasiChecklist(Request $request, Ajuan $ajuan, ChecklistAjuan $checklistAjuan)
    {
        $request->validate([
            'status' => ['required', 'in:lengkap,kurang,tidak_sesuai,pending'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ]);

        $statusLama = $checklistAjuan->status;

        $checklistAjuan->update([
            'status' => $request->status,
            'catatan' => $request->catatan,
            'updated_by' => Auth::id(),
        ]);

        // Catat log kekurangan jika status kurang atau tidak sesuai
        if (in_array($request->status, ['kurang', 'tidak_sesuai'])) {
            LogKekurangan::create([
                'checklist_ajuan_id' => $checklistAjuan->id,
                'status_lama' => $statusLama,
                'status_baru' => $request->status,
                'catatan' => $request->catatan ?? '',
            ]);
        }

        // Update status ajuan jika ada yang kurang
        $adaKurang = $ajuan->checklistAjuans()
            ->whereIn('status', ['kurang', 'tidak_sesuai'])
            ->exists();

        $semuaLengkap = $ajuan->checklistAjuans()
            ->where('status', '!=', 'lengkap')
            ->doesntExist();

        if ($adaKurang) {
            $ajuan->update(['status' => 'direvisi']);
        } elseif ($semuaLengkap && in_array($ajuan->status, ['submitted', 'direvisi'])) {
            $ajuan->update(['status' => 'diproses']);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'status' => $checklistAjuan->status,
                'message' => 'Status dokumen berhasil diperbarui.'
            ]);
        }

        return back()->with('success', 'Status dokumen berhasil diperbarui.');
    }

    public function updateMilestone(Request $request, Ajuan $ajuan)
    {
        $request->validate([
            'tahap' => ['required', 'integer', 'min:1', 'max:10'],
            'tgl_selesai' => ['nullable', 'date'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        MilestoneTracking::updateOrCreate(
            ['ajuan_id' => $ajuan->id, 'tahap' => $request->tahap],
            [
                'tgl_mulai' => now()->toDateString(),
                'tgl_selesai' => $request->tgl_selesai ?: now()->toDateString(),
                'catatan' => $request->catatan,
                'updated_by' => Auth::id(),
            ]
        );

        // Otomatis set ajuan ke diproses saat milestone pertama diisi
        if ($ajuan->status === 'submitted' || $ajuan->status === 'direvisi') {
            $ajuan->update(['status' => 'diproses']);
        }

        // Jika tahap 10 selesai, tandai ajuan sebagai selesai
        if ($request->tahap == 10) {
            $ajuan->update(['status' => 'selesai']);
        }

        return back()->with('success', 'Milestone Tahap '.$request->tahap.' berhasil diselesaikan!');
    }

    private function hitungTahapAktif($milestoneTrackings): int
    {
        if ($milestoneTrackings->isEmpty()) {
            return 1;
        }

        // Cari tahap yang belum selesai (tgl_selesai null)
        $tahapBelumSelesai = $milestoneTrackings
            ->whereNull('tgl_selesai')
            ->min('tahap');

        if ($tahapBelumSelesai) {
            return (int) $tahapBelumSelesai;
        }

        // Semua yang ada sudah selesai, ambil tahap terbesar + 1
        $maxTahap = $milestoneTrackings->max('tahap');

        return min((int) $maxTahap + 1, 10);
    }

    public function destroy(Ajuan $ajuan)
    {
        if ($ajuan->folder_path && Storage::disk('public')->exists($ajuan->folder_path)) {
            Storage::disk('public')->deleteDirectory($ajuan->folder_path);
        }

        $ajuan->checklistAjuans()->delete();
        $ajuan->pesertas()->delete();
        $ajuan->milestoneTrackings()->delete();
        $ajuan->delete();

        return redirect()->route('admin.ajuan.index')->with('success', 'Data usulan e-Rekomendasi berhasil dihapus oleh Admin.');
    }
}
