<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\Regulasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegulasiController extends Controller
{
    public function index()
    {
        $desaId = Auth::user()->desa_id;
        $regulasis = Regulasi::where('desa_id', $desaId)->latest()->paginate(15);
        return view('desa.regulasi.index', compact('regulasis'));
    }

    public function create()
    {
        return view('desa.regulasi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'tipe' => 'required|in:perdes,perkades,sk_kades',
            'file' => 'required|file|mimes:pdf,docx,doc|max:10240'
        ]);

        $desaId = Auth::user()->desa_id;
        $path = $request->file('file')->store('regulasi/draft', 'public');

        // Generate No. Registrasi
        $prefix = match ($request->tipe) {
            'perdes' => 'PRD',
            'perkades' => 'PKD',
            'sk_kades' => 'SKD',
        };
        $noReg = $prefix . '/' . now()->format('Y') . '/' . now()->format('m') . '/' . str_pad(Regulasi::count() + 1, 4, '0', STR_PAD_LEFT);

        Regulasi::create([
            'no_regulasi' => $noReg,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tipe' => $request->tipe,
            'file_path' => $path,
            'status' => 'diajukan', // otomatis diajukan ke dinas
            'desa_id' => $desaId,
            'tgl_diajukan' => now(),
        ]);

        return redirect()->route('desa.regulasi.index')->with('success', 'Rancangan regulasi berhasil diajukan untuk fasilitasi.');
    }
}
