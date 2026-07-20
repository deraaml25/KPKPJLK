<?php
$rows = json_decode(file_get_contents(__DIR__ . '/../data_desa.json'), true);
array_shift($rows);
$found = null;
foreach ($rows as $row) {
    if (($row['B'] ?? '') === 'Patikraja' && trim((string)($row['C'] ?? '')) === 'KARANGENDEP') {
        $found = $row;
        break;
    }
}
if (!$found) {
    echo "not found\n";
    exit(1);
}
foreach ($found as $k => $v) {
    echo $k . ' => ' . (is_array($v) ? json_encode($v) : $v) . PHP_EOL;
}
