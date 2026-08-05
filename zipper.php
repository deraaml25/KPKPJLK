<?php
$zipFile = 'sidmini.zip';
$zip = new ZipArchive();
if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    die("Cannot create zip");
}
$dir = new RecursiveDirectoryIterator('.');
$iterator = new RecursiveIteratorIterator($dir);
foreach ($iterator as $file) {
    if (!$file->isDir()) {
        $path = substr($file->getPathname(), 2); // remove ./
        if ($path != 'sidmini.zip' && $path != 'zipper.php' && !str_starts_with($path, '.git')) {
            $zip->addFile($file->getRealPath(), $path);
        }
    }
}
$zip->close();
echo "ZIP_DONE";
