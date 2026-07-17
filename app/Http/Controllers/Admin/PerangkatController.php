<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PerangkatDesa;
use Illuminate\Http\Request;

class PerangkatController extends Controller
{
    public function index(Request $request)
    {
        $query = PerangkatDesa::with('desa.kecamatan');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama', 'like', "%{$search}%")
                ->orWhere('jabatan', 'like', "%{$search}%")
                ->orWhereHas('desa', function ($q) use ($search) {
                    $q->where('nama_desa', 'like', "%{$search}%");
                });
        }

        $perangkats = $query->orderBy('desa_id')->paginate(15);

        return view('admin.perangkat.index', compact('perangkats'));
    }
}
