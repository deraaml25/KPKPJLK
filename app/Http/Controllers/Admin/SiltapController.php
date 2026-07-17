<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siltap;
use Illuminate\Http\Request;

class SiltapController extends Controller
{
    public function index()
    {
        $siltaps = Siltap::with('desa')->latest()->paginate(15);
        return view('admin.siltap.index', compact('siltaps'));
    }

    public function show(Siltap $siltap)
    {
        $siltap->load('desa');
        return view('admin.siltap.show', compact('siltap'));
    }

    public function approve(Request $request, Siltap $siltap)
    {
        $request->validate([
            'sp2d' => 'required|file|mimes:pdf|max:10240'
        ]);

        $path = $request->file('sp2d')->store('siltap/sp2d', 'public');

        $siltap->update([
            'status' => 'approved',
            'sp2d_path' => $path,
            'notes' => $request->notes
        ]);

        return redirect()->route('admin.siltap.show', $siltap)->with('success', 'Usulan Siltap disetujui berkas SP2D terunggah.');
    }
}
