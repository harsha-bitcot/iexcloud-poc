<!-- Area Chart -->
<?php
    $chartId = $chartId ?? '';
    $chartTitle = $chartTitle ?? 'Change (Total,Count)';
?>
<div class="card shadow mb-4">
    <!-- Card Header - Dropdown -->
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><?php echo $chartTitle;?></h6>
    </div>
    <!-- Card Body -->
    <div class="card-body">
        <div class="chart-pie pt-4">
            <canvas id="changeChart<?php echo $chartId;?>"></canvas>
        </div>
    </div>
</div>
<script>
    // Set new default font family and font color to mimic Bootstrap's default styling
    Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#858796';

    // Pie Chart Example
    var ctx = document.getElementById("changeChart<?php echo $chartId;?>");
    var myPieChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['gain', 'loss'],
            datasets: [
                {
                    data: <?php echo $changeValue; ?>,
                    backgroundColor: ['rgba(0,200,0,0.65)', 'rgba(200,0,0,0.65)'],
                    hoverBackgroundColor: ['#00c800', '#c80000'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                },
                {
                    data: <?php echo $changeCount; ?>,
                    backgroundColor: ['rgba(0,200,0,0.65)', 'rgba(200,0,0,0.65)'],
                    hoverBackgroundColor: ['#00c800', '#c80000'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }
            ],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
                callbacks: {
                    label: function(tooltipItem, chart) {
                        let label;
                        if (tooltipItem.datasetIndex === 1)
                        {
                            label = chart.labels[tooltipItem.index] + ' Count';
                        }else {
                            label = 'Total ' + chart.labels[tooltipItem.index];
                        }
                        return label + ': ' + chart.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                    }
                }
            },
            legend: {
                // display: false
                position: 'bottom'
            },
            cutoutPercentage: 60,
        },
    });
</script>
