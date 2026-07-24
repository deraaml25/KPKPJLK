<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArsipRekom;
use App\Models\Ajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArsipRekomController extends Controller
{
    public function index(Request $request)
    {
        $arsips = ArsipRekom::with(['ajuan.desa.kecamatan', 'ajuan.jenisLayanan', 'ajuan.pesertas.perangkatDesa'])
            ->latest()
            ->paginate(15);

        return view('admin.arsip.index', compact('arsips'));
    }

    public function create(Ajuan $ajuan)
    {
        $ajuan->load(['desa', 'jenisLayanan', 'pesertas.perangkatDesa']);

        return view('admin.arsip.create', compact('ajuan'));
    }

    public function store(Request $request, Ajuan $ajuan)
    {
        $request->validate([
            'no_surat_rekom' => ['required', 'string', 'max:100'],
            'file_rekom' => ['required', 'file', 'mimes:pdf', 'max:20480'],
        ]);

        // Pastikan folder ada
        Storage::disk('public')->makeDirectory($ajuan->folder_path);

        $safeNoReg = str_replace('/', '-', $ajuan->no_registrasi);
        $path = $request->file('file_rekom')->storeAs(
            $ajuan->folder_path,
            $safeNoReg . '_REKOM.pdf',
            'public'
        );

        ArsipRekom::updateOrCreate(
            ['ajuan_id' => $ajuan->id],
            [
                'no_surat_rekom' => $request->no_surat_rekom,
                'file_path' => $path,
                'uploaded_by' => Auth::id(),
            ]
        );

        $ajuan->update(['status' => 'selesai']);

        return redirect()->route('admin.arsip.index')
            ->with('success', 'Arsip rekomendasi berhasil diunggah. Ajuan telah ditandai selesai.');
    }

    public function download(ArsipRekom $arsipRekom)
    {
        if (!Storage::disk('public')->exists($arsipRekom->file_path)) {
            return back()->with('error', 'File tidak ditemukan di server.');
        }

        return Storage::disk('public')->download($arsipRekom->file_path);
    }
}
