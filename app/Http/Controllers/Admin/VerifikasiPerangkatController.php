<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PerangkatDesa;
use Illuminate\Http\Request;

class VerifikasiPerangkatController extends Controller
{
    public function index()
    {
        // Get all pending requests
        $pending = PerangkatDesa::with('desa')
            ->whereIn('status_verifikasi', ['pending_tambah', 'pending_ubah', 'pending_nonaktif', 'pending_aktif'])
            ->latest()
            ->paginate(15);

        return view('admin.verifikasi_perangkat.index', compact('pending'));
    }

    public function approve(Request $request, $id)
    {
        $perangkat = PerangkatDesa::findOrFail($id);

        if ($perangkat->status_verifikasi === 'pending_tambah') {
            $perangkat->status_aktif = true;
            $perangkat->status_verifikasi = 'disetujui';
        } elseif ($perangkat->status_verifikasi === 'pending_ubah') {
            $draft = $perangkat->draft_perubahan;
            if ($draft) {
                $perangkat->nama = $draft['nama'] ?? $perangkat->nama;
                $perangkat->jabatan = $draft['jabatan'] ?? $perangkat->jabatan;
                $perangkat->no_sk_terakhir = $draft['no_sk_terakhir'] ?? $perangkat->no_sk_terakhir;
                $perangkat->tgl_mulai_jabatan = $draft['tgl_mulai_jabatan'] ?? $perangkat->tgl_mulai_jabatan;
            }
            $perangkat->draft_perubahan = null;
            $perangkat->status_verifikasi = 'disetujui';
        } elseif ($perangkat->status_verifikasi === 'pending_nonaktif') {
            $perangkat->status_aktif = false;
            $perangkat->status_verifikasi = 'disetujui';
        } elseif ($perangkat->status_verifikasi === 'pending_aktif') {
            $perangkat->status_aktif = true;
            $perangkat->status_verifikasi = 'disetujui';
        }

        $perangkat->save();

        return back()->with('success', 'Usulan perangkat desa disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $perangkat = PerangkatDesa::findOrFail($id);

        if ($perangkat->status_verifikasi === 'pending_tambah') {
            // Delete if it was a new record that got rejected
            $perangkat->delete();
        } else {
            // Just clear draft and set back to approved (so it reverts to active state)
            $perangkat->draft_perubahan = null;
            $perangkat->status_verifikasi = 'disetujui';
            $perangkat->save();
        }

        return back()->with('success', 'Usulan perangkat desa ditolak.');
    }
}
