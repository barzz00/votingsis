<?php
require 'config.php';
$conn = mysqli_connect($hostname, $username, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if position parameter is set
if (!isset($_GET['position'])) {
    die("No position selected.");
}
$position = $_GET['position'];

// Fetch voting results for the selected position
$sql = "SELECT $position, COUNT(*) as votes FROM score GROUP BY $position";
$result = mysqli_query($conn, $sql);
$votesData = [];
while ($row = mysqli_fetch_assoc($result)) {
    $votesData[$row[$position]] = $row['votes'];
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Position Results</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="container">
    <h2 class="text-center">Results for <?php echo ucfirst(str_replace('_', ' ', $position)); ?></h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Candidate</th>
                <th>Votes</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($votesData as $candidate => $votes) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($candidate); ?></td>
                    <td><?php echo $votes; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <canvas id="positionChart"></canvas>
    <script>
        const ctx = document.getElementById('positionChart').getContext('2d');
        const votesData = <?php echo json_encode($votesData); ?>;
        const labels = Object.keys(votesData);
        const data = Object.values(votesData);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Votes',
                    data: data,
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
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
    <a href="cpanel.php" class="btn btn-primary">Back to Admin Panel</a>
</div>
</body>
</html>
