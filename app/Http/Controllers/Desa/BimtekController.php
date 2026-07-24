<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\Bimtek;
use App\Models\BimtekInformasi;
use App\Models\BimtekPendaftaran;
use App\Models\PengajuanPembinaan;
use App\Models\PerangkatDesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BimtekController extends Controller
{
    public function index()
    {
        $desaId = Auth::user()->desa_id;

        // Informasi & berita pembinaan dari Dinpermasdes (yang sudah dipublikasikan)
        $informasis = BimtekInformasi::published()->latest('published_at')->take(6)->get();

        // Event Bimtek yang masih terjadwal
        $bimteks = Bimtek::withCount('pendaftarans')
            ->latest()
            ->get();

        // Pendaftaran milik desa ini
        $myPendaftarans = BimtekPendaftaran::with(['bimtek', 'perangkatDesa'])
            ->where('desa_id', $desaId)
            ->get();

        // ID bimtek yang sudah didaftarkan desa ini (untuk disable tombol)
        $registeredBimtekIds = $myPendaftarans->pluck('bimtek_id')->toArray();

        // Pengajuan pembinaan dari desa ini
        $myPengajuans = PengajuanPembinaan::where('desa_id', $desaId)->latest()->get();

        return view('desa.bimtek.index', compact('bimteks', 'myPendaftarans', 'registeredBimtekIds', 'informasis', 'myPengajuans'));
    }

    /**
     * Tahap 2: Desa mendaftarkan perangkat untuk mengikuti Bimtek.
     */
    public function daftar(Request $request, Bimtek $bimtek)
    {
        $desaId = Auth::user()->desa_id;

        // Validasi kuota
        if (! $bimtek->kuotaTersedia()) {
            return redirect()->back()->with('error', 'Pendaftaran gagal: Kuota kelas sudah penuh.');
        }

        // Cek duplikasi pendaftaran per desa
        $exists = BimtekPendaftaran::where('bimtek_id', $bimtek->id)
            ->where('desa_id', $desaId)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Desa Anda sudah mendaftarkan peserta untuk Bimtek ini.');
        }

        $request->validate([
            'perangkat_desa_id' => 'required|exists:perangkat_desas,id',
        ]);

        // Pastikan perangkat milik desa ini
        $perangkat = PerangkatDesa::where('id', $request->perangkat_desa_id)
            ->where('desa_id', $desaId)
            ->firstOrFail();

        BimtekPendaftaran::create([
            'bimtek_id' => $bimtek->id,
            'user_id' => Auth::user()->id,
            'desa_id' => $desaId,
            'perangkat_desa_id' => $perangkat->id,
            'status_presensi' => 'terdaftar',
            'status_rtl' => 'menunggu_rtl',
        ]);

        return redirect()->route('desa.bimtek.index')->with('success', 'Perangkat "'.$perangkat->nama.'" berhasil didaftarkan untuk Bimtek "'.$bimtek->judul.'".');
    }

    /**
     * Tahap 4: Setelah presensi valid, Desa mengunggah dokumen RTL.
     */
    public function uploadRtl(Request $request, BimtekPendaftaran $pendaftaran)
    {
        $desaId = Auth::user()->desa_id;

        // Pastikan milik desa ini
        if ($pendaftaran->desa_id !== $desaId) {
            abort(403);
        }

        // Hanya bisa upload RTL jika sudah berstatus "hadir"
        if ($pendaftaran->status_presensi !== 'hadir') {
            return back()->with('error', 'Upload RTL hanya bisa dilakukan setelah kehadiran divalidasi oleh Dinpermasdes.');
        }

        $request->validate([
            'file_rtl' => 'required|file|mimes:pdf,docx,doc|max:10240',
        ]);

        $path = $request->file('file_rtl')->store('bimtek/rtl', 'public');

        $pendaftaran->update([
            'file_rtl' => $path,
            'status_rtl' => 'menunggu_validasi',
        ]);

        return redirect()->route('desa.bimtek.index')->with('success', 'Dokumen RTL berhasil diunggah. Menunggu validasi Dinpermasdes.');
    }
}
