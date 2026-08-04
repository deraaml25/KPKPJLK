<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\Regulasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegulasiController extends Controller
{
    public function index()
    {
        $desaId = Auth::user()->desa_id;
        $regulasis = Regulasi::where('desa_id', $desaId)->latest()->paginate(15);

        return view('desa.regulasi.index', compact('regulasis'));
    }

    public function create()
    {
        return view('desa.regulasi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'tipe' => 'required|in:perdes,perkades,sk_kades',
        ]);

        if (! $request->hasFile('file') || ! $request->file('file')) {
            // Try getting it from allFiles directly
            $files = $request->allFiles();
            if (! isset($files['file'])) {
                return back()->withErrors(['file' => 'File dokumen wajib diunggah.'])->withInput();
            }
        }

        $uploadedFile = $request->file('file') ?? $request->allFiles()['file'];
        $ext = strtolower($uploadedFile->getClientOriginalExtension());

        \Log::info('REGULASI FILE EXT', ['ext' => $ext, 'original_name' => $uploadedFile->getClientOriginalName(), 'error' => $uploadedFile->getError()]);

        if (! in_array($ext, ['doc', 'docx'])) {
            return back()->withErrors(['file' => 'File harus berupa dokumen Word (.doc atau .docx). Format lain tidak diterima.'])->withInput();
        }

        $desaId = Auth::user()->desa_id;
        $path = $uploadedFile->store('regulasi/draft_desa', 'public');

        \Log::info('REGULASI FILE STORED', ['path' => $path]);

        // Auto-generate no_regulasi
        $prefix = match ($request->tipe) {
            'perdes' => 'PRD',
            'perkades' => 'PKD',
            'sk_kades' => 'SKK',
            default => 'REG'
        };
        $year = date('Y');
        $month = date('m');

        $latestReg = Regulasi::where('no_regulasi', 'like', "{$prefix}/{$year}/{$month}/%")
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = '0001';
        if ($latestReg && preg_match('/(\d{4})$/', $latestReg->no_regulasi, $matches)) {
            $nextNumber = str_pad(intval($matches[1]) + 1, 4, '0', STR_PAD_LEFT);
        }

        $noRegulasi = "{$prefix}/{$year}/{$month}/{$nextNumber}";

        $reg = Regulasi::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tipe' => $request->tipe,
            'file_path' => $path,
            'status' => 'menunggu_evaluasi',
            'desa_id' => $desaId,
            'tgl_diajukan' => now(),
            'no_regulasi' => $noRegulasi,
        ]);

        \Log::info('REGULASI CREATED', ['id' => $reg->id]);

        return redirect()->route('desa.regulasi.index')->with('success', 'Draf aturan berhasil dikirim. Menunggu evaluasi Dinpermasdes.');
    }

    public function show(Regulasi $regulasi)
    {
        if ($regulasi->desa_id !== Auth::user()->desa_id) {
            abort(403);
        }

        return view('desa.regulasi.show', compact('regulasi'));
    }

    public function kirimRevisi(Request $request, Regulasi $regulasi)
    {
        if ($regulasi->desa_id !== Auth::user()->desa_id || $regulasi->status !== 'perlu_revisi') {
            abort(403);
        }

        $request->validate([
            'file_revisi' => 'required|file|max:10240',
            'file_pdf_sah' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $extRevisi = strtolower($request->file('file_revisi')->getClientOriginalExtension());
        if (! in_array($extRevisi, ['doc', 'docx'])) {
            return back()->withErrors(['file_revisi' => 'File harus berupa dokumen Word (.doc atau .docx).']);
        }

        $updateData = [
            'file_path' => $request->file('file_revisi')->store('regulasi/draft_desa', 'public'),
            'status' => 'evaluasi_lanjutan',
        ];

        if ($request->hasFile('file_pdf_sah')) {
            $updateData['file_pdf'] = $request->file('file_pdf_sah')->store('regulasi/pdf_final', 'public');
        }

        $regulasi->update($updateData);

        return back()->with('success', 'Rancangan revisi telah diserahkan kembali ke Dinpermasdes.');
    }

    public function sahkanAturan(Request $request, Regulasi $regulasi)
    {
        if ($regulasi->desa_id !== Auth::user()->desa_id || $regulasi->status !== 'disetujui') {
            abort(403);
        }

        $request->validate([
            'file_final' => 'required|file|mimes:pdf|max:10240',
        ]);

        $regulasi->update([
            'status' => 'disahkan',
            'tgl_disahkan' => now(),
            'file_pdf' => $request->file('file_final')->store('regulasi/pdf_final', 'public'),
        ]);

        return back()->with('success', 'Aturan Resmi Disahkan dengan Nomor Lembaran: '.$regulasi->no_regulasi);
    }
}
