<!DOCTYPE html>
<html>
<head>
    <title>Pie Chart Example</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pie-chart/1.0.0/pie-chart.min.js" integrity="sha512-RloOYfWgCwxbdExraq88FwUdFA/RQSuLJADn72+kUcKraQGrhn43BfDruK5dxKjqDGhNDhkE3h1bSoqdXxbGHg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body>

<canvas id="myPieChart"></canvas>

<?php
   
    $data = [
        'labels' => ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
        'datasets' => [
            [
                
                'data' => [300, 50, 100, 40, 120, 90],
                'backgroundColor' => [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                'borderColor' => [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                'borderWidth' => 1
            ]
        ]
    ];

    // Convert PHP array to JSON
    $jsonData = json_encode($data);
?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Get the JSON data from PHP
        var data = <?php echo $jsonData; ?>;

        var ctx = document.getElementById('myPieChart').getContext('2d');
        var myPieChart = new Chart(ctx, {
            type: 'pie',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw;
                            }
                        }
                    }
                }
            }
        });
    });
</script>

</body>
</html>
