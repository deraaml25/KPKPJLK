<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BimtekInformasi;
use Illuminate\Http\Request;

class BimtekInformasiController extends Controller
{
    public function index()
    {
        $informasis = BimtekInformasi::latest()->paginate(15);

        return view('admin.bimtek-informasi.index', compact('informasis'));
    }

    public function create()
    {
        return view('admin.bimtek-informasi.create');
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $path = $request->file('file')->store('bimtek/informasi/images', 'public');

        return response()->json([
            'location' => asset('storage/'.$path),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'kategori' => 'required|in:informasi,dokumentasi,pengumuman',
            'foto' => 'nullable|array|max:5',
            'foto.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:20480',
            'file_lampiran' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'published_at' => 'nullable|date',
        ]);

        $data = $request->only(['judul', 'konten', 'kategori', 'published_at']);

        if ($request->hasFile('foto')) {
            $fotoPaths = [];
            foreach ($request->file('foto') as $file) {
                $fotoPaths[] = $file->store('bimtek/informasi/foto', 'public');
            }
            $data['foto'] = $fotoPaths;
        }

        if ($request->hasFile('file_lampiran')) {
            $data['file_lampiran'] = $request->file('file_lampiran')->store('bimtek/informasi/lampiran', 'public');
        }

        BimtekInformasi::create($data);

        return redirect()->route('admin.bimtek-informasi.index')
            ->with('success', 'Informasi pembinaan berhasil dipublikasikan.');
    }

    public function show(BimtekInformasi $bimtekInformasi)
    {
        return view('admin.bimtek-informasi.show', compact('bimtekInformasi'));
    }

    public function edit(BimtekInformasi $bimtekInformasi)
    {
        return view('admin.bimtek-informasi.edit', compact('bimtekInformasi'));
    }

    public function update(Request $request, BimtekInformasi $bimtekInformasi)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'kategori' => 'required|in:informasi,dokumentasi,pengumuman',
            'foto' => 'nullable|array|max:5',
            'foto.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:20480',
            'file_lampiran' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'published_at' => 'nullable|date',
        ]);

        $data = $request->only(['judul', 'konten', 'kategori', 'published_at']);

        if ($request->hasFile('foto')) {
            $fotoPaths = [];
            foreach ($request->file('foto') as $file) {
                $fotoPaths[] = $file->store('bimtek/informasi/foto', 'public');
            }
            $data['foto'] = $fotoPaths;
        }

        if ($request->hasFile('file_lampiran')) {
            $data['file_lampiran'] = $request->file('file_lampiran')->store('bimtek/informasi/lampiran', 'public');
        }

        $bimtekInformasi->update($data);

        return redirect()->route('admin.bimtek-informasi.index')
            ->with('success', 'Informasi pembinaan berhasil diperbarui.');
    }

    public function destroy(BimtekInformasi $bimtekInformasi)
    {
        $bimtekInformasi->delete();

        return redirect()->route('admin.bimtek-informasi.index')
            ->with('success', 'Informasi pembinaan berhasil dihapus.');
    }
}
