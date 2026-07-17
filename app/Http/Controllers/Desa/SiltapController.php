<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\Siltap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiltapController extends Controller
{
    public function index()
    {
        $desaId = Auth::user()->desa_id;
        $siltaps = Siltap::where('desa_id', $desaId)->latest()->get();
        return view('desa.siltap.index', compact('siltaps'));
    }

    public function create()
    {
        return view('desa.siltap.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020',
            'rekomendasi_camat' => 'required|file|mimes:pdf|max:10240',
            'bukti_bpjs' => 'required|file|mimes:pdf|max:10240',
            'spj_sebelumnya' => 'required|file|mimes:pdf|max:10240',
        ]);

        $desaId = Auth::user()->desa_id;

        // Auto-lock system: check if they have locking requirements such as late previous SPJ or APBDes verification (mock check)
        // If they did something wrong or late, we could lock them. But for now they submit.
        $rekomPath = $request->file('rekomendasi_camat')->store('siltap/rekomendasi', 'public');
        $bpjsPath = $request->file('bukti_bpjs')->store('siltap/bpjs', 'public');
        $spjPath = $request->file('spj_sebelumnya')->store('siltap/spj', 'public');

        Siltap::create([
            'desa_id' => $desaId,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'rekomendasi_camat_path' => $rekomPath,
            'bukti_bpjs_path' => $bpjsPath,
            'spj_path' => $spjPath,
            'status' => 'submitted', // Auto-submitted to agency
        ]);

        return redirect()->route('desa.siltap.index')->with('success', 'Usulan pencairan Siltap berhasil dikirim.');
    }

    public function show(Siltap $siltap)
    {
        if ($siltap->desa_id !== Auth::user()->desa_id) {
            abort(403);
        }
        return view('desa.siltap.show', compact('siltap'));
    }
}
