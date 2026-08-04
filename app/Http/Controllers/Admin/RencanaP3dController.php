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

        $fileName = 'rencana_p3d_'.date('Y_m_d_H_i_s').'.xls';

        $headers = [
            'Content-type' => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename=$fileName",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($rencanas) {
            echo '<table border="1" style="border-collapse: collapse; text-align: left;">';
            echo '<thead>';
            echo '<tr style="background-color: #f3f4f6;">';
            echo '<th style="padding: 8px; font-weight: bold; border: 1px solid #000;">No</th>';
            echo '<th style="padding: 8px; font-weight: bold; border: 1px solid #000;">Kecamatan</th>';
            echo '<th style="padding: 8px; font-weight: bold; border: 1px solid #000;">Desa</th>';
            echo '<th style="padding: 8px; font-weight: bold; border: 1px solid #000;">Tahun</th>';
            echo '<th style="padding: 8px; font-weight: bold; border: 1px solid #000;">Jumlah Formasi Kosong</th>';
            echo '<th style="padding: 8px; font-weight: bold; border: 1px solid #000;">Jabatan Kosong</th>';
            echo '<th style="padding: 8px; font-weight: bold; border: 1px solid #000;">Rencana Pelaksanaan</th>';
            echo '<th style="padding: 8px; font-weight: bold; border: 1px solid #000;">Rencana Anggaran</th>';
            echo '<th style="padding: 8px; font-weight: bold; border: 1px solid #000;">Keterangan</th>';
            echo '<th style="padding: 8px; font-weight: bold; border: 1px solid #000;">Status</th>';
            echo '<th style="padding: 8px; font-weight: bold; border: 1px solid #000;">Tanggal Dibuat</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            foreach ($rencanas as $index => $item) {
                echo '<tr>';
                echo '<td style="padding: 8px; border: 1px solid #000;">'.($index + 1).'</td>';
                echo '<td style="padding: 8px; border: 1px solid #000;">'.($item->kecamatan->nama_kecamatan ?? '-').'</td>';
                echo '<td style="padding: 8px; border: 1px solid #000;">'.($item->desa->nama_desa ?? '-').'</td>';
                echo '<td style="padding: 8px; border: 1px solid #000;">'.($item->tahun ?? '-').'</td>';
                echo '<td style="padding: 8px; border: 1px solid #000; text-align: center;">'.$item->jumlah_formasi_kosong.'</td>';
                echo '<td style="padding: 8px; border: 1px solid #000;">'.$item->jabatan_kosong.'</td>';
                echo '<td style="padding: 8px; border: 1px solid #000;">'.($item->rencana_pelaksanaan ? $item->rencana_pelaksanaan->format('d/m/Y') : '-').'</td>';
                echo '<td style="padding: 8px; border: 1px solid #000;">Rp '.number_format($item->rencana_anggaran, 0, ',', '.').'</td>';
                echo '<td style="padding: 8px; border: 1px solid #000;">'.$item->keterangan.'</td>';
                echo '<td style="padding: 8px; border: 1px solid #000;">'.ucfirst($item->status).'</td>';
                echo '<td style="padding: 8px; border: 1px solid #000;">'.$item->created_at->format('d/m/Y H:i').'</td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
        };

        return response()->stream($callback, 200, $headers);
    }
}
