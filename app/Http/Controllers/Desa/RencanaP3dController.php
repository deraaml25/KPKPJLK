<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\RencanaP3d;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RencanaP3dController extends Controller
{
    public function index()
    {
        $rencana = RencanaP3d::latest()->paginate(15);
        return view('desa.rencana_p3d.index', compact('rencana'));
    }

    public function create()
    {
        $desa = Auth::user()->desa;
        return view('desa.rencana_p3d.create', compact('desa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jumlah_formasi_kosong' => ['required', 'integer', 'min:1'],
            'jabatan_kosong' => ['required', 'string'],
            'rencana_pelaksanaan' => ['required', 'date'],
            'rencana_anggaran' => ['required', 'numeric', 'min:0'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $desa = Auth::user()->desa;
        $tahun = Carbon::parse($request->rencana_pelaksanaan)->year;

        RencanaP3d::create([
            'desa_id' => $desa->id,
            'kecamatan_id' => $desa->kecamatan_id,
            'jumlah_formasi_kosong' => $request->jumlah_formasi_kosong,
            'jabatan_kosong' => $request->jabatan_kosong,
            'rencana_pelaksanaan' => $request->rencana_pelaksanaan,
            'rencana_anggaran' => $request->rencana_anggaran,
            'keterangan' => $request->keterangan,
            'status' => 'dikirim',
            'tahun' => $tahun,
        ]);

        return redirect()->route('desa.rencana-p3d.index')
            ->with('success', 'Rencana P3D berhasil disimpan.');
    }

    public function edit(RencanaP3d $rencanaP3d)
    {
        if ($rencanaP3d->desa_id !== Auth::user()->desa_id) {
            abort(403, 'Unauthorized action.');
        }

        $desa = Auth::user()->desa;
        return view('desa.rencana_p3d.edit', [
            'rencana' => $rencanaP3d,
            'desa' => $desa
        ]);
    }

    public function update(Request $request, RencanaP3d $rencanaP3d)
    {
        if ($rencanaP3d->desa_id !== Auth::user()->desa_id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'jumlah_formasi_kosong' => ['required', 'integer', 'min:1'],
            'jabatan_kosong' => ['required', 'string'],
            'rencana_pelaksanaan' => ['required', 'date'],
            'rencana_anggaran' => ['required', 'numeric', 'min:0'],
            'keterangan' => ['nullable', 'string'],
            'status' => ['nullable', 'string', 'in:draft,dikirim,disetujui'],
        ]);

        $tahun = Carbon::parse($request->rencana_pelaksanaan)->year;

        $rencanaP3d->update([
            'jumlah_formasi_kosong' => $request->jumlah_formasi_kosong,
            'jabatan_kosong' => $request->jabatan_kosong,
            'rencana_pelaksanaan' => $request->rencana_pelaksanaan,
            'rencana_anggaran' => $request->rencana_anggaran,
            'keterangan' => $request->keterangan,
            'tahun' => $tahun,
            'status' => $request->status ?? $rencanaP3d->status,
        ]);

        return redirect()->route('desa.rencana-p3d.index')
            ->with('success', 'Rencana P3D berhasil diperbarui.');
    }

    public function destroy(RencanaP3d $rencanaP3d)
    {
        if ($rencanaP3d->desa_id !== Auth::user()->desa_id) {
            abort(403, 'Unauthorized action.');
        }

        $rencanaP3d->delete();

        return redirect()->route('desa.rencana-p3d.index')
            ->with('success', 'Rencana P3D berhasil dihapus.');
    }
}
