<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Highstock Example</title>

    <style type="text/css">

    </style>
</head>
<body>
<script src="../../code/highstock.js"></script>
<script src="../../code/modules/data.js"></script>
<script src="../../code/modules/exporting.js"></script>
<script src="../../code/modules/export-data.js"></script>


<div id="container" style="height: 400px; min-width: 310px"></div>


<script type="text/javascript">
    var seriesOptions = [],
        seriesCounter = 0,
        names = @json($chartData);
        // names = ['MSFT', 'AAPL', 'WK'];

        // console.log();
    /**
     * Create the chart when all data is loaded
     * @returns {undefined}
     */
    function createChart() {

        Highcharts.stockChart('container', {

            rangeSelector: {
                selected: 4
            },

            yAxis: {
                labels: {
                    formatter: function () {
                        return (this.value > 0 ? ' + ' : '') + this.value + '%';
                    }
                },
                plotLines: [{
                    value: 0,
                    width: 2,
                    color: 'silver'
                }]
            },

            plotOptions: {
                series: {
                    compare: 'percent',
                    showInNavigator: true
                }
            },

            tooltip: {
                pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.change}%)<br/>',
                valueDecimals: 2,
                split: true
            },

            series: seriesOptions
        });
    }

    function success(data) {
        console.log(data);
        var name = this.url.match(/(<?php echo implode('|', $chartData) ?>)/)[0].toUpperCase();
        var i = names.indexOf(name);
        seriesOptions[i] = {
            name: name,
            data: data
        };

        // As we're loading the data asynchronously, we don't know what order it
        // will arrive. So we keep a counter and create the chart when all the data is loaded.
        seriesCounter += 1;

        if (seriesCounter === names.length) {
            createChart();
        }
    }

    @foreach($chartData as $ticker)
        Highcharts.getJSON(
            'http://localhost:8000/chart/{{ $ticker }}/close',
            success
        );
    @endforeach
</script>
</body>
</html>
