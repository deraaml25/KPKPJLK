<?php
$zipFile = 'C:/laragon/www/sidmini_upload.zip';
if (file_exists($zipFile)) @unlink($zipFile);
$zip = new ZipArchive();
if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    die("Cannot create zip");
}
$dir = new RecursiveDirectoryIterator('C:/laragon/www/sidmini');
$iterator = new RecursiveIteratorIterator($dir);
foreach ($iterator as $file) {
    if (!$file->isDir()) {
        $path = str_replace('\\', '/', $file->getPathname());
        $path = str_replace('C:/laragon/www/sidmini/', '', $path);
        
        if (!str_starts_with($path, '.git') && !str_starts_with($path, 'node_modules')) {
            $zip->addFile($file->getRealPath(), $path);
        }
    }
}
$zip->close();
echo "ZIP_DONE";
