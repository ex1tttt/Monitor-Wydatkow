<?php
require_once 'DataSourceInterface.php';

class MySQLDataSource implements DataSourceInterface {

    private $pdo;

    public function __construct() {
        $this->pdo = new PDO(
            'mysql:host=localhost;dbname=monitor_wydatkow;charset=utf8',
            'root',
            '',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM wydatki ORDER BY data_wydatku DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function add($record) {
        $sql = "INSERT INTO wydatki (nazwa, kwota, kategoria, data_wydatku)
                VALUES (:nazwa, :kwota, :kategoria, :data)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nazwa' => $record['nazwa'],
            ':kwota' => $record['kwota'],
            ':kategoria' => $record['kategoria'],
            ':data' => $record['data_wydatku']
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM wydatki WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function update($id, $record) {
        $sql = "UPDATE wydatki
                SET nazwa=:nazwa, kwota=:kwota, kategoria=:kategoria, data_wydatku=:data
                WHERE id=:id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nazwa' => $record['nazwa'],
            ':kwota' => $record['kwota'],
            ':kategoria' => $record['kategoria'],
            ':data' => $record['data_wydatku'],
            ':id' => $id
        ]);
    }

    public function getChartData() {
        $sql = "
            SELECT kategoria, SUM(kwota) AS suma
            FROM wydatki
            GROUP BY kategoria
        ";
        $stmt = $this->pdo->query($sql);

        $out = [];
        foreach ($stmt as $row) {
            $out[$row['kategoria']] = (float)$row['suma'];
        }
        return $out;
    }
}
