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
            if (! in_array($ext, ['doc', 'docx', 'pdf'])) {
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

    public function setujuiDraft(Regulasi $regulasi)
    {
        $regulasi->update([
            'status' => 'disetujui',
        ]);

        return redirect()->route('admin.regulasi.show', $regulasi)
            ->with('success', 'Draf Regulasi telah disetujui dan diteruskan kembali ke desa untuk disahkan.');
    }

    public function destroy(Regulasi $regulasi)
    {
        if ($regulasi->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($regulasi->file_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($regulasi->file_path);
        }
        if ($regulasi->file_revisi && \Illuminate\Support\Facades\Storage::disk('public')->exists($regulasi->file_revisi)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($regulasi->file_revisi);
        }
        if ($regulasi->file_pdf && \Illuminate\Support\Facades\Storage::disk('public')->exists($regulasi->file_pdf)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($regulasi->file_pdf);
        }

        $regulasi->delete();

        return redirect()->route('admin.regulasi.index')->with('success', 'Draf Regulasi berhasil dihapus oleh Admin.');
    }
}
