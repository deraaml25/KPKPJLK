<?php
$output = [];
$return_var = 0;
exec('cd ' . dirname(__DIR__) . ' && php artisan app:import-perangkat 2>&1', $output, $return_var);

$dbPath = dirname(__DIR__) . '/database/database.sqlite';
$db = new PDO('sqlite:' . $dbPath);
$count = $db->query("SELECT COUNT(*) FROM perangkat_desas")->fetchColumn();

echo "<h3>Import Routine Output:</h3>";
echo "<pre>" . implode("\n", $output) . "</pre>";
echo "<h3>Database Count After Import:</h3>";
echo "Total perangkat desa: " . $count;
