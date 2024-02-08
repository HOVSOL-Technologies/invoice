<?php
session_start();
require_once 'lib/LoginClass.php';
include_once("includes/header.php");

$user = new LoginClass();

if (!$user->isLoggedIn()) {
    header('Location: login.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profit and Loss Pie Chart</title>
    <!-- Include Chart.js from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div style="width: 20%; margin: auto;">
        <canvas id="profitLossChart" width="400" height="400"></canvas>
    </div>


    <script>
        // PHP-generated data
        var data = <?php echo json_encode(getProfitLossData()); ?>;

        // Create the pie chart
        var ctx = document.getElementById('profitLossChart').getContext('2d');
        var myPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Profit', 'Loss'],
                datasets: [{
                    data: data,
                    backgroundColor: ['#36A2EB', '#FF6384'],
                    hoverBackgroundColor: ['#36A2EB', '#FF6384']
                }]
            }
        });
    </script>

    <?php
    // Function to generate sample profit and loss data
    function getProfitLossData() {
        $profit = rand(1000, 5000);
        $loss = rand(500, 2000);

        return [$profit, $loss];
    }
    ?>
</body>
</html>
