<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kecamatan;
use App\Models\RencanaP3d;
use Illuminate\Http\Request;

class RencanaP3dController extends Controller
{
    public function index(Request $request)
    {
        $query = RencanaP3d::withoutGlobalScopes()->with(['desa.kecamatan', 'kecamatan']);

        if ($request->filled('kecamatan_id')) {
            $query->where('kecamatan_id', $request->kecamatan_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('desa', function ($q) use ($search) {
                $q->where('nama_desa', 'LIKE', "%{$search}%");
            });
        }

        // Hitung statistik berdasarkan filter
        $statsQuery = clone $query;
        $totalFormasi = $statsQuery->sum('jumlah_formasi_kosong');
        $totalAnggaran = $statsQuery->sum('rencana_anggaran');
        $totalDesa = $statsQuery->distinct('desa_id')->count('desa_id');

        $rencana = $query->latest()->paginate(15)->withQueryString();
        $kecamatans = Kecamatan::orderBy('nama_kecamatan')->get();

        return view('admin.rencana_p3d.index', compact(
            'rencana',
            'kecamatans',
            'totalFormasi',
            'totalAnggaran',
            'totalDesa'
        ));
    }

    public function show($id)
    {
        $rencana = RencanaP3d::withoutGlobalScopes()
            ->with(['desa.kecamatan', 'kecamatan'])
            ->findOrFail($id);

        return view('admin.rencana_p3d.show', compact('rencana'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', 'string', 'in:draft,dikirim,disetujui'],
        ]);

        $rencana = RencanaP3d::withoutGlobalScopes()->findOrFail($id);
        $rencana->update([
            'status' => $request->status,
        ]);

        return redirect()->route('admin.rencana-p3d.index')
            ->with('success', 'Status rencana P3D berhasil diperbarui.');
    }

    public function exportCsv(Request $request)
    {
        $query = RencanaP3d::withoutGlobalScopes()->with(['desa', 'kecamatan']);

        if ($request->filled('kecamatan_id')) {
            $query->where('kecamatan_id', $request->kecamatan_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('desa', function ($q) use ($search) {
                $q->where('nama_desa', 'LIKE', "%{$search}%");
            });
        }

        $rencanas = $query->latest()->get();

        $fileName = 'rencana_p3d_' . date('Y_m_d_H_i_s') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'No', 
            'Kecamatan', 
            'Desa', 
            'Tahun', 
            'Jumlah Formasi Kosong', 
            'Jabatan Kosong', 
            'Rencana Pelaksanaan', 
            'Rencana Anggaran', 
            'Keterangan', 
            'Status', 
            'Tanggal Dibuat'
        ];

        $callback = function() use($rencanas, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($rencanas as $index => $item) {
                $row = [
                    $index + 1,
                    $item->kecamatan->nama_kecamatan ?? '-',
                    $item->desa->nama_desa ?? '-',
                    $item->tahun ?? '-',
                    $item->jumlah_formasi_kosong,
                    $item->jabatan_kosong,
                    $item->rencana_pelaksanaan ? $item->rencana_pelaksanaan->format('Y-m-d') : '-',
                    $item->rencana_anggaran,
                    $item->keterangan,
                    $item->status,
                    $item->created_at->format('Y-m-d H:i:s'),
                ];
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
