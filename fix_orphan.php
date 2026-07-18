<?php
try {
    $dbPath = __DIR__ . '/database/database.sqlite';
    $db = new PDO('sqlite:' . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get Karangendep ID
    $desaStmt = $db->prepare("SELECT id FROM desas WHERE nama_desa LIKE '%Karangendep%' LIMIT 1");
    $desaStmt->execute();
    $desaId = $desaStmt->fetchColumn();

    if ($desaId) {
        // Get valid perangkat IDs for this desa
        $validPStmt = $db->prepare("SELECT id FROM perangkat_desas WHERE desa_id = ?");
        $validPStmt->execute([$desaId]);
        $validIds = $validPStmt->fetchAll(PDO::FETCH_COLUMN);

        if (!empty($validIds)) {
            $defaultId = $validIds[0]; // Karsinah

            // Find all Ajuans for this desa that have invalid perangkat_desa_id
            $q = clone $db;

            // Re-assign orphaned ajuans
            // An ajuan is orphaned if its perangkat_desa_id is not in the validIds list
            $placeholders = implode(',', array_fill(0, count($validIds), '?'));

            // we will just update all ajuans where perangkat_desa_id is not in validIds
            $stmt = $db->prepare("UPDATE ajuans SET perangkat_desa_id = ? WHERE id IN (
                SELECT a.id FROM ajuans a
                LEFT JOIN perangkat_desas p ON a.perangkat_desa_id = p.id
                WHERE p.id IS NULL
            )");
            $stmt->execute([$defaultId]);

            echo "Successfully relinked orphaned Ajuans to Perangkat ID $defaultId";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
