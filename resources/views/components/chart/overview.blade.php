<!-- Bar Chart -->
<?php
    $chartId = $chartId ?? '';
    $chartTitle = $chartTitle ?? 'Overview';
?>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><?php echo $chartTitle;?></h6>
    </div>
    <div class="card-body">
        <div class="chart-bar">
            <canvas id="overviewChart<?php echo $chartId;?>"></canvas>
        </div>
    </div>
</div>


<script>
    // Set new default font family and font color to mimic Bootstrap's default styling
    Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#858796';


    // change percentage Chart
    var ctx = document.getElementById("overviewChart<?php echo $chartId;?>");
    var myLineChart = new Chart(ctx, {
        data: {
            labels: <?php echo $date; ?> ,
            datasets: [
                {
                    type: 'line',
                    tension: 0,
                    steppedLine: 'middle',
                    label: 'Close',
                    yAxisID: 'left',
                    data: <?php echo $close; ?>,
                    borderColor: "rgba(0,255,0,0.64)",
                    fill: false,
                },
                {
                    type: 'line',
                    tension: 0,
                    steppedLine: 'middle',
                    label: 'Open',
                    yAxisID: 'left',
                    data: <?php echo $open; ?>,
                    borderColor: "rgb(255,0,0,0.64)",
                    fill: false,
                },
                {
                    type: 'bar',
                    label: 'Range',
                    yAxisID: 'left',
                    data: <?php echo $range; ?>,
                    backgroundColor: "rgba(0,255,217,0.62)",
                },
                {
                    type: 'scatter',
                    label: 'Change',
                    yAxisID: 'right',
                    data: <?php echo $change; ?>,
                    borderColor: "rgb(0,0,255)",
                }
            ],
        },
        options: {
            maintainAspectRatio: false,
            layout: {
                padding: {
                    left: 10,
                    right: 25,
                    top: 25,
                    bottom: 0
                }
            },
            scales: {
                xAxes: [{
                    stacked: true,
                    time: {
                        unit: 'date'
                    },
                    gridLines: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        maxTicksLimit: 3,
                        callback: function(value, index, values) {
                            return value.split(":")[0];
                        }
                    }
                }],
                yAxes: [
                    {
                        id: 'left',
                        type: 'linear',
                        position: 'left',
                        scalePositionLeft: true,
                        ticks: {
                            maxTicksLimit: 5,
                            padding: 10,
                            // Include a dollar sign in the ticks
                            callback: function(value, index, values) {
                                return value;
                            }
                        },
                        gridLines: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    },
                    {
                        id: 'right',
                        type: 'linear',
                        position: 'right',
                        scalePositionLeft: false,
                        ticks: {
                            maxTicksLimit: 5,
                            padding: 10,
                            // Include a dollar sign in the ticks
                            callback: function(value, index, values) {
                                return value;
                            }
                        }
                    }
                ],
            },
            legend: {
                // display: false
                position: 'bottom'
            },
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                titleMarginBottom: 10,
                titleFontColor: '#6e707e',
                titleFontSize: 14,
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                intersect: false,
                mode: 'index',
                caretPadding: 10,
                callbacks: {
                    label: function(tooltipItem, chart) {
                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                        if (datasetLabel === 'Range'){
                            let value = tooltipItem.value;
                            value = value.slice(1,value.search(']'));
                            value = value.split(", ");
                            let response = ['Low: ' + value[0]];
                            response.push('High: ' + value[1]);
                            return response;
                        }
                        return datasetLabel + ' ' + tooltipItem.yLabel;
                    }
                }
            }
        }
    });

</script>
