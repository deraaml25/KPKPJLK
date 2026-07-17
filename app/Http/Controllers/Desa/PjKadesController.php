<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\PjKades;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PjKadesController extends Controller
{
    public function index()
    {
        $desaId = Auth::user()->desa_id;
        $pjkades = PjKades::where('desa_id', $desaId)->latest()->get();
        return view('desa.pjkades.index', compact('pjkades'));
    }

    public function create()
    {
        return view('desa.pjkades.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pns' => 'required',
            'nip' => 'required',
            'pangkat' => 'required',
            'riwayat_hidup' => 'required|file|mimes:pdf|max:10240',
            'sk_pangkat' => 'required|file|mimes:pdf|max:10240',
        ]);

        $desaId = Auth::user()->desa_id;
        $cvPath = $request->file('riwayat_hidup')->store('pjkades/cv', 'public');
        $skPath = $request->file('sk_pangkat')->store('pjkades/sk_pangkat', 'public');

        PjKades::create([
            'desa_id' => $desaId,
            'nama_pns' => $request->nama_pns,
            'nip' => $request->nip,
            'pangkat' => $request->pangkat,
            'riwayat_hidup_path' => $cvPath,
            'sk_pangkat_path' => $skPath,
            'status_bebas_hukdis' => 'pending',
            'status' => 'submitted',
        ]);

        return redirect()->route('desa.pjkades.index')->with('success', 'Usulan Pj Kepala Desa berhasil dikirim.');
    }
}
