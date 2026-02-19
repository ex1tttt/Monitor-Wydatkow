<?php
require_once 'config.php';

switch ($data_source) {
    case 'json':
        require_once 'datasources/JsonDataSource.php';
        $ds = new JsonDataSource();
        break;
    case 'csv':
        require_once 'datasources/CsvDataSource.php';
        $ds = new CsvDataSource();
        break;
    default:
        require_once 'datasources/MySQLDataSource.php';
        $ds = new MySQLDataSource();
        break;
}


$allData = $ds->getAll();
$chartData = $ds->getChartData();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Zarządzanie Wydatkami</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #e20e0e; }
        canvas { margin-top: 40px; }
    </style>
</head>
<body>
    <h1>Lol Lolik</h1>
    <table>
        <thead>
            <tr>
                <th>Nazwa</th>
                <th>Kwota</th>
                <th>Kategoria</th>
                <th>Data wydatku</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($allData as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['nazwa']) ?></td>
                <td><?= htmlspecialchars($row['kwota']) ?></td>
                <td><?= htmlspecialchars($row['kategoria']) ?></td>
                <td><?= htmlspecialchars($row['data_wydatku']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <canvas id="expenseChart" width="600" height="300"></canvas>

    <script>
    const ctx = document.getElementById('expenseChart').getContext('2d');
    const data = <?php echo json_encode($chartData); ?>;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: Object.keys(data),
            datasets: [{
                label: 'Wydatki wg kategorii',
                data: Object.values(data),
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    </script>
</body>
</html>
