<?php
$host = '127.0.0.1';
$db = 'kapejelek'; // assuming the standard Herd database name or read from env
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

// read from .env if needed
$env = file_get_contents('.env');
preg_match('/DB_DATABASE=(.*)/', $env, $m);
if (isset($m[1]))
    $db = trim($m[1]);
preg_match('/DB_USERNAME=(.*)/', $env, $m);
if (isset($m[1]))
    $user = trim($m[1]);
preg_match('/DB_PASSWORD=(.*)/', $env, $m);
if (isset($m[1]))
    $pass = trim($m[1]);

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    $stmt = $pdo->query("SELECT id, nama, kecamatan_id FROM desas WHERE nama LIKE '%Karangendep%'");
    $desa = $stmt->fetch();

    if ($desa) {
        // Force delete
        $pdo->prepare("DELETE FROM perangkat_desas WHERE desa_id = ?")->execute([$desa['id']]);

        $realData = [
            ['jabatan' => 'Kepala Desa', 'nama' => 'KARSINAH'],
            ['jabatan' => 'Sekretaris Desa', 'nama' => 'TRIYO WIDODO'],
            ['jabatan' => 'Kasi Pemerintahan', 'nama' => 'KIRTO'],
            ['jabatan' => 'Kasi Kesejahteraan', 'nama' => 'SUTARKO'],
            ['jabatan' => 'Kasi Pelayanan', 'nama' => 'AGUS SUPRIJATNO'],
            ['jabatan' => 'Kaur Keuangan', 'nama' => 'NETY AMI PRABAWATI'],
            ['jabatan' => 'Kaur Perencanaan', 'nama' => 'TRI YUNIA RUBIANTO'],
            ['jabatan' => 'Kaur TU & Umum', 'nama' => 'INAWAN NUR KHOLI'],
        ];

        $insert = $pdo->prepare("INSERT INTO perangkat_desas (desa_id, nama, jabatan, nomor_sk, tgl_sk, tgl_mulai, status_aktif, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
        foreach ($realData as $r) {
            $insert->execute([
                $desa['id'],
                $r['nama'],
                $r['jabatan'],
                '141/00' . rand(1, 9) . '/2020',
                '2020-01-01',
                '2020-01-01',
                1
            ]);
        }
        echo "BERHASIL UPDATE DATA KARANGENDEP ID: " . $desa['id'];
    } else {
        echo "DESA TIDAK DITEMUKAN: " . json_encode($pdo->query("SELECT id, nama FROM desas LIMIT 5")->fetchAll());
    }
} catch (PDOException $e) {
    echo "GAGAL: " . $e->getMessage();
}
