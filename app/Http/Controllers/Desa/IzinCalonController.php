<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\IzinCalon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IzinCalonController extends Controller
{
    public function index()
    {
        $desaId = Auth::user()->desa_id;
        $izins = IzinCalon::where('desa_id', $desaId)->latest()->get();
        return view('desa.izincalon.index', compact('izins'));
    }

    public function create()
    {
        return view('desa.izincalon.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_calon' => 'required',
            'jabatan_sekarang' => 'required',
            'jenis_calon' => 'required|in:kades,perangkat,pns',
            'berkas_syarat' => 'required|file|mimes:pdf|max:10240',
            'bebas_temuan_inspektorat' => 'required|file|mimes:pdf|max:10240',
        ]);

        $desaId = Auth::user()->desa_id;
        $berkasPath = $request->file('berkas_syarat')->store('izincalon/berkas', 'public');
        $bebasPath = $request->file('bebas_temuan_inspektorat')->store('izincalon/inspektorat', 'public');

        IzinCalon::create([
            'desa_id' => $desaId,
            'nama_calon' => $request->nama_calon,
            'jabatan_sekarang' => $request->jabatan_sekarang,
            'jenis_calon' => $request->jenis_calon,
            'berkas_syarat_path' => $berkasPath,
            'bebas_temuan_inspektorat_path' => $bebasPath,
            'status' => 'submitted',
        ]);

        return redirect()->route('desa.izincalon.index')->with('success', 'Usulan izin pencalonan berhasil dikirim.');
    }
}
