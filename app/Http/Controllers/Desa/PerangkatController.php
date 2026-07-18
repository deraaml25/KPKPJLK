<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\PerangkatDesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerangkatController extends Controller
{
    public function index(Request $request)
    {
        // TenantDesaScope memastikan hanya mengambil perangkat sesuai desa_id user login
        $query = PerangkatDesa::orderByRaw("CASE WHEN jabatan = 'Kepala Desa' THEN 0 ELSE 1 END")->latest();

        if ($request->has('search') && $request->search != '') {
            $query->where('nama', 'like', '%' . $request->search . '%')
                ->orWhere('jabatan', 'like', '%' . $request->search . '%');
        }

        $perangkat = $query->paginate(15);
        $totalAktif = PerangkatDesa::where('status_aktif', true)->count();

        return view('desa.perangkat.index', compact('perangkat', 'totalAktif'));
    }

    public function create()
    {
        return view('desa.perangkat.create');
    }

    public function store(\App\Http\Requests\Desa\PerangkatRequest $request)
    {
        $validated = $request->validated();

        // Mencegah manipulasi desa_id, dipaksa sesuai auth desa
        $validated['desa_id'] = auth()->user()->desa_id;
        $validated['status_aktif'] = true;

        PerangkatDesa::create($validated);

        return redirect()->route('desa.perangkat.index')
            ->with('success', 'Data perangkat desa berhasil ditambahkan.');
    }

    public function edit(PerangkatDesa $perangkat)
    {
        // $perangkat ini sudah ter-filter secara otomatis oleh TenantDesaScope 
        // sehingga mereka tidak akan pernah bisa mengedit perangkat desa lain
        return view('desa.perangkat.edit', compact('perangkat'));
    }

    public function update(\App\Http\Requests\Desa\PerangkatRequest $request, PerangkatDesa $perangkat)
    {
        $validated = $request->validated();

        // Hanya update field administratif.
        // Kita tidak memperbarui desa_id atau id
        $perangkat->update([
            'nama' => $validated['nama'],
            'jabatan' => $validated['jabatan'],
            'no_sk_terakhir' => $validated['no_sk_terakhir'],
            'tgl_mulai_jabatan' => $validated['tgl_mulai_jabatan'],
        ]);

        return redirect()->route('desa.perangkat.index')
            ->with('success', 'Data perangkat desa berhasil diperbarui.');
    }

    public function destroy(PerangkatDesa $perangkat)
    {
        // Alih-alih hard delete, kita lakukan soft delete flag.
        // Ini menjaga integritas data riwayat jika digunakan untuk pendaftaran dsb.
        $perangkat->update(['status_aktif' => false]);

        return redirect()->route('desa.perangkat.index')
            ->with('success', 'Perangkat desa berhasil dinonaktifkan.');
    }
}
