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
            'file' => 'required|file|mimes:doc,docx|max:10240'
        ]);

        $desaId = Auth::user()->desa_id;
        $path = $request->file('file')->store('regulasi/draft_desa', 'public');

        Regulasi::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tipe' => $request->tipe,
            'file_path' => $path,
            'status' => 'menunggu_evaluasi',
            'desa_id' => $desaId,
            'tgl_diajukan' => now(),
            'no_regulasi' => null,
        ]);

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
            'file_revisi' => 'required|file|mimes:doc,docx|max:10240',
            'file_pdf_sah' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $updateData = [
            'file_path' => $request->file('file_revisi')->store('regulasi/draft_desa', 'public'),
            'status' => 'evaluasi_lanjutan'
        ];

        if ($request->hasFile('file_pdf_sah')) {
            $updateData['file_pdf'] = $request->file('file_pdf_sah')->store('regulasi/pdf_final', 'public');
        }

        $regulasi->update($updateData);

        return back()->with('success', 'Rancangan revisi telah diserahkan kembali ke Dinpermasdes.');
    }
}
