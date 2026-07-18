<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Regulasi;
use Illuminate\Http\Request;

class RegulasiController extends Controller
{
    public function index()
    {
        $regulasis = Regulasi::with('desa')->latest()->paginate(15);
        return view('admin.regulasi.index', compact('regulasis'));
    }

    public function show(Regulasi $regulasi)
    {
        $regulasi->load('desa');
        return view('admin.regulasi.show', compact('regulasi'));
    }

    public function kembalikanUntukRevisi(Request $request, Regulasi $regulasi)
    {
        $request->validate([
            'file_catatan_dinas' => 'required|file|mimes:doc,docx,pdf|max:10240',
            'catatan' => 'required|string',
        ]);

        $pathCatatan = $request->file('file_catatan_dinas')->store('regulasi/catatan_dinas', 'public');

        $regulasi->update([
            'status' => 'perlu_revisi',
            'file_catatan_dinas' => $pathCatatan,
            'catatan_revisi' => $request->catatan,
        ]);

        return back()->with('warning', 'Draf dikembalikan ke desa dengan status Perlu Revisi.');
    }

    public function sahkanAturan(Request $request, Regulasi $regulasi)
    {
        $request->validate([
            'no_regulasi' => 'required|string|unique:regulasis,no_regulasi',
            'file_final' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $updateData = [
            'status' => 'disahkan',
            'no_regulasi' => $request->no_regulasi,
            'tgl_disahkan' => now(),
        ];

        if ($request->hasFile('file_final')) {
            $updateData['file_pdf'] = $request->file('file_final')->store('regulasi/pdf_final', 'public');
        }

        $regulasi->update($updateData);

        return redirect()->route('admin.regulasi.show', $regulasi)
            ->with('success', 'Aturan Resmi Disahkan dengan Nomor Lembaran: ' . $request->no_regulasi);
    }
}
