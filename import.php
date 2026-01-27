<?php
require_once 'index.php';


if ($_FILES) {
$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
$rows = [];


if ($ext === 'json') {
$rows = json_decode(file_get_contents($_FILES['file']['tmp_name']), true);
}


if ($ext === 'csv') {
$csv = array_map('str_getcsv', file($_FILES['file']['tmp_name']));
$header = array_shift($csv);
foreach ($csv as $r) $rows[] = array_combine($header, $r);
}


foreach ($rows as $r) {
if (!isset($r['nazwa'],$r['kwota'],$r['kategoria'],$r['data_wydatku'])) continue;
if (!is_numeric($r['kwota'])) continue;
$ds->add($r);
}
}