<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bpd;
use Illuminate\Http\Request;

class BpdController extends Controller
{
    public function index(Request $request)
    {
        $query = Bpd::with('desa.kecamatan');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama', 'like', "%{$search}%")
                ->orWhere('jabatan', 'like', "%{$search}%")
                ->orWhereHas('desa', function ($q) use ($search) {
                    $q->where('nama_desa', 'like', "%{$search}%");
                });
        }

        $bpd = $query->orderBy('desa_id')
            ->orderByRaw("CASE WHEN jabatan = 'Ketua' THEN 0 ELSE 1 END")
            ->paginate(15);

        return view('admin.bpd.index', compact('bpd'));
    }
}
