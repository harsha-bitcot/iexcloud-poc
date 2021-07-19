@extends('layouts.layout')
@section('title', 'Charts')

@section('content')

        <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Content Row -->
            <div class="row">

                <div class="col-xl-8 col-lg-7">
                    <?php $chartId = 'asd'; ?>
                    @include('components.chart.changePercentage')
                    @include('components.chart.overview')
                    <?php
                        $chartId = 'abc';
                        $chartTitle = 'Custom Title';
                        $changePercent = $newChangePercent;
                    ?>
                    @include('components.chart.changePercentage')
                </div>

                <!-- Donut Chart -->
                <div class="col-xl-4 col-lg-5">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Donut Chart</h6>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="chart-pie pt-4">
                                <canvas id="myPieChart"></canvas>
                            </div>
                            {{--                                <hr>--}}
                            {{--                                Styling for the donut chart can be found in the--}}
                            {{--                                <code>/js/demo/chart-pie-demo.js</code> file.--}}
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- /.container-fluid -->


@endsection
