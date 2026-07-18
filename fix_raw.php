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

    // Check ajuan 4 to 10
    $output = "";
    for ($i = 4; $i <= 10; $i++) {
        $stmt = $pdo->prepare("SELECT id, jenis_layanan_id, alasan_pemberhentian_id FROM ajuans WHERE id = ?");
        $stmt->execute([$i]);
        $ajuan = $stmt->fetch();
        if ($ajuan) {
            $output .= "Ajuan ID $i: Layan: {$ajuan['jenis_layanan_id']}, Alasan: {$ajuan['alasan_pemberhentian_id']}\n";

            // Generate checklist
            $q = $pdo->prepare("SELECT * FROM template_checklists WHERE jenis_layanan_id = ? AND (alasan_pemberhentian_id IS NULL OR alasan_pemberhentian_id = ?)");
            $q->execute([$ajuan['jenis_layanan_id'], $ajuan['alasan_pemberhentian_id']]);
            $templates = $q->fetchAll();
            $output .= "  Templates: " . count($templates) . "\n";

            // Check existing
            $cq = $pdo->prepare("SELECT COUNT(*) as c FROM checklist_ajuans WHERE ajuan_id = ?");
            $cq->execute([$i]);
            $cc = $cq->fetch();
            $output .= "  Existing Checklists: " . $cc['c'] . "\n";

            // Fix dynamically if missing
            if ($cc['c'] == 0 && count($templates) > 0) {
                foreach ($templates as $t) {
                    $in = $pdo->prepare("INSERT IGNORE INTO checklist_ajuans (ajuan_id, template_checklist_id, status, versi, created_at, updated_at) VALUES (?, ?, 'belum_diunggah', 1, NOW(), NOW())");
                    $in->execute([$i, $t['id']]);
                }
                $output .= "  => FIXED (INSERTED " . count($templates) . ")\n";
            }
        }
    }

    // save output
    file_put_contents('debug_raw.txt', $output);

} catch (PDOException $e) {
    file_put_contents('debug_raw.txt', "ERROR: " . $e->getMessage());
}
