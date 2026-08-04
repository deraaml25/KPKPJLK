<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\AjuanBpd;
use App\Models\AjuanBpdPeserta;
use App\Models\AlasanPemberhentian;
use App\Models\Bpd; // Note: using PerangkatDesa or Bpd? The user wrote Bpd model.
use App\Models\ChecklistAjuanBpd;
use App\Models\MilestoneAjuanBpd;
use App\Models\TemplateChecklistBpd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AjuanBpdController extends Controller
{
    public function index()
    {
        $ajuans = AjuanBpd::where('desa_id', auth()->user()->desa_id)->latest()->paginate(10);

        return view('desa.ajuan-bpd.index', compact('ajuans'));
    }

    public function create()
    {
        // Actually, the Bpd members are probably in PerangkatDesa where jabatan includes BPD, or in a separate Bpd table.
        // Based on user: bpd_id -> Bpd member being proposed. So there's a Bpd model.
        // Let's pass what's needed.
        $bpds = Bpd::where('desa_id', auth()->user()->desa_id)->get();
        $alasans = AlasanPemberhentian::whereIn('nama', ['Mengundurkan Diri', 'Meninggal Dunia', 'Diberhentikan'])->get();

        return view('desa.ajuan-bpd.create', compact('bpds', 'alasans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_ajuan' => 'required|in:pemberhentian,peresmian',
            'metode' => 'required|in:online,offline',
            'bpd_ids' => 'required|array',
        ]);

        $ajuan = DB::transaction(function () use ($request) {
            $ajuan = AjuanBpd::create([
                'desa_id' => auth()->user()->desa_id,
                'no_registrasi' => 'BPD-'.time(),
                'jenis_ajuan' => $request->jenis_ajuan,
                'metode' => $request->metode,
                'alasan_pemberhentian_id' => $request->alasan_pemberhentian_id,
                'status' => 'draft',
                'tgl_diajukan' => now(),
            ]);

            foreach ($request->bpd_ids as $bpd_id) {
                AjuanBpdPeserta::create([
                    'ajuan_bpd_id' => $ajuan->id,
                    'bpd_id' => $bpd_id,
                ]);
            }

            $templates = TemplateChecklistBpd::where('jenis_ajuan', $ajuan->jenis_ajuan)
                ->where('alasan_pemberhentian_id', $ajuan->alasan_pemberhentian_id)
                ->get();

            foreach ($templates as $template) {
                ChecklistAjuanBpd::create([
                    'ajuan_bpd_id' => $ajuan->id,
                    'template_checklist_bpd_id' => $template->id,
                    'status' => 'belum_diunggah',
                ]);
            }

            MilestoneAjuanBpd::create([
                'ajuan_bpd_id' => $ajuan->id,
                'tahapan' => 'Pengajuan Dibuat',
                'status' => 'selesai',
                'tgl_selesai' => now(),
            ]);

            return $ajuan;
        });

        return redirect()->route('desa.ajuan-bpd.show', $ajuan)->with('success', 'Ajuan BPD berhasil dibuat. Silakan lengkapi dan unggah dokumen persyaratan di bawah.');
    }

    public function show(AjuanBpd $ajuanBpd)
    {
        if ($ajuanBpd->desa_id !== auth()->user()->desa_id) {
            abort(403);
        }

        $ajuanBpd->load(['pesertas.bpd', 'checklists.templateChecklist', 'milestones']);

        return view('desa.ajuan-bpd.show', compact('ajuanBpd'));
    }

    public function uploadDokumen(Request $request, AjuanBpd $ajuanBpd, ChecklistAjuanBpd $checklist)
    {
        if ($ajuanBpd->desa_id !== auth()->user()->desa_id || $checklist->ajuan_bpd_id !== $ajuanBpd->id) {
            abort(403);
        }

        $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
        ], [
            'file.mimes' => 'File harus berupa PDF, JPG, JPEG, atau PNG.',
            'file.max' => 'Ukuran file maksimal 5MB.',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('uploads/bpd', $filename, 'public');

            $checklist->update([
                'file_path' => $path,
                'status' => 'menunggu_verifikasi',
                'updated_by' => auth()->id(),
                'versi' => $checklist->versi + 1,
            ]);

            // Auto update status ajuan jika diperlukan (contoh: jadi 'draft')
            if ($ajuanBpd->status == 'draft') {
                // do nothing for now
            }
        }

        return back()->with('success', 'Dokumen berhasil diunggah.');
    }

    public function bulkUpload(Request $request, AjuanBpd $ajuanBpd)
    {
        if ($ajuanBpd->desa_id !== auth()->user()->desa_id) {
            abort(403);
        }

        $isSubmit = $request->input('submit_ajuan') == '1';

        $request->validate([
            'berkas_zip' => 'nullable|file|mimes:zip,rar,pdf|max:51200', // 50MB max
        ], [
            'berkas_zip.mimes' => 'File keseluruhan harus berupa ZIP, RAR, atau PDF.',
            'berkas_zip.max' => 'Ukuran file maksimal 50MB.',
        ]);

        if (! in_array($ajuanBpd->status, ['draft', 'revisi'])) {
            return back()->with('error', 'Dokumen tidak dapat diubah karena ajuan sedang diproses.');
        }

        if ($request->hasFile('berkas_zip')) {
            $file = $request->file('berkas_zip');
            $safeNoReg = str_replace('/', '-', $ajuanBpd->no_registrasi ?? ('BPD-'.time()));
            $filename = $safeNoReg.'_berkas_persyaratan.'.$file->extension();

            if ($ajuanBpd->berkas_zip && Storage::disk('public')->exists($ajuanBpd->berkas_zip)) {
                Storage::disk('public')->delete($ajuanBpd->berkas_zip);
            }

            $path = $file->storeAs('uploads/bpd/'.$safeNoReg, $filename, 'public');

            $ajuanBpd->update([
                'berkas_zip' => $path,
            ]);
        }

        if ($isSubmit) {
            $ajuanBpd->update(['status' => 'menunggu_verifikasi']);

            MilestoneAjuanBpd::create([
                'ajuan_bpd_id' => $ajuanBpd->id,
                'tahapan' => 'Berkas Dikirim ke Dinpermasdes',
                'status' => 'selesai',
                'tgl_selesai' => now(),
            ]);

            return redirect()->route('desa.ajuan-bpd.index')->with('success', 'Ajuan BPD berhasil disubmit dan diteruskan ke Dinpermasdes!');
        }

        return back()->with('success', 'Draft dokumen berhasil disimpan.');
    }

    public function destroy(AjuanBpd $ajuanBpd)
    {
        if ($ajuanBpd->desa_id !== auth()->user()->desa_id) {
            abort(403);
        }

        // Hapus file ZIP jika ada
        if ($ajuanBpd->berkas_zip && Storage::disk('public')->exists($ajuanBpd->berkas_zip)) {
            Storage::disk('public')->delete($ajuanBpd->berkas_zip);
        }

        // Hapus file individual di checklist
        foreach ($ajuanBpd->checklists as $checklist) {
            if ($checklist->file_path && Storage::disk('public')->exists($checklist->file_path)) {
                Storage::disk('public')->delete($checklist->file_path);
            }
        }

        $ajuanBpd->delete();

        return redirect()->route('desa.ajuan-bpd.index')->with('success', 'Ajuan berhasil dihapus.');
    }
}
