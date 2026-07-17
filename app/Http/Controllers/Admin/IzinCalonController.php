<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IzinCalon;
use Illuminate\Http\Request;

class IzinCalonController extends Controller
{
    public function index()
    {
        $izins = IzinCalon::with('desa')->latest()->paginate(15);
        return view('admin.izincalon.index', compact('izins'));
    }

    public function show(IzinCalon $izincalon)
    {
        $izincalon->load('desa');
        return view('admin.izincalon.show', compact('izincalon'));
    }

    public function verifikasi(Request $request, IzinCalon $izincalon)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        // business rule: ditolak sistem jika ada temuan BK/Inspektorat
        if ($request->status === 'approved' && $request->has_temuan === 'yes') {
            return redirect()->back()->with('error', 'Prosedur dibatalkan: Calon terdeteksi memiliki temuan kerugian dari Inspektorat.');
        }

        $izincalon->update([
            'status' => $request->status,
            'catatan_inspektorat' => $request->catatan_inspektorat
        ]);

        return redirect()->route('admin.izincalon.show', $izincalon)->with('success', 'Verifikasi izin pencalonan berhasil diperbarui.');
    }
}
