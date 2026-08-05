<?php
$zipFile = 'sidmini.zip';
$zip = new ZipArchive;
if ($zip->open($zipFile) === TRUE) {
    $zip->extractTo(__DIR__);
    $zip->close();
    echo "BERHASIL UNZIP! Silakan hapus file sidmini.zip dan unzip.php dari FileZilla.";
} else {
    echo "Gagal membuka file ZIP.";
}
