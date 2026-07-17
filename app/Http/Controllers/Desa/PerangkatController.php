<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\PerangkatDesa;
use Illuminate\Http\Request;

class PerangkatController extends Controller
{
    public function index()
    {
        $perangkats = PerangkatDesa::where('desa_id', auth()->user()->desa_id)
            ->where('status_aktif', true)
            ->paginate(10);

        return view('desa.perangkat.index', compact('perangkats'));
    }
}
