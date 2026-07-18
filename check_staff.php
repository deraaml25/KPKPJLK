<?php
$json = file_get_contents(__DIR__ . '/data_desa.json');
$data = json_decode($json, true);
$result = [];
foreach ($data as $item) {
    if (stripos(var_export($item, true), 'Karangendep') !== false) {
        $result[] = $item;
    }
}
file_put_contents(__DIR__ . '/karangendep_staff.json', json_encode($result, JSON_PRETTY_PRINT));
echo "Done";
