<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Desa;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DriveController extends Controller
{
    public function index(Request $request)
    {
        $kecamatans = Kecamatan::with('desas')->get();

        // Resolve current folder path from query params
        $path = $request->query('path', '');
        $files = [];
        $folders = [];

        if ($path && Storage::disk('public')->exists($path)) {
            foreach (Storage::disk('public')->directories($path) as $dir) {
                $folders[] = [
                    'name'  => basename($dir),
                    'path'  => $dir,
                    'count' => count(Storage::disk('public')->allFiles($dir)),
                ];
            }
            foreach (Storage::disk('public')->files($path) as $file) {
                $files[] = [
                    'name' => basename($file),
                    'path' => $file,
                    'size' => Storage::disk('public')->size($file),
                    'url'  => Storage::disk('public')->url($file),
                ];
            }
        } else {
            // Root level: show kecamatan folders
            foreach (Storage::disk('public')->directories('dokumen') as $dir) {
                $folders[] = [
                    'name'  => basename($dir),
                    'path'  => $dir,
                    'count' => count(Storage::disk('public')->allFiles($dir)),
                ];
            }
        }

        // Build breadcrumbs
        $breadcrumbs = $this->buildBreadcrumbs($path);

        return view('admin.drive.index', compact('folders', 'files', 'breadcrumbs', 'path'));
    }

    public function downloadZip(Request $request)
    {
        $path = $request->query('path', 'dokumen');
        $label = $request->query('label', 'drive-dokumen');

        $allFiles = Storage::disk('public')->allFiles($path);

        if (empty($allFiles)) {
            return back()->with('error', 'Tidak ada file untuk diunduh.');
        }

        $zipName = sys_get_temp_dir() . '/' . $label . '_' . now()->format('Ymd_His') . '.zip';
        $zip = new \ZipArchive();

        if ($zip->open($zipName, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            return back()->with('error', 'Gagal membuat ZIP.');
        }

        foreach ($allFiles as $file) {
            $realPath = Storage::disk('public')->path($file);
            $relativePath = str_replace($path . '/', '', $file);
            $zip->addFile($realPath, $relativePath);
        }

        $zip->close();

        return response()->download($zipName, $label . '.zip')->deleteFileAfterSend(true);
    }

    private function buildBreadcrumbs(string $path): array
    {
        // Clean up any trailing slashes
        $path = trim($path, '/');
        
        if (empty($path) || $path === 'dokumen') {
            return [['label' => 'Drive Dokumen', 'path' => '']];
        }

        $parts = explode('/', $path);
        $breadcrumbs = [['label' => 'Drive Dokumen', 'path' => '']];
        $cumulative = '';

        foreach ($parts as $part) {
            $cumulative .= ($cumulative ? '/' : '') . $part;
            
            // Skip the internal 'dokumen' root from breadcrumb display
            if ($part === 'dokumen' && $cumulative === 'dokumen') {
                continue;
            }
            
            // Capitalize for better look
            $label = ucwords(str_replace('-', ' ', $part));
            $breadcrumbs[] = ['label' => $label, 'path' => $cumulative];
        }

        return $breadcrumbs;
    }
}
