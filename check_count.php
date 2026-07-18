<?php
$dbPath = __DIR__ . '/database/database.sqlite';
$db = new PDO('sqlite:' . $dbPath);
$count = $db->query("SELECT COUNT(*) FROM perangkat_desas")->fetchColumn();
echo "Total perangkat desa: " . $count;
