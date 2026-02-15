<?php
require_once 'DataSourceInterface.php';


class CsvDataSource implements DataSourceInterface {
private $file = __DIR__ . '/../data/data.csv';


private function read() {
if (!file_exists($this->file)) return [];
$rows = array_map('str_getcsv', file($this->file));
$header = array_shift($rows);
return array_map(fn($r) => array_combine($header, $r), $rows);
}


private function write($data) {
$f = fopen($this->file, 'w');
fputcsv($f, array_keys($data[0] ?? ['id','nazwa','kwota','kategoria','data_wydatku']));
foreach ($data as $row) fputcsv($f, $row);
fclose($f);
}


public function getAll() { return $this->read(); }


public function add($record) {
$data = $this->read();
$record['id'] = time();
$data[] = $record;
$this->write($data);
}


public function delete($id) {
$data = array_filter($this->read(), fn($r) => $r['id'] != $id);
$this->write(array_values($data));
}


public function update($id, $record) {
$data = $this->read();
foreach ($data as &$r) if ($r['id'] == $id) $r = array_merge($r, $record);
$this->write($data);
}


public function getChartData() {
$out = [];
foreach ($this->read() as $r) {
$out[$r['kategoria']] = ($out[$r['kategoria']] ?? 0) + $r['kwota'];
}
return $out;
}
}
