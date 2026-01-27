<?php
interface DataSourceInterface {
public function getAll();
public function add($record);
public function delete($id);
public function update($id, $record);
public function getChartData();
}