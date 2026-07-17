<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bimtek;
use App\Models\BimtekPendaftaran;
use Illuminate\Http\Request;

class BimtekController extends Controller
{
    public function index()
    {
        $bimteks = Bimtek::withCount('pendaftarans')->latest()->paginate(15);
        $totalPeserta = BimtekPendaftaran::count();
        $rtlUploads = BimtekPendaftaran::whereNotNull('file_rtl')->count();

        return view('admin.bimtek.index', compact('bimteks', 'totalPeserta', 'rtlUploads'));
    }

    public function create()
    {
        return view('admin.bimtek.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'tanggal_pelaksanaan' => 'required|date',
            'kuota' => 'required|integer',
            'tempat' => 'required'
        ]);

        Bimtek::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'kuota' => $request->kuota,
            'sisa_kuota' => $request->kuota,
            'tanggal_pelaksanaan' => $request->tanggal_pelaksanaan,
            'tempat' => $request->tempat
        ]);

        return redirect()->route('admin.bimtek.index')->with('success', 'Agenda Bimtek berhasil dibuat.');
    }
}
