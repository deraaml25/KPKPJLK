<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\AlasanPemberhentian;
use App\Models\ChecklistPjKades;
use App\Models\JenisLayanan;
use App\Models\PerangkatDesa;
use App\Models\PjKades;
use App\Models\TemplateChecklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PjKadesController extends Controller
{
    public function index()
    {
        $desaId = Auth::user()->desa_id;
        $pjkades = PjKades::withoutGlobalScopes()
            ->with(['alasanPemberhentian', 'checklists'])
            ->where('desa_id', $desaId)
            ->latest()
            ->paginate(15);

        return view('desa.pjkades.index', compact('pjkades'));
    }

    public function create()
    {
        $desaId = Auth::user()->desa_id;

        // Ambil Jenis Layanan SK Kades
        $layananPj = JenisLayanan::where('nama', 'LIKE', '%Pj Kades%')->first();
        $layananPlt = JenisLayanan::where('nama', 'LIKE', '%Plt Kades%')->first();

        // Alasan Pj Kades (Definitif)
        $alasanPj = AlasanPemberhentian::whereIn('nama', [
            'Meninggal Dunia',
            'Permintaan Sendiri',
            'Diberhentikan Dengan Tidak Hormat',
        ])->get();

        // Alasan Plt Kades (Sementara / Cuti)
        $alasanPlt = AlasanPemberhentian::whereIn('nama', [
            'Pemberhentian Sementara',
            'Cuti Sakit',
            'Cuti Umroh / Haji',
            'Cuti Tahunan',
            'Cuti Bersalin',
            'Cuti Alasan Penting',
        ])->get();

        // List Perangkat Desa untuk Opsi Sekdes (Plt)
        $perangkatDesas = PerangkatDesa::where('desa_id', $desaId)
            ->where('status_aktif', true)
            ->get();

        return view('desa.pjkades.create', compact(
            'layananPj',
            'layananPlt',
            'alasanPj',
            'alasanPlt',
            'perangkatDesas'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori' => ['required', 'in:pj_kades,plt_kades'],
            'alasan_pemberhentian_id' => ['required', 'exists:alasan_pemberhentians,id'],

            // Pj Kades Validation
            'nama_pns' => ['required_if:kategori,pj_kades', 'nullable', 'string', 'max:255'],
            'nip' => ['required_if:kategori,pj_kades', 'nullable', 'string', 'max:30'],
            'pangkat' => ['required_if:kategori,pj_kades', 'nullable', 'string', 'max:100'],

            // Plt Kades Validation
            'nama_plt' => ['required_if:kategori,plt_kades', 'nullable', 'string', 'max:255'],
            'nip_plt' => ['nullable', 'string', 'max:30'],
            'pangkat_plt' => ['nullable', 'string', 'max:100'],
        ]);

        $desa = Auth::user()->desa;
        $alasan = AlasanPemberhentian::findOrFail($request->alasan_pemberhentian_id);
        $kategori = $request->kategori;

        // Tentukan Jenis Layanan
        $jenisLayananNama = $kategori === 'pj_kades'
            ? 'Pj Kades (Pemberhentian Definitif & Penunjukan Pj)'
            : 'Plt Kades (Pemberhentian Sementara / Cuti & Penunjukan Plt)';

        $jenisLayanan = JenisLayanan::where('nama', $jenisLayananNama)->first();

        // No Registrasi SK Kades
        $prefix = $kategori === 'pj_kades' ? 'PJK' : 'PLT';
        $noRegistrasi = $prefix.'/'.now()->format('Y').'/'.now()->format('m').'/'.str_pad(PjKades::count() + 1, 4, '0', STR_PAD_LEFT);

        $kecamatanSlug = Str::slug($desa->kecamatan->nama_kecamatan ?? 'kecamatan');
        $desaSlug = Str::slug($desa->nama_desa);
        $folderPath = "dokumen/{$kecamatanSlug}/{$desaSlug}/sk-kades-{$kategori}";

        $pjKades = PjKades::create([
            'desa_id' => $desa->id,
            'kategori' => $kategori,
            'alasan_pemberhentian_id' => $alasan->id,
            'alasan_nama' => $alasan->nama,
            'no_registrasi' => $noRegistrasi,
            'nama_pns' => $kategori === 'pj_kades' ? $request->nama_pns : null,
            'nip' => $kategori === 'pj_kades' ? $request->nip : null,
            'pangkat' => $kategori === 'pj_kades' ? $request->pangkat : null,
            'nama_plt' => $kategori === 'plt_kades' ? $request->nama_plt : null,
            'nip_plt' => $kategori === 'plt_kades' ? $request->nip_plt : null,
            'pangkat_plt' => $kategori === 'plt_kades' ? $request->pangkat_plt : null,
            'folder_path' => $folderPath,
            'tgl_diajukan' => now()->toDateString(),
            'status_bebas_hukdis' => 'pending',
            'status' => 'draft',
        ]);

        // Generate Checklist Items dari TemplateChecklist
        $templates = TemplateChecklist::where('jenis_layanan_id', $jenisLayanan->id ?? 0)
            ->where('alasan_pemberhentian_id', $alasan->id)
            ->orderBy('urutan')
            ->get();

        foreach ($templates as $tmpl) {
            ChecklistPjKades::create([
                'pj_kades_id' => $pjKades->id,
                'template_checklist_id' => $tmpl->id,
                'nama_dokumen' => $tmpl->nama_dokumen,
                'wajib' => $tmpl->wajib,
                'urutan' => $tmpl->urutan,
                'status_verifikasi' => 'pending',
            ]);
        }

        return redirect()->route('desa.pjkades.show', $pjKades->id)
            ->with('success', 'Usulan SK Kades berhasil dibuat. Silakan lengkapi unggahan dokumen persyaratan di bawah ini.');
    }

    public function show($id)
    {
        $desaId = Auth::user()->desa_id;
        $pjkades = PjKades::withoutGlobalScopes()
            ->with(['alasanPemberhentian', 'checklists'])
            ->where('desa_id', $desaId)
            ->findOrFail($id);

        return view('desa.pjkades.show', compact('pjkades'));
    }

    public function uploadChecklist(Request $request, $id, $checklistId)
    {
        $desaId = Auth::user()->desa_id;
        $pjKades = PjKades::withoutGlobalScopes()
            ->where('desa_id', $desaId)
            ->findOrFail($id);

        $checklist = ChecklistPjKades::where('pj_kades_id', $pjKades->id)
            ->findOrFail($checklistId);

        $request->validate([
            'file_dokumen' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ], [
            'file_dokumen.required' => 'File dokumen wajib dipilih.',
            'file_dokumen.mimes' => 'Format file harus berupa PDF atau Gambar (JPG/PNG).',
            'file_dokumen.max' => 'Ukuran file tidak boleh melebihi 10MB.',
        ]);

        if ($checklist->file_path && Storage::disk('public')->exists($checklist->file_path)) {
            Storage::disk('public')->delete($checklist->file_path);
        }

        $filename = Str::slug($checklist->nama_dokumen).'_'.time().'.'.$request->file('file_dokumen')->getClientOriginalExtension();
        $path = $request->file('file_dokumen')->storeAs($pjKades->folder_path ?? 'dokumen/sk-kades', $filename, 'public');

        $checklist->update([
            'file_path' => $path,
            'status_verifikasi' => 'pending',
            'catatan_revisi' => null,
            'tgl_diunggah' => now(),
        ]);

        return back()->with('success', "Dokumen {$checklist->nama_dokumen} berhasil diunggah.");
    }

    public function submitUsulan($id)
    {
        $desaId = Auth::user()->desa_id;
        $pjKades = PjKades::withoutGlobalScopes()
            ->where('desa_id', $desaId)
            ->findOrFail($id);

        // Cek kelengkapan dokumen wajib
        $unuploadedWajib = $pjKades->checklists()->where('wajib', true)->whereNull('file_path')->count();
        if ($unuploadedWajib > 0) {
            return back()->with('error', "Masih terdapat {$unuploadedWajib} dokumen wajib yang belum diunggah. Mohon lengkapi seluruh berkas terlebih dahulu.");
        }

        $pjKades->update([
            'status' => 'submitted',
            'tgl_diajukan' => now()->toDateString(),
        ]);

        return redirect()->route('desa.pjkades.index')
            ->with('success', 'Usulan SK Kades berhasil dikirim ke Dinpermasdes untuk proses verifikasi.');
    }
}
