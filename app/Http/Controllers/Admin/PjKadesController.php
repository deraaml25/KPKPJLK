<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PjKades;
use Illuminate\Http\Request;

class PjKadesController extends Controller
{
    public function index()
    {
        $pjkades = PjKades::with('desa')->latest()->paginate(15);
        return view('admin.pjkades.index', compact('pjkades'));
    }

    public function show(PjKades $pjkades)
    {
        $pjkades->load('desa');
        return view('admin.pjkades.show', compact('pjkades'));
    }

    public function generateSk(Request $request, PjKades $pjkades)
    {
        $request->validate([
            'status_bebas_hukdis' => 'required|in:clean,has_issues',
        ]);

        if ($request->status_bebas_hukdis === 'has_issues') {
            $pjkades->update([
                'status_bebas_hukdis' => 'has_issues',
                'status' => 'rejected'
            ]);
            return redirect()->route('admin.pjkades.show', $pjkades)->with('error', 'Status PNS tidak bebas hukuman disiplin. Usulan ditolak.');
        }

        // Mock SK Bupati PDF Generation
        $fileName = 'SK_Bupati_PjKades_' . $pjkades->id . '.pdf';
        $path = 'pjkades/sk/' . $fileName;

        $pjkades->update([
            'status_bebas_hukdis' => 'clean',
            'sk_bupati_path' => $path,
            'status' => 'approved'
        ]);

        return redirect()->route('admin.pjkades.show', $pjkades)->with('success', 'SK Bupati Pj Kades berhasil digenerate dan disetujui.');
    }
}
