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
        $rtlUploads = BimtekPendaftaran::where('status_rtl', 'selesai')->count();
        $rtlMenunggu = BimtekPendaftaran::where('status_rtl', 'menunggu_validasi')->count();

        return view('admin.bimtek.index', compact('bimteks', 'totalPeserta', 'rtlUploads', 'rtlMenunggu'));
    }

    public function create()
    {
        return view('admin.bimtek.create');
    }

    /**
     * Tahap 1: Dinas membuat Event Bimtek baru + upload Surat Undangan.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal_pelaksanaan' => 'required|date',
            'kuota' => 'required|integer|min:1',
            'tempat' => 'required|string|max:255',
            'file_undangan' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $data = [
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'kuota' => $request->kuota,
            'tanggal_pelaksanaan' => $request->tanggal_pelaksanaan,
            'tempat' => $request->tempat,
        ];

        if ($request->hasFile('file_undangan')) {
            $data['file_undangan'] = $request->file('file_undangan')->store('bimtek/undangan', 'public');
        }

        Bimtek::create($data);

        return redirect()->route('admin.bimtek.index')->with('success', 'Agenda Bimtek berhasil dibuat dan dipublikasikan ke seluruh desa.');
    }

    /**
     * Tahap 1 (lanjutan): Detail Event + Daftar Peserta.
     */
    public function show(Bimtek $bimtek)
    {
        $bimtek->load(['pendaftarans.desa', 'pendaftarans.perangkatDesa']);
        return view('admin.bimtek.show', compact('bimtek'));
    }

    /**
     * Tahap 3: Dinas melakukan presensi kehadiran peserta.
     */
    public function updatePresensi(Request $request, BimtekPendaftaran $pendaftaran)
    {
        $request->validate([
            'status_presensi' => 'required|in:hadir,absen',
        ]);

        $pendaftaran->update([
            'status_presensi' => $request->status_presensi,
        ]);

        return back()->with('success', 'Status kehadiran peserta dari Desa ' . $pendaftaran->desa->nama_desa . ' berhasil diupdate.');
    }

    /**
     * Tahap 1 (opsional): Upload materi & sertifikat setelah event dibuat.
     */
    public function uploadMateri(Request $request, Bimtek $bimtek)
    {
        $request->validate([
            'file_materi' => 'nullable|file|mimes:pdf,pptx,ppt,doc,docx|max:20480',
            'file_sertifikat' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $data = [];

        if ($request->hasFile('file_materi')) {
            $data['file_materi'] = $request->file('file_materi')->store('bimtek/materi', 'public');
        }
        if ($request->hasFile('file_sertifikat')) {
            $data['file_sertifikat'] = $request->file('file_sertifikat')->store('bimtek/sertifikat', 'public');
        }

        if (!empty($data)) {
            $bimtek->update($data);
        }

        return back()->with('success', 'Materi/Sertifikat berhasil diunggah.');
    }

    /**
     * Tahap 5: Validasi RTL dari Desa.
     */
    public function validasiRtl(Request $request, BimtekPendaftaran $pendaftaran)
    {
        $request->validate([
            'status_rtl' => 'required|in:selesai,revisi',
            'catatan_revisi_rtl' => 'nullable|string',
        ]);

        $data = ['status_rtl' => $request->status_rtl];

        if ($request->status_rtl === 'revisi') {
            $data['catatan_revisi_rtl'] = $request->catatan_revisi_rtl;
        }

        $pendaftaran->update($data);

        $msg = $request->status_rtl === 'selesai'
            ? 'RTL desa ' . $pendaftaran->desa->nama_desa . ' dinyatakan TUNTAS.'
            : 'RTL dikembalikan ke desa untuk revisi.';

        return back()->with('success', $msg);
    }
}
