<?php
require_once 'index.php';


$format = $_GET['format'];
$data = $ds->getAll();


if ($format === 'json') {
header('Content-Type: application/json');
header('Content-Disposition: attachment; filename="wydatki.json"');
echo json_encode($data, JSON_PRETTY_PRINT);
}


if ($format === 'csv') {
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="wydatki.csv"');
$out = fopen('php://output', 'w');
fputcsv($out, array_keys($data[0] ?? []));
foreach ($data as $row) fputcsv($out, $row);
fclose($out);
}