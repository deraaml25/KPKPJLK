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
            'file_catatan_dinas' => 'nullable|file|max:10240',
            'catatan' => 'required|string',
        ]);

        if ($request->hasFile('file_catatan_dinas')) {
            $ext = strtolower($request->file('file_catatan_dinas')->getClientOriginalExtension());
            if (!in_array($ext, ['doc', 'docx', 'pdf'])) {
                return back()->withErrors(['file_catatan_dinas' => 'File harus berupa dokumen Word atau PDF.']);
            }
        }

        $updateData = [
            'status' => 'perlu_revisi',
            'catatan_revisi' => $request->catatan,
        ];

        if ($request->hasFile('file_catatan_dinas')) {
            $updateData['file_catatan_dinas'] = $request->file('file_catatan_dinas')->store('regulasi/catatan_dinas', 'public');
        }

        $regulasi->update($updateData);

        return back()->with('warning', 'Draf dikembalikan ke desa dengan status Perlu Revisi.');
    }

    public function sahkanAturan(Request $request, Regulasi $regulasi)
    {
        $request->validate([
            'no_regulasi' => 'required|string|unique:regulasis,no_regulasi',
            'file_final' => 'nullable|file|max:10240',
        ]);

        if ($request->hasFile('file_final')) {
            $ext = strtolower($request->file('file_final')->getClientOriginalExtension());
            if ($ext !== 'pdf') {
                return back()->withErrors(['file_final' => 'File final harus berupa PDF.']);
            }
        }

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
