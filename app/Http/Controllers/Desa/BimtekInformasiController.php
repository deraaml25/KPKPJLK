<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\BimtekInformasi;

class BimtekInformasiController extends Controller
{
    public function index()
    {
        $informasis = BimtekInformasi::published()->latest()->paginate(9);

        return view('desa.bimtek-informasi.index', compact('informasis'));
    }

    public function show(BimtekInformasi $bimtekInformasi)
    {
        // Pastikan hanya bisa lihat yang sudah dipublish
        if (! $bimtekInformasi->isPublished()) {
            abort(404, 'Informasi belum dipublikasikan.');
        }

        return view('desa.bimtek-informasi.show', compact('bimtekInformasi'));
    }
}
