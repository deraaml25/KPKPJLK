<?php
$dbPath = __DIR__ . '/database/database.sqlite';
$db = new PDO('sqlite:' . $dbPath);
$desas = $db->query("SELECT * FROM desas WHERE nama_desa LIKE '%KARANGENDEP%'")->fetchAll(PDO::FETCH_ASSOC);
$users = $db->query("SELECT id, name, desa_id FROM users WHERE name LIKE '%Karangendep%'")->fetchAll(PDO::FETCH_ASSOC);
$perangkat = $db->query("SELECT id, nama, jabatan, desa_id FROM perangkat_desas WHERE nama LIKE '%SATINI%'")->fetchAll(PDO::FETCH_ASSOC);

$out = json_encode([
    'desas' => $desas,
    'users' => $users,
    'satini' => $perangkat
], JSON_PRETTY_PRINT);
file_put_contents(__DIR__ . '/investigate.json', $out);
echo "Investigated!";
