<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\PerangkatDesa;
use App\Models\Siltap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiltapController extends Controller
{
    public function index()
    {
        $desaId = Auth::user()->desa_id;
        $siltaps = Siltap::where('desa_id', $desaId)->latest()->get();

        // Cek apakah boleh submit bulan ini
        $bulanIni = (int) now()->format('m');
        $tahunIni = (int) now()->format('Y');
        $canSubmitCheck = Siltap::canSubmit($desaId, $bulanIni, $tahunIni);

        return view('desa.siltap.index', compact('siltaps', 'canSubmitCheck', 'bulanIni', 'tahunIni'));
    }

    public function create()
    {
        $desaId = Auth::user()->desa_id;
        $bulanIni = (int) now()->format('m');
        $tahunIni = (int) now()->format('Y');

        $canSubmitCheck = Siltap::canSubmit($desaId, $bulanIni, $tahunIni);

        if (! $canSubmitCheck['allowed']) {
            return redirect()->route('desa.siltap.index')->with('error', $canSubmitCheck['reason']);
        }

        $jumlahPerangkatAktif = PerangkatDesa::where('desa_id', $desaId)->where('status_aktif', true)->count();

        return view('desa.siltap.create', compact('bulanIni', 'tahunIni', 'jumlahPerangkatAktif'));
    }

    public function store(Request $request)
    {
        $desaId = Auth::user()->desa_id;

        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020',
            'rekomendasi_camat' => 'required|file|mimes:pdf|max:10240',
            'bukti_bpjs' => 'required|file|mimes:pdf|max:10240',
            'spj_sebelumnya' => 'required|file|mimes:pdf|max:10240',
        ]);

        // Auto-lock enforcement di backend
        $canSubmitCheck = Siltap::canSubmit($desaId, $request->bulan, $request->tahun);
        if (! $canSubmitCheck['allowed']) {
            return back()->withErrors(['lock' => $canSubmitCheck['reason']]);
        }

        // Snapshot jumlah perangkat aktif saat ini
        $jumlahPerangkat = PerangkatDesa::where('desa_id', $desaId)->where('status_aktif', true)->count();

        Siltap::create([
            'desa_id' => $desaId,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'jumlah_perangkat_aktif' => $jumlahPerangkat,
            'rekomendasi_camat_path' => $request->file('rekomendasi_camat')->store('siltap/rekomendasi', 'public'),
            'bukti_bpjs_path' => $request->file('bukti_bpjs')->store('siltap/bpjs', 'public'),
            'spj_path' => $request->file('spj_sebelumnya')->store('siltap/spj', 'public'),
            'status' => 'menunggu_verifikasi',
        ]);

        return redirect()->route('desa.siltap.index')->with('success', 'Usulan pencairan Siltap bulan '.$request->bulan.'/'.$request->tahun.' berhasil dikirim ke Dinpermasdes.');
    }

    public function show(Siltap $siltap)
    {
        if ($siltap->desa_id !== Auth::user()->desa_id) {
            abort(403);
        }

        return view('desa.siltap.show', compact('siltap'));
    }
}
