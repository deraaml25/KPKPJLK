<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\Bimtek;
use App\Models\BimtekPendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BimtekController extends Controller
{
    public function index()
    {
        $bimteks = Bimtek::where('tanggal_pelaksanaan', '>=', now())
            ->latest()
            ->get();

        $myPendaftarans = BimtekPendaftaran::with('bimtek')
            ->where('user_id', Auth::id())
            ->get();

        return view('desa.bimtek.index', compact('bimteks', 'myPendaftarans'));
    }

    public function daftar(Bimtek $bimtek)
    {
        if ($bimtek->sisa_kuota <= 0) {
            return redirect()->back()->with('error', 'Pendaftaran gagal: Kuota kelas pembinaan sudah penuh.');
        }

        // Cek double pendaftaran
        $exists = BimtekPendaftaran::where('bimtek_id', $bimtek->id)
            ->where('user_id', Auth::id())
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Anda sudah terdaftar dalam bimtek ini.');
        }

        BimtekPendaftaran::create([
            'bimtek_id' => $bimtek->id,
            'user_id' => Auth::id(),
            'status_presensi' => 'absen',
        ]);

        $bimtek->decrement('sisa_kuota');

        return redirect()->route('desa.bimtek.index')->with('success', 'Berhasil mendaftar Bimtek.');
    }

    public function uploadRtl(Request $request, BimtekPendaftaran $pendaftaran)
    {
        if ($pendaftaran->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'file_rtl' => 'required|file|mimes:pdf,docx,doc|max:10240'
        ]);

        $path = $request->file('file_rtl')->store('bimtek/rtl', 'public');

        $pendaftaran->update([
            'file_rtl' => $path
        ]);

        return redirect()->route('desa.bimtek.index')->with('success', 'Dokumen Rencana Tindak Lanjut (RTL) berhasil diunggah.');
    }
}
