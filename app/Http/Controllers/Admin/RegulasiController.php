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

    public function approve(Request $request, Regulasi $regulasi)
    {
        $regulasi->update([
            'status' => 'disahkan',
            'tgl_disahkan' => now(),
            'catatan_revisi' => $request->catatan_revisi
        ]);
        return redirect()->route('admin.regulasi.show', $regulasi)->with('success', 'Regulasi berhasil disahkan.');
    }
}
