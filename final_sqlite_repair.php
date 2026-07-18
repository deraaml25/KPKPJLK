<?php
try {
    $dbPath = 'C:/Users/isnai/Herd/kapejelek/database/database.sqlite';
    $db = new PDO('sqlite:' . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. ADD COLUMN
    $q = $db->query("PRAGMA table_info(bimtek_pendaftarans)");
    $cols = $q->fetchAll(PDO::FETCH_ASSOC);
    $hasDesaId = false;
    foreach ($cols as $c) {
        if ($c['name'] === 'desa_id')
            $hasDesaId = true;
    }
    if (!$hasDesaId) {
        $db->exec("ALTER TABLE bimtek_pendaftarans ADD COLUMN desa_id INTEGER");
    }

    // 2. Fix Ajuans 4 to 10
    for ($i = 4; $i <= 10; $i++) {
        $stmt = $db->prepare("SELECT id, jenis_layanan_id, alasan_pemberhentian_id FROM ajuans WHERE id = ?");
        $stmt->execute([$i]);
        $ajuan = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($ajuan) {
            $cStmt = $db->prepare("SELECT COUNT(*) as c FROM checklist_ajuans WHERE ajuan_id = ?");
            $cStmt->execute([$i]);
            if ($cStmt->fetchColumn() == 0) {
                if ($ajuan['alasan_pemberhentian_id']) {
                    $tStmt = $db->prepare("SELECT id FROM template_checklists WHERE jenis_layanan_id = ? AND (alasan_pemberhentian_id IS NULL OR alasan_pemberhentian_id = ?)");
                    $tStmt->execute([$ajuan['jenis_layanan_id'], $ajuan['alasan_pemberhentian_id']]);
                } else {
                    $tStmt = $db->prepare("SELECT id FROM template_checklists WHERE jenis_layanan_id = ? AND alasan_pemberhentian_id IS NULL");
                    $tStmt->execute([$ajuan['jenis_layanan_id']]);
                }
                $templates = $tStmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($templates as $t) {
                    $db->prepare("INSERT INTO checklist_ajuans (ajuan_id, template_checklist_id, status, versi, created_at, updated_at) VALUES (?, ?, 'belum_diunggah', 1, datetime('now'), datetime('now'))")->execute([$i, $t['id']]);
                }
            }
        }
    }

    // 3. Karangendep Update & Extent
    $desaStmt = $db->prepare("SELECT id, nama FROM desas WHERE nama LIKE '%Karangendep%'");
    $desaStmt->execute();
    $desa = $desaStmt->fetch(PDO::FETCH_ASSOC);
    if ($desa) {
        $dId = $desa['id'];
        // Update first one (Budi Santoso) to KARSINAH to preserve foreign keys
        $firstPStmt = $db->prepare("SELECT id FROM perangkat_desas WHERE desa_id = ? ORDER BY id ASC LIMIT 1");
        $firstPStmt->execute([$dId]);
        $firstP = $firstPStmt->fetch(PDO::FETCH_ASSOC);

        if ($firstP) {
            $db->prepare("UPDATE perangkat_desas SET nama = 'KARSINAH', jabatan = 'Kepala Desa', no_sk_terakhir = '141/001/2020', tgl_mulai_jabatan = '2020-01-01', status_aktif = 1 WHERE id = ?")->execute([$firstP['id']]);
        }

        // Count how many we have now. If it's less than 2, it means we only had Budi.
        // If we only have KARSINAH, we need to insert the rest. 
        $cntStmt = $db->prepare("SELECT COUNT(*) FROM perangkat_desas WHERE desa_id = ?");
        $cntStmt->execute([$dId]);
        if ($cntStmt->fetchColumn() < 2) {
            $realData = [
                ['jabatan' => 'Sekretaris Desa', 'nama' => 'TRIYO WIDODO'],
                ['jabatan' => 'Kasi Pemerintahan', 'nama' => 'KIRTO'],
                ['jabatan' => 'Kasi Kesejahteraan', 'nama' => 'SUTARKO'],
                ['jabatan' => 'Kasi Pelayanan', 'nama' => 'AGUS SUPRIJATNO'],
                ['jabatan' => 'Kaur Keuangan', 'nama' => 'NETY AMI PRABAWATI'],
                ['jabatan' => 'Kaur Perencanaan', 'nama' => 'TRI YUNIA RUBIANTO'],
                ['jabatan' => 'Kaur TU & Umum', 'nama' => 'INAWAN NUR KHOLIQ'],
            ];
            foreach ($realData as $p) {
                $db->prepare("INSERT INTO perangkat_desas (desa_id, nama, jabatan, no_sk_terakhir, tgl_mulai_jabatan, status_aktif, created_at, updated_at) VALUES (?, ?, ?, ?, ?, 1, datetime('now'), datetime('now'))")
                    ->execute([$dId, $p['nama'], $p['jabatan'], '141/00' . rand(1, 9) . '/2020', '2020-01-01']);
            }
        }
    }

    file_put_contents('final_repair.txt', "REPAIR COMPLETE ON " . $dbPath);
} catch (Exception $e) {
    file_put_contents('final_repair.txt', "ERROR: " . $e->getMessage());
}
