<?php
// todo add support to append text to title
$chartTitle = $chartTitle ?? 'All indicators';
?>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><?php echo $chartTitle;?></h6>
    </div>
    <div class="card-body" style="min-height: 750px">
        <div class="main-wrapper">
            <div class="selectors-container">
                <div class="col">
                    <label for="overlays">Overlays:</label>
                    <select class="left-select" id="overlays">
                        <option value="abands">Acceleration Bands</option>
                        <option value="bb">Bollinger Bands</option>
                        <option value="dema">DEMA (Double Exponential Moving Average)</option>
                        <option value="ema">EMA (Exponential Moving Average)</option>
                        <option value="ikh">Ichimoku Kinko Hyo</option>
                        <option value="keltnerchannels">Keltner Channels</option>
                        <option value="linearRegression">Linear Regression</option>
                        <option value="pivotpoints">Pivot Points</option>
                        <option value="pc" selected="selected">Price Channel</option>
                        <option value="priceenvelopes">Price Envelopes</option>
                        <option value="psar">PSAR (Parabolic SAR)</option>
                        <option value="sma">SMA (Simple Moving Average)</option>
                        <option value="supertrend">Super Trend</option>
                        <option value="tema">TEMA (Triple Exponential Moving Average)</option>
                        <option value="vbp">VbP (Volume by Price)</option>
                        <option value="vwap">WMA (Weighted Moving Average)</option>
                        <option value="wma">VWAP (Volume Weighted Average Price)</option>
                        <option value="zigzag">Zig Zag</option>
                    </select>
                </div>
                <div class="col">
                    <label for="oscillators">Oscillators:</label>
                    <select class="right-select" id="oscillators">
                        <option value="apo">Absolute price indicator</option>
                        <option value="ad">A/D (Accumulation/Distribution)</option>
                        <option value="aroon">Aroon</option>
                        <option value="aroonoscillator">Aroon oscillator</option>
                        <option value="atr">ATR (Average True Range)</option>
                        <option value="ao">Awesome oscillator</option>
                        <option value="cci">CCI (Commodity Channel Index)</option>
                        <option value="chaikin">Chaikin</option>
                        <option value="cmf">CMF (Chaikin Money Flow)</option>
                        <option value="disparityindex">Disparity Index</option>
                        <option value="cmo">CMO (Chande Momentum Oscillator)</option>
                        <option value="dmi">DMI (Directional Movement Index)</option>
                        <option value="dpo">Detrended price</option>
                        <option value="linearRegressionAngle">Linear Regression Angle</option>
                        <option value="linearRegressionIntercept">Linear Regression Intercept</option>
                        <option value="linearRegressionSlope">Linear Regression Slope</option>
                        <option value="klinger">Klinger Oscillator</option>
                        <option value="macd" selected="selected">MACD (Moving Average Convergence Divergence)</option>
                        <option value="mfi">MFI (Money Flow Index)</option>
                        <option value="momentum">Momentum</option>
                        <option value="natr">NATR (Normalized Average True Range)</option>
                        <option value="obv">OBV (On-Balance Volume)</option>
                        <option value="ppo">Percentage Price oscillator</option>
                        <option value="roc">RoC (Rate of Change)</option>
                        <option value="rsi">RSI (Relative Strength Index)</option>
                        <option value="slowstochastic">Slow Stochastic</option>
                        <option value="stochastic">Stochastic</option>
                        <option value="trix">TRIX</option>
                        <option value="williamsr">Williams %R</option>
                    </select>
                </div>
            </div>
            <div class="chart-area">
                <div id="allIndicatorsChart{{ $chartCompany }}"></div>
            </div>
        </div>
    </div>
</div>






<script type="text/javascript">
    Highcharts.getJSON('http://localhost:8000/chart/{{ $chartCompany }}/OHLCV', function (data) {
        // split the data set into ohlc and volume
        var ohlc = [],
            volume = [],
            dataLength = data.length;

        for (var i = 0; i < dataLength; i += 1) {
            ohlc.push([
                data[i][0], // the date
                data[i][1], // open
                data[i][2], // high
                data[i][3], // low
                data[i][4] // close
            ]);

            volume.push([
                data[i][0], // the date
                data[i][5] // the volume
            ]);
        }

        // create the chart
        Highcharts.stockChart('allIndicatorsChart{{ $chartCompany }}', {
            chart: {
                height: 600
            },
            title: {
                text: '{{ $chartCompany }} Historical'
            },
            subtitle: {
                text: 'All indicators'
            },
            legend: {
                enabled: true
            },
            rangeSelector: {
                selected: 2
            },
            yAxis: [{
                height: '60%'
            }, {
                top: '60%',
                height: '20%'
            }, {
                top: '80%',
                height: '20%'
            }],
            plotOptions: {
                series: {
                    showInLegend: true
                }
            },
            series: [{
                type: 'candlestick',
                id: '{{ $chartCompany }}',
                name: '{{ $chartCompany }}',
                data: data
            }, {
                type: 'column',
                id: 'volume',
                name: 'Volume',
                data: volume,
                yAxis: 1
            }, {
                type: 'pc',
                id: 'overlay',
                linkedTo: '{{ $chartCompany }}',
                yAxis: 0
            }, {
                type: 'macd',
                id: 'oscillator',
                linkedTo: '{{ $chartCompany }}',
                yAxis: 2
            }]
        }, function (chart) {
            document.getElementById('overlays').addEventListener('change', function (e) {
                var series = chart.get('overlay');

                if (series) {
                    series.remove(false);
                    chart.addSeries({
                        type: e.target.value,
                        linkedTo: '{{ $chartCompany }}',
                        id: 'overlay'
                    });
                }
            });

            document.getElementById('oscillators').addEventListener('change', function (e) {
                var series = chart.get('oscillator');

                if (series) {
                    series.remove(false);
                    chart.addSeries({
                        type: e.target.value,
                        linkedTo: '{{ $chartCompany }}',
                        id: 'oscillator',
                        yAxis: 2
                    });
                }
            });
        });
    });

</script>
