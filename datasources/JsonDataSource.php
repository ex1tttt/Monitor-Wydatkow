<?php
require_once 'DataSourceInterface.php';


class JsonDataSource implements DataSourceInterface {
private $file = __DIR__ . '/../data/data.json';


private function read() {
if (!file_exists($this->file)) return [];
return json_decode(file_get_contents($this->file), true) ?? [];
}


private function write($data) {
file_put_contents($this->file, json_encode($data, JSON_PRETTY_PRINT));
}


public function getAll() {
return $this->read();
}


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
foreach ($data as &$r) {
if ($r['id'] == $id) {
$r = array_merge($r, $record);
}
}
$this->write($data);
}


public function getChartData() {
$result = [];
foreach ($this->read() as $r) {
$cat = $r['kategoria'];
$result[$cat] = ($result[$cat] ?? 0) + $r['kwota'];
}
return $result;
}
}