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
        $izins = IzinCalon::withoutGlobalScopes()->where('desa_id', $desaId)->latest()->get();

        return view('desa.izincalon.index', compact('izins'));
    }

    public function create()
    {
        return view('desa.izincalon.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'nama_calon' => 'required|string|max:255',
            'jabatan_sekarang' => 'required|string|max:255',
            'jenis_calon' => 'required|in:kades,perangkat,pns',
            'surat_permohonan' => 'required|file|mimes:pdf|max:10240',
            'berkas_syarat' => 'required|file|mimes:pdf|max:10240',
        ];

        // Perangkat Desa wajib sertakan surat pengunduran diri
        if ($request->jenis_calon === 'perangkat') {
            $rules['surat_pengunduran_diri'] = 'required|file|mimes:pdf|max:10240';
        }

        // Petahana (Kades) wajib input tanggal cuti
        if ($request->jenis_calon === 'kades') {
            $rules['tgl_cuti_mulai'] = 'required|date';
            $rules['tgl_cuti_selesai'] = 'required|date|after:tgl_cuti_mulai';
        }

        $request->validate($rules);

        $desaId = Auth::user()->desa_id;
        $permohonan = $request->file('surat_permohonan')->store('izincalon/permohonan', 'public');
        $berkas = $request->file('berkas_syarat')->store('izincalon/berkas', 'public');
        $pengunduran = null;

        if ($request->hasFile('surat_pengunduran_diri')) {
            $pengunduran = $request->file('surat_pengunduran_diri')->store('izincalon/pengunduran', 'public');
        }

        IzinCalon::create([
            'desa_id' => $desaId,
            'nama_calon' => $request->nama_calon,
            'jabatan_sekarang' => $request->jabatan_sekarang,
            'jenis_calon' => $request->jenis_calon,
            'surat_permohonan_path' => $permohonan,
            'berkas_syarat_path' => $berkas,
            'surat_pengunduran_diri_path' => $pengunduran,
            'tgl_cuti_mulai' => $request->tgl_cuti_mulai,
            'tgl_cuti_selesai' => $request->tgl_cuti_selesai,
            'status' => 'submitted',
        ]);

        return redirect()->route('desa.izincalon.index')
            ->with('success', 'Permohonan Izin Pencalonan Kades berhasil dikirim ke Dinpermasdes.');
    }
}
