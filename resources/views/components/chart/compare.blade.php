<?php
$chartId = $chartId ?? '';
$chartTitle = $chartTitle ?? 'Top '.count($chartData).' Companies';
?>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><?php echo $chartTitle;?></h6>
    </div>
    <div class="card-body" style="min-height: 420px; min-width: 310px">
        <div class="chart-bar">
            <div id="compareChart"></div>
        </div>
    </div>
    <div class="card-footer">
        <ul>
            @foreach($chartData as $ticker)
                <li>{{ $ticker }}: <a href="<?php echo URL::to('/'); ?>/charts/{{ $ticker }}">View Charts</a> | <a href="/companies/view/{{ $ticker }}">View Details</a> </li>
            @endforeach
        </ul>
    </div>
</div>




<script type="text/javascript">
    var seriesOptions = [],
        seriesCounter = 0,
        names = @json($chartData);

    /**
     * Create the chart when all data is loaded
     * @returns {undefined}
     */
    function createChart() {

        Highcharts.stockChart('compareChart', {

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
        '<?php echo URL::to('/'); ?>/chart/{{ $ticker }}/close',
        success
    );
    @endforeach
</script>
