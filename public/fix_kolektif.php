<?php
try {
    $dbPath = dirname(__DIR__) . '/database/database.sqlite';
    $db = new PDO('sqlite:' . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db->beginTransaction();

    // 1. Create ajuan_pesertas table
    $db->exec("CREATE TABLE IF NOT EXISTS ajuan_pesertas (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        ajuan_id INTEGER NOT NULL,
        perangkat_desa_id INTEGER NOT NULL,
        jabatan_baru VARCHAR(255) NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (ajuan_id) REFERENCES ajuans(id) ON DELETE CASCADE,
        FOREIGN KEY (perangkat_desa_id) REFERENCES perangkat_desas(id) ON DELETE CASCADE
    )");
    echo "1. Table ajuan_pesertas created.<br>";

    // 2. Check if ajuans still has perangkat_desa_id column
    $q = $db->query("PRAGMA table_info(ajuans)");
    $cols = $q->fetchAll(PDO::FETCH_ASSOC);
    $hasPerangkatId = false;
    $hasJabatanBaru = false;
    foreach ($cols as $c) {
        if ($c['name'] === 'perangkat_desa_id')
            $hasPerangkatId = true;
        if ($c['name'] === 'jabatan_baru')
            $hasJabatanBaru = true;
    }

    if ($hasPerangkatId) {
        // 3. Migrate existing data to ajuan_pesertas
        $jabatanCol = $hasJabatanBaru ? 'jabatan_baru' : 'NULL';
        $db->exec("INSERT OR IGNORE INTO ajuan_pesertas (ajuan_id, perangkat_desa_id, jabatan_baru, created_at, updated_at)
                   SELECT id, perangkat_desa_id, {$jabatanCol}, created_at, updated_at FROM ajuans 
                   WHERE perangkat_desa_id IS NOT NULL");
        echo "2. Migrated existing data to ajuan_pesertas.<br>";

        // 4. Rebuild ajuans table without old columns
        $db->exec("CREATE TABLE IF NOT EXISTS ajuans_new (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            no_registrasi VARCHAR(255) NOT NULL UNIQUE,
            desa_id INTEGER NOT NULL,
            jenis_layanan_id INTEGER NOT NULL,
            alasan_pemberhentian_id INTEGER NULL,
            status VARCHAR(255) NOT NULL DEFAULT 'draft',
            folder_path VARCHAR(255) NULL,
            tgl_diajukan DATE NULL,
            tgl_sla_batas DATE NULL,
            posisi_surat VARCHAR(255) NULL,
            created_at DATETIME DEFAULT NULL,
            updated_at DATETIME DEFAULT NULL,
            FOREIGN KEY (desa_id) REFERENCES desas(id) ON DELETE CASCADE,
            FOREIGN KEY (jenis_layanan_id) REFERENCES jenis_layanans(id) ON DELETE CASCADE,
            FOREIGN KEY (alasan_pemberhentian_id) REFERENCES alasan_pemberhentians(id) ON DELETE CASCADE
        )");

        $db->exec("INSERT INTO ajuans_new (id, no_registrasi, desa_id, jenis_layanan_id, alasan_pemberhentian_id, status, folder_path, tgl_diajukan, tgl_sla_batas, posisi_surat, created_at, updated_at)
                   SELECT id, no_registrasi, desa_id, jenis_layanan_id, alasan_pemberhentian_id, status, folder_path, tgl_diajukan, tgl_sla_batas, posisi_surat, created_at, updated_at FROM ajuans");

        $db->exec("DROP TABLE ajuans");
        $db->exec("ALTER TABLE ajuans_new RENAME TO ajuans");
        echo "3. Restructured ajuans table (removed perangkat_desa_id + jabatan_baru).<br>";
    } else {
        echo "2. Ajuans already restructured, skipped.<br>";
    }

    // 5. Check peserta count
    $count = $db->query("SELECT COUNT(*) FROM ajuan_pesertas")->fetchColumn();
    echo "4. Total peserta records: {$count}<br>";

    $db->commit();
    echo "<br><b style='color:green'>SUCCESS! Database fully upgraded.</b>";
} catch (Exception $e) {
    if (isset($db) && $db->inTransaction())
        $db->rollBack();
    echo "<b style='color:red'>ERROR:</b> " . $e->getMessage() . " (line " . $e->getLine() . ")";
}
