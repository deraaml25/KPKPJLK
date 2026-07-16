<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\ArsipRekom;
use Illuminate\Support\Facades\Auth;

class ArsipController extends Controller
{
    public function index()
    {
        $arsips = ArsipRekom::with(['ajuan.jenisLayanan'])
            ->whereHas('ajuan', fn ($q) => $q->where('desa_id', Auth::user()->desa_id))
            ->latest()
            ->paginate(15);

        return view('desa.arsip.index', compact('arsips'));
    }
}
