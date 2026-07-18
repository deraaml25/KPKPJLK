<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\Ajuan;
use App\Models\ChecklistAjuan;
use App\Models\JenisLayanan;
use App\Models\AlasanPemberhentian;
use App\Models\PerangkatDesa;
use App\Models\TemplateChecklist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AjuanController extends Controller
{
    public function index()
    {
        $desaId = Auth::user()->desa_id;
        $ajuans = Ajuan::with(['jenisLayanan', 'pesertas.perangkatDesa', 'milestoneTrackings'])
            ->where('desa_id', $desaId)
            ->latest()
            ->paginate(15);

        return view('desa.ajuan.index', compact('ajuans'));
    }

    public function create()
    {
        $jenisLayanans = JenisLayanan::all();
        $alasanPemberhentians = AlasanPemberhentian::all();
        $perangkatDesas = PerangkatDesa::where('desa_id', Auth::user()->desa_id)
            ->where('status_aktif', true)
            ->where('jabatan', '!=', 'Kepala Desa')
            ->get();

        return view('desa.ajuan.create', compact('jenisLayanans', 'alasanPemberhentians', 'perangkatDesas'));
    }

    public function store(Request $request)
    {
        $rotasiLayanan = JenisLayanan::where('nama', 'Rotasi')->first();

        $request->validate([
            'jenis_layanan_id' => ['required', 'exists:jenis_layanans,id'],
            'alasan_pemberhentian_id' => ['nullable', 'exists:alasan_pemberhentians,id'],
            'pesertas' => ['required', 'array', 'min:1'],
            'pesertas.*.perangkat_desa_id' => ['required', 'exists:perangkat_desas,id'],
            'pesertas.*.jabatan_baru' => ['nullable', 'required_if:jenis_layanan_id,' . ($rotasiLayanan->id ?? 0), 'string', 'max:255'],
        ], [
            'pesertas.*.jabatan_baru.required_if' => 'Jabatan tujuan harus diisi untuk layanan Rotasi.',
            'pesertas.min' => 'Minimal 1 (satu) orang perangkat desa harus didaftarkan.',
        ]);

        $desa = Auth::user()->desa;
        $jenisLayanan = JenisLayanan::find($request->jenis_layanan_id);

        // Generate no registrasi
        $prefix = match ($jenisLayanan->nama) {
            'Pengangkatan' => 'PGKT',
            'Rotasi' => 'ROT',
            'Pemberhentian' => 'PBRH',
            default => 'AJU',
        };
        $noRegistrasi = $prefix . '/' . now()->format('Y') . '/' . now()->format('m') . '/' . str_pad(Ajuan::count() + 1, 4, '0', STR_PAD_LEFT);

        // Hitung SLA batas (20 hari kerja dari hari ini)
        $tglBatas = $this->hitungHariKerja(now(), 20);

        // Build folder path
        $kecamatan = Str::slug($desa->kecamatan->nama_kecamatan);
        $desaNama = Str::slug($desa->nama_desa);
        $jenis = Str::slug($jenisLayanan->nama);
        $folderPath = "dokumen/{$kecamatan}/{$desaNama}/{$jenis}/{$noRegistrasi}";

        $isDraft = $request->has('draft');

        $ajuan = Ajuan::create([
            'no_registrasi' => $noRegistrasi,
            'desa_id' => $desa->id,
            'jenis_layanan_id' => $request->jenis_layanan_id,
            'alasan_pemberhentian_id' => $request->alasan_pemberhentian_id,
            'status' => 'draft',
            'folder_path' => $folderPath,
            'tgl_diajukan' => now()->toDateString(),
            'tgl_sla_batas' => $tglBatas,
        ]);

        // Simpan Bulk Pesertas (Kolektif)
        foreach ($request->pesertas as $peserta) {
            \App\Models\AjuanPeserta::create([
                'ajuan_id' => $ajuan->id,
                'perangkat_desa_id' => $peserta['perangkat_desa_id'],
                'jabatan_baru' => $jenisLayanan->nama === 'Rotasi' ? ($peserta['jabatan_baru'] ?? null) : null,
            ]);
        }

        // Buat checklist_ajuan dari template
        $checklists = TemplateChecklist::where('jenis_layanan_id', $request->jenis_layanan_id)
            ->where(function ($q) use ($request) {
                $q->whereNull('alasan_pemberhentian_id')
                    ->orWhere('alasan_pemberhentian_id', $request->alasan_pemberhentian_id);
            })
            ->orderBy('urutan')
            ->get();

        foreach ($checklists as $template) {
            ChecklistAjuan::create([
                'ajuan_id' => $ajuan->id,
                'template_checklist_id' => $template->id,
                'status' => 'belum_diunggah',
                'versi' => 1,
            ]);
        }

        if ($isDraft) {
            return redirect()->route('desa.ajuan.show', $ajuan)->with('success', 'Draft ajuan tersimpan.');
        }

        return redirect()->route('desa.ajuan.show', $ajuan)
            ->with('success', 'Ajuan berhasil disubmit! No. Registrasi: ' . $noRegistrasi . '. Silakan lengkapi dan unggah dokumen persyaratan di bawah.');
    }

    public function show(Ajuan $ajuan)
    {
        // Gate: hanya desa pemilik yang bisa lihat
        if ($ajuan->desa_id !== Auth::user()->desa_id) {
            abort(403, 'Anda tidak memiliki akses ke ajuan ini.');
        }

        $ajuan->load([
            'jenisLayanan',
            'alasanPemberhentian',
            'pesertas.perangkatDesa',
            'checklistAjuans.templateChecklist',
            'milestoneTrackings',
            'arsipRekom',
        ]);

        $tahapAktif = $this->hitungTahapAktif($ajuan->milestoneTrackings);

        return view('desa.ajuan.show', compact('ajuan', 'tahapAktif'));
    }

    public function uploadDokumen(Request $request, Ajuan $ajuan, ChecklistAjuan $checklistAjuan)
    {
        if ($ajuan->desa_id !== Auth::user()->desa_id) {
            abort(403);
        }

        $request->validate([
            'dokumen' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        // Enforcement: Rule Immutable status
        if (!in_array($ajuan->status, ['draft', 'direvisi'])) {
            return back()->with('error', 'Dokumen tidak dapat diubah karena ajuan sedang diproses oleh dinas.');
        }

        // Pastikan folder ada
        Storage::disk('public')->makeDirectory($ajuan->folder_path);

        $template = $checklistAjuan->templateChecklist;
        $urutan = str_pad($template->urutan, 2, '0', STR_PAD_LEFT);
        $ext = $request->file('dokumen')->extension();
        $filename = $urutan . '_' . Str::slug($template->nama_dokumen) . '.' . $ext;

        // Jika ada file lama, hapus dulu
        if ($checklistAjuan->file_path && Storage::disk('public')->exists($checklistAjuan->file_path)) {
            Storage::disk('public')->delete($checklistAjuan->file_path);
        }

        $path = $request->file('dokumen')->storeAs(
            $ajuan->folder_path,
            $filename,
            'public'
        );

        $checklistAjuan->update([
            'file_path' => $path,
            'status' => 'pending',
            'versi' => $checklistAjuan->versi,
        ]);

        return back()->with('success', 'Dokumen "' . $template->nama_dokumen . '" berhasil diunggah. Menunggu verifikasi Dinpermasdes.');
    }

    public function bulkUpload(Request $request, Ajuan $ajuan)
    {
        if ($ajuan->desa_id !== Auth::user()->desa_id) {
            abort(403);
        }

        $isSubmit = $request->has('submit_ajuan');

        $request->validate([
            'dokumen' => ['nullable', 'array'],
            'dokumen.*' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        // Enforcement: Rule Immutable status
        if (!in_array($ajuan->status, ['draft', 'direvisi'])) {
            return back()->with('error', 'Dokumen tidak dapat diubah karena ajuan sedang diproses oleh dinas.');
        }

        Storage::disk('public')->makeDirectory($ajuan->folder_path);

        if ($request->hasFile('dokumen')) {
            foreach ($request->file('dokumen') as $checklistAjuanId => $file) {
                $checklistAjuan = $ajuan->checklistAjuans()->find($checklistAjuanId);
                if (!$checklistAjuan) {
                    continue;
                }

                $template = $checklistAjuan->templateChecklist;
                $urutan = str_pad($template->urutan, 2, '0', STR_PAD_LEFT);
                $ext = $file->extension();
                $filename = $urutan . '_' . Str::slug($template->nama_dokumen) . '.' . $ext;

                if ($checklistAjuan->file_path && Storage::disk('public')->exists($checklistAjuan->file_path)) {
                    Storage::disk('public')->delete($checklistAjuan->file_path);
                }

                $path = $file->storeAs(
                    $ajuan->folder_path,
                    $filename,
                    'public'
                );

                $checklistAjuan->update([
                    'file_path' => $path,
                    'status' => 'pending',
                ]);
            }
        }

        if ($isSubmit) {
            $ajuan->update(['status' => 'submitted']);
            return redirect()->route('desa.ajuan.index')->with('success', 'Ajuan berhasil disubmit dan diteruskan ke Dinpermasdes!');
        }

        return back()->with('success', 'Draft dokumen berhasil disimpan.');
    }

    private function hitungTahapAktif($milestoneTrackings): int
    {
        if ($milestoneTrackings->isEmpty()) {
            return 1;
        }

        $tahapBelumSelesai = $milestoneTrackings
            ->whereNull('tgl_selesai')
            ->min('tahap');

        if ($tahapBelumSelesai) {
            return (int) $tahapBelumSelesai;
        }

        $maxTahap = $milestoneTrackings->max('tahap');

        return min((int) $maxTahap + 1, 9);
    }

    private function hitungHariKerja(Carbon $dari, int $jumlahHari): Carbon
    {
        $tanggal = $dari->copy();
        $hitung = 0;
        while ($hitung < $jumlahHari) {
            $tanggal->addDay();
            if (!$tanggal->isWeekend()) {
                $hitung++;
            }
        }

        return $tanggal;
    }
}
