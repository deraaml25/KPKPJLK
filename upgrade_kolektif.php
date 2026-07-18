<?php
try {
    $dbPath = __DIR__ . '/database/database.sqlite';
    $db = new PDO('sqlite:' . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db->beginTransaction();

    // 1. Create ajuan_pesertas
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

    // 2. See if old ajuans table has perangkat_desa_id
    $q = $db->query("PRAGMA table_info(ajuans)");
    $cols = $q->fetchAll(PDO::FETCH_ASSOC);

    $hasPerangkatId = false;
    foreach ($cols as $c) {
        if ($c['name'] === 'perangkat_desa_id') {
            $hasPerangkatId = true;
            break;
        }
    }

    if ($hasPerangkatId) {
        // 3. Migrate data
        $db->exec("INSERT INTO ajuan_pesertas (ajuan_id, perangkat_desa_id, jabatan_baru, created_at, updated_at)
                   SELECT id, perangkat_desa_id, jabatan_baru, created_at, updated_at FROM ajuans 
                   WHERE perangkat_desa_id IS NOT NULL");

        // 4. Recreate ajuans table without perangkat_desa_id and jabatan_baru
        $db->exec("CREATE TABLE ajuans_new (
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

        // Copy Data
        $db->exec("INSERT INTO ajuans_new (id, no_registrasi, desa_id, jenis_layanan_id, alasan_pemberhentian_id, status, folder_path, tgl_diajukan, tgl_sla_batas, posisi_surat, created_at, updated_at)
                   SELECT id, no_registrasi, desa_id, jenis_layanan_id, alasan_pemberhentian_id, status, folder_path, tgl_diajukan, tgl_sla_batas, posisi_surat, created_at, updated_at FROM ajuans");

        // Swap tables
        $db->exec("DROP TABLE ajuans");
        $db->exec("ALTER TABLE ajuans_new RENAME TO ajuans");

        echo "Successfully restructured ajuans and created ajuan_pesertas!<br>";
    } else {
        echo "Ajuans table already restructured!<br>";
    }

    $db->commit();
} catch (Exception $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    echo "ERROR: " . $e->getMessage() . " line: " . $e->getLine();
}
