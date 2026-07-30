<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Kecamatan;
use App\Models\Desa;

class DriveController extends Controller
{
    public function index(Request $request)
    {
        $path = $request->query('path', '');
        $files = [];
        $folders = [];

        $parts = explode('/', trim($path, '/'));

        if (empty($path) || $path === 'dokumen') {
            // Root level: show all kecamatan folders
            $kecamatans = Kecamatan::all();
            foreach ($kecamatans as $kecamatan) {
                $dirPath = 'dokumen/' . strtolower($kecamatan->nama_kecamatan);
                $folders[] = [
                    'name'  => ucwords(strtolower($kecamatan->nama_kecamatan)),
                    'path'  => $dirPath,
                    'count' => Storage::disk('public')->exists($dirPath) ? count(Storage::disk('public')->allFiles($dirPath)) : 0,
                ];
            }
        } elseif (count($parts) === 2) {
            // Level 2: {kecamatan} -> show all desas
            $kecamatanName = $parts[1];
            $kecamatan = Kecamatan::where('nama_kecamatan', str_replace('_', ' ', $kecamatanName))->first();
            if ($kecamatan) {
                $desas = Desa::where('kecamatan_id', $kecamatan->id)->get();
                foreach ($desas as $desa) {
                    $dirPath = $path . '/' . strtolower(str_replace(' ', '_', $desa->nama_desa));
                    $folders[] = [
                        'name'  => ucwords(strtolower($desa->nama_desa)),
                        'path'  => $dirPath,
                        'count' => 4, // 4 static folders
                    ];
                }
            }
            if (Storage::disk('public')->exists($path)) {
                foreach (Storage::disk('public')->files($path) as $file) {
                    $files[] = ['name' => basename($file), 'path' => $file, 'size' => Storage::disk('public')->size($file), 'url' => Storage::disk('public')->url($file)];
                }
            }
        } elseif (count($parts) === 3) {
            // Level 3: {kecamatan}/{desa}
            $desaName = $parts[2];
            $desa = Desa::where('nama_desa', str_replace('_', ' ', $desaName))->first();
            
            if ($desa) {
                $fixedFolders = ['kades' => 'Kades', 'perangkat_desa' => 'Perangkat Desa', 'bpd' => 'BPD', 'pembinaan' => 'Pembinaan'];
                foreach ($fixedFolders as $slug => $label) {
                    $folders[] = ['name' => $label, 'path' => $path . '/' . $slug, 'count' => 0];
                }
            }
            if (Storage::disk('public')->exists($path)) {
                foreach (Storage::disk('public')->files($path) as $file) {
                    $files[] = ['name' => basename($file), 'path' => $file, 'size' => Storage::disk('public')->size($file), 'url' => Storage::disk('public')->url($file)];
                }
            }
        } elseif (count($parts) === 4) {
            // Level 4: {kecamatan}/{desa}/{module}
            $desaName = $parts[2];
            $module = $parts[3];
            $desa = Desa::where('nama_desa', str_replace('_', ' ', $desaName))->first();
            
            if ($module === 'kades') {
                $folders[] = ['name' => 'Pemberhentian', 'path' => $path . '/pemberhentian', 'count' => 0];
                $folders[] = ['name' => 'Penunjukan', 'path' => $path . '/penunjukan', 'count' => 0];
            } elseif ($module === 'perangkat_desa') {
                $folders[] = ['name' => 'Pemberhentian', 'path' => $path . '/pemberhentian', 'count' => 0];
                $folders[] = ['name' => 'Rotasi', 'path' => $path . '/rotasi', 'count' => 0];
                $folders[] = ['name' => 'Pengangkatan', 'path' => $path . '/pengangkatan', 'count' => 0];
            } elseif ($module === 'bpd') {
                $folders[] = ['name' => 'Pemberhentian', 'path' => $path . '/pemberhentian', 'count' => 0];
                $folders[] = ['name' => 'Peresmian', 'path' => $path . '/peresmian', 'count' => 0];
            } elseif ($module === 'pembinaan' && $desa) {
                $pembinaans = \App\Models\PengajuanPembinaan::where('desa_id', $desa->id)->where('status', '!=', 'draft')->get();
                foreach ($pembinaans as $pem) {
                    if ($pem->file_surat_permohonan) {
                        $files[] = ['name' => '[Pembinaan] ' . basename($pem->file_surat_permohonan), 'path' => $pem->file_surat_permohonan, 'size' => Storage::disk('public')->exists($pem->file_surat_permohonan) ? Storage::disk('public')->size($pem->file_surat_permohonan) : 0, 'url'  => Storage::disk('public')->url($pem->file_surat_permohonan)];
                    }
                    if ($pem->file_undangan) {
                        $files[] = ['name' => '[Pembinaan_Undangan] ' . basename($pem->file_undangan), 'path' => $pem->file_undangan, 'size' => Storage::disk('public')->exists($pem->file_undangan) ? Storage::disk('public')->size($pem->file_undangan) : 0, 'url'  => Storage::disk('public')->url($pem->file_undangan)];
                    }
                }
            }
            if (Storage::disk('public')->exists($path)) {
                foreach (Storage::disk('public')->files($path) as $file) {
                    $files[] = ['name' => basename($file), 'path' => $file, 'size' => Storage::disk('public')->size($file), 'url' => Storage::disk('public')->url($file)];
                }
                foreach (Storage::disk('public')->directories($path) as $dir) {
                    $base = basename($dir);
                    if (!in_array(strtolower($base), ['pemberhentian', 'penunjukan', 'rotasi', 'pengangkatan', 'peresmian'])) {
                        $folders[] = ['name' => $base, 'path' => $dir, 'count' => count(Storage::disk('public')->allFiles($dir))];
                    }
                }
            }
        } elseif (count($parts) === 5) {
            // Level 5: {kecamatan}/{desa}/{module}/{jenis}
            $desaName = $parts[2];
            $module = $parts[3];
            $jenis = $parts[4];
            
            $desa = Desa::where('nama_desa', str_replace('_', ' ', $desaName))->first();
            if ($desa) {
                if ($module === 'kades') {
                    if ($jenis === 'pemberhentian') {
                        $ajuanChecklists = \App\Models\ChecklistAjuan::whereNotNull('file_path')->whereHas('ajuan', function($q) use ($desa) {
                            $q->where('desa_id', $desa->id)->where('status', '!=', 'draft')->whereIn('jenis_layanan_id', [4, 5]);
                        })->get();
                        foreach ($ajuanChecklists as $chk) {
                            $files[] = ['name' => '[e-Rekom] ' . basename($chk->file_path), 'path' => $chk->file_path, 'size' => Storage::disk('public')->exists($chk->file_path) ? Storage::disk('public')->size($chk->file_path) : 0, 'url'  => Storage::disk('public')->url($chk->file_path)];
                        }
                    } elseif ($jenis === 'penunjukan') {
                        $pjKadesChecklists = \App\Models\ChecklistPjKades::whereNotNull('file_path')->whereHas('pjKades', function($q) use ($desa) {
                            $q->where('desa_id', $desa->id)->where('status', '!=', 'draft');
                        })->get();
                        foreach ($pjKadesChecklists as $chk) {
                            $files[] = ['name' => '[SK-Kades] ' . basename($chk->file_path), 'path' => $chk->file_path, 'size' => Storage::disk('public')->exists($chk->file_path) ? Storage::disk('public')->size($chk->file_path) : 0, 'url'  => Storage::disk('public')->url($chk->file_path)];
                        }
                    }
                } elseif ($module === 'perangkat_desa') {
                    $mapJenis = ['pengangkatan' => 1, 'rotasi' => 2, 'pemberhentian' => 3];
                    if (isset($mapJenis[$jenis])) {
                        $ajuanChecklists = \App\Models\ChecklistAjuan::whereNotNull('file_path')->whereHas('ajuan', function($q) use ($desa, $mapJenis, $jenis) {
                            $q->where('desa_id', $desa->id)->where('status', '!=', 'draft')->where('jenis_layanan_id', $mapJenis[$jenis]);
                        })->get();
                        foreach ($ajuanChecklists as $chk) {
                            $files[] = ['name' => '[e-Rekom] ' . basename($chk->file_path), 'path' => $chk->file_path, 'size' => Storage::disk('public')->exists($chk->file_path) ? Storage::disk('public')->size($chk->file_path) : 0, 'url'  => Storage::disk('public')->url($chk->file_path)];
                        }
                    }
                } elseif ($module === 'bpd') {
                    if (in_array($jenis, ['pemberhentian', 'peresmian'])) {
                        $bpdChecklists = \App\Models\ChecklistAjuanBpd::whereNotNull('file_path')->whereHas('ajuanBpd', function($q) use ($desa, $jenis) {
                            $q->where('desa_id', $desa->id)->where('status', '!=', 'draft')->where('jenis_ajuan', $jenis);
                        })->get();
                        foreach ($bpdChecklists as $chk) {
                            $files[] = ['name' => '[BPD] ' . basename($chk->file_path), 'path' => $chk->file_path, 'size' => Storage::disk('public')->exists($chk->file_path) ? Storage::disk('public')->size($chk->file_path) : 0, 'url'  => Storage::disk('public')->url($chk->file_path)];
                        }
                        
                        $bpds = \App\Models\AjuanBpd::where('desa_id', $desa->id)->where('status', '!=', 'draft')->where('jenis_ajuan', $jenis)->get();
                        foreach ($bpds as $bpd) {
                            if ($bpd->berkas_zip) {
                                $files[] = ['name' => '[BPD_ZIP] ' . basename($bpd->berkas_zip), 'path' => $bpd->berkas_zip, 'size' => Storage::disk('public')->exists($bpd->berkas_zip) ? Storage::disk('public')->size($bpd->berkas_zip) : 0, 'url' => Storage::disk('public')->url($bpd->berkas_zip)];
                            }
                        }
                    }
                }
            }
            if (Storage::disk('public')->exists($path)) {
                foreach (Storage::disk('public')->directories($path) as $dir) {
                    $folders[] = ['name' => basename($dir), 'path' => $dir, 'count' => count(Storage::disk('public')->allFiles($dir))];
                }
                foreach (Storage::disk('public')->files($path) as $file) {
                    $files[] = ['name' => basename($file), 'path' => $file, 'size' => Storage::disk('public')->size($file), 'url' => Storage::disk('public')->url($file)];
                }
            }
        } else {
            // Beyond level 5
            if (!Storage::disk('public')->exists($path)) {
                Storage::disk('public')->makeDirectory($path);
            }
            foreach (Storage::disk('public')->directories($path) as $dir) {
                $folders[] = ['name' => basename($dir), 'path' => $dir, 'count' => count(Storage::disk('public')->allFiles($dir))];
            }
            foreach (Storage::disk('public')->files($path) as $file) {
                $files[] = ['name' => basename($file), 'path' => $file, 'size' => Storage::disk('public')->size($file), 'url' => Storage::disk('public')->url($file)];
            }
        }

        $breadcrumbs = $this->buildBreadcrumbs($path);
        return view('admin.drive.index', compact('folders', 'files', 'breadcrumbs', 'path'));
    }

    public function upload(Request $request)
    {
        $request->validate(['file' => 'required|file', 'path' => 'required|string']);
        $path = $request->input('path', 'dokumen');
        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/' . $path, $filename);
        return back()->with('success', 'Dokumen berhasil diunggah.');
    }

    public function downloadZip(Request $request)
    {
        $path = $request->query('path', 'dokumen');
        $label = $request->query('label', 'drive-dokumen');
        $parts = explode('/', trim($path, '/'));
        
        $zipName = sys_get_temp_dir() . '/' . $label . '_' . now()->format('Ymd_His') . '.zip';
        $zip = new \ZipArchive();

        if ($zip->open($zipName, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            return back()->with('error', 'Gagal membuat ZIP.');
        }

        $hasFiles = false;

        // Add physical files
        if (Storage::disk('public')->exists($path)) {
            $allPhysical = Storage::disk('public')->allFiles($path);
            foreach ($allPhysical as $file) {
                $realPath = Storage::disk('public')->path($file);
                $relativePath = str_replace($path . '/', '', $file);
                $zip->addFile($realPath, $relativePath);
                $hasFiles = true;
            }
        }

        // Add virtual files based on level
        $virtualFiles = [];
        if (count($parts) >= 3) {
            $desaName = $parts[2];
            $desa = Desa::where('nama_desa', str_replace('_', ' ', $desaName))->first();
            if ($desa) {
                if (count($parts) === 3) {
                    $virtualFiles = $this->getAllVirtualFilesForDesa($desa);
                } elseif (count($parts) === 4) {
                    $module = $parts[3];
                    $virtualFiles = $this->getVirtualFilesForModule($desa, $module);
                } elseif (count($parts) === 5) {
                    $module = $parts[3];
                    $jenis = $parts[4];
                    $virtualFiles = $this->getVirtualFilesForJenis($desa, $module, $jenis);
                }
            }
        }

        foreach ($virtualFiles as $vf) {
            $zip->addFile($vf['real_path'], $vf['relative_name']);
            $hasFiles = true;
        }

        if (!$hasFiles) {
            $zip->close();
            return back()->with('error', 'Tidak ada file untuk diunduh.');
        }
        $zip->close();

        return response()->download($zipName, $label . '.zip')->deleteFileAfterSend(true);
    }

    private function buildBreadcrumbs($path)
    {
        if (empty($path)) return [];

        $parts = explode('/', trim($path, '/'));
        $breadcrumbs = [];
        $currentPath = '';

        foreach ($parts as $index => $part) {
            $currentPath .= ($index === 0 ? '' : '/') . $part;
            
            $label = ucwords(str_replace('_', ' ', $part));
            if ($index === 0 && strtolower($part) === 'dokumen') {
                $label = 'Arsip Dokumen';
            }
            
            $breadcrumbs[] = [
                'label' => $label,
                'path' => $currentPath
            ];
        }

        return $breadcrumbs;
    }
}
