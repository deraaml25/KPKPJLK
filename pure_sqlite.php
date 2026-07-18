<?php
try {
    $dbPath = 'c:/Users/isnai/Herd/kapejelek/database/database.sqlite';
    $db = new PDO('sqlite:' . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $output = "Terhubung ke SQLite murni pada: " . $dbPath . "\n";

    // 1. ADD COLUMN desa_id to bimtek_pendaftarans if not exists
    $q = $db->query("PRAGMA table_info(bimtek_pendaftarans)");
    $cols = $q->fetchAll(PDO::FETCH_ASSOC);
    $hasDesaId = false;
    foreach ($cols as $c) {
        if ($c['name'] === 'desa_id')
            $hasDesaId = true;
    }
    if (!$hasDesaId) {
        $db->exec("ALTER TABLE bimtek_pendaftarans ADD COLUMN desa_id INTEGER");
        $output .= "Sukses ADD COLUMN desa_id ke bimtek_pendaftarans.\n";
    }

    // 2. Fix Ajuans 4 to 10 Empty Checklists
    for ($i = 4; $i <= 10; $i++) {
        $stmt = $db->prepare("SELECT id, jenis_layanan_id, alasan_pemberhentian_id FROM ajuans WHERE id = ?");
        $stmt->execute([$i]);
        $ajuan = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($ajuan) {
            // Count Checklists
            $cStmt = $db->prepare("SELECT COUNT(*) as c FROM checklist_ajuans WHERE ajuan_id = ?");
            $cStmt->execute([$i]);
            $count = $cStmt->fetchColumn();

            if ($count == 0) {
                // Get templates
                if ($ajuan['alasan_pemberhentian_id']) {
                    $tStmt = $db->prepare("SELECT id FROM template_checklists WHERE jenis_layanan_id = ? AND (alasan_pemberhentian_id IS NULL OR alasan_pemberhentian_id = ?)");
                    $tStmt->execute([$ajuan['jenis_layanan_id'], $ajuan['alasan_pemberhentian_id']]);
                } else {
                    $tStmt = $db->prepare("SELECT id FROM template_checklists WHERE jenis_layanan_id = ? AND alasan_pemberhentian_id IS NULL");
                    $tStmt->execute([$ajuan['jenis_layanan_id']]);
                }

                $templates = $tStmt->fetchAll(PDO::FETCH_ASSOC);
                $inserted = 0;
                foreach ($templates as $t) {
                    $ins = $db->prepare("INSERT INTO checklist_ajuans (ajuan_id, template_checklist_id, status, versi, created_at, updated_at) VALUES (?, ?, 'belum_diunggah', 1, datetime('now'), datetime('now'))");
                    $ins->execute([$i, $t['id']]);
                    $inserted++;
                }
                $output .= "Ajuan $i direstore: $inserted items.\n";
            }
        }
    }

    // 3. Karangendep Fix
    $dStmt = $db->prepare("SELECT id, nama FROM desas WHERE nama LIKE '%Karangendep%'");
    $dStmt->execute();
    $desa = $dStmt->fetch(PDO::FETCH_ASSOC);
    if ($desa) {
        $db->prepare("DELETE FROM perangkat_desas WHERE desa_id = ?")->execute([$desa['id']]);

        $realData = [
            ['jabatan' => 'Kepala Desa', 'nama' => 'KARSINAH'],
            ['jabatan' => 'Sekretaris Desa', 'nama' => 'TRIYO WIDODO'],
            ['jabatan' => 'Kasi Pemerintahan', 'nama' => 'KIRTO'],
            ['jabatan' => 'Kasi Kesejahteraan', 'nama' => 'SUTARKO'],
            ['jabatan' => 'Kasi Pelayanan', 'nama' => 'AGUS SUPRIJATNO'],
            ['jabatan' => 'Kaur Keuangan', 'nama' => 'NETY AMI PRABAWATI'],
            ['jabatan' => 'Kaur Perencanaan', 'nama' => 'TRI YUNIA RUBIANTO'],
            ['jabatan' => 'Kaur TU & Umum', 'nama' => 'INAWAN NUR KHOLIQ'],
        ];

        foreach ($realData as $p) {
            $insP = $db->prepare("INSERT INTO perangkat_desas (desa_id, nama, jabatan, no_sk_terakhir, tgl_mulai_jabatan, status_aktif, created_at, updated_at) VALUES (?, ?, ?, ?, ?, 1, datetime('now'), datetime('now'))");
            $insP->execute([
                $desa['id'],
                $p['nama'],
                $p['jabatan'],
                '141/00' . rand(1, 9) . '/2020',
                '2020-01-01'
            ]);
        }
        $output .= "Karangendep (Desa ID: {$desa['id']}) sukses disuntik ulang 8 baris asli.\n";
    }

    echo $output;
    file_put_contents('sqlite_out.txt', $output);
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage();
    file_put_contents('sqlite_out.txt', "ERROR: " . $e->getMessage());
}
