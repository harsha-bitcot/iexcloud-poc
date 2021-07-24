@extends('layouts.layout')
@section('title', $company->companyName)

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Details</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <p><span class="text-primary">Name:</span> {{ $company->companyName }}</p>
                        <p><span class="text-primary">Symbol:</span> {{ $company->symbol }}</p>
                        <p><span class="text-primary">description:</span> {{ $company->description }}</p>
                        <p><span class="text-primary">Address:</span> {{ $company->city }}, {{ $company->state }}, {{ $company->country }}</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Information</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <p><span class="text-primary">Status:</span> <?php echo $company->status ? '<span style="color: green">Active</span>' : '<span style="color: red">In-active</span>'; ?></p>
                        <p><span class="text-primary">Marked for dialy data update:</span> <?php echo $company->updateDailyData ? '<span style="color: green">True</span>' : '<span style="color: red">False</span>'; ?></p>
                        <p><span class="text-primary">sector:</span> {{ $company->sector }}</p>
                        <p><span class="text-primary">exchange:</span> {{ $company->exchange }}</p>
                        <p><span class="text-primary">industry:</span> {{ $company->industry }}</p>
                        <p><span class="text-primary">website:</span> {{ $company->website }}</p>
                        <p><span class="text-primary">previous 52 week high:</span> {{ $previousFiftyTwoWeekData['high']->max() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3">
                        <h6 class="m-0 text-center font-weight-bold text-primary">Past one month</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php
                $chartId = 'previousMonth';
                extract($previousMonthData);
                ?>
                @include('components.chart.overview')
            </div>
        </div>
        <div class="row">

            <div class="col-xl-8 col-lg-7">
                @include('components.chart.changePercentage')
            </div>

            <div class="col-xl-4 col-lg-5">
                @include('components.chart.change')
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3">
                        <h6 class="m-0 text-center font-weight-bold text-primary">Past 52 weeks</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php
                $chartId = 'previousFiftyTwoWeek';
                extract($previousFiftyTwoWeekData);
                ?>
                @include('components.chart.overview')
            </div>
        </div>
        <div class="row">

            <div class="col-xl-8 col-lg-7">
                @include('components.chart.changePercentage')
            </div>

            <div class="col-xl-4 col-lg-5">
                @include('components.chart.change')
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3">
                        <h6 class="m-0 text-center font-weight-bold text-primary">Past 52 weeks(Weekly)</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php
                $chartId = 'weeklyFiftyTwoWeek';
                extract($weeklyFiftyTwoWeekData);
                ?>
                @include('components.chart.overview')
            </div>
        </div>
        <div class="row">

            <div class="col-xl-8 col-lg-7">
                @include('components.chart.changePercentage')
            </div>

            <div class="col-xl-4 col-lg-5">
                @include('components.chart.change')
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3">
                        <h6 class="m-0 text-center font-weight-bold text-primary">Past 52 weeks(Monthly)</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php
                $chartId = 'monthlyFiftyTwoWeek';
                extract($monthlyFiftyTwoWeekData);
                ?>
                @include('components.chart.overview')
            </div>
        </div>
        <div class="row">

            <div class="col-xl-8 col-lg-7">
                @include('components.chart.changePercentage')
            </div>

            <div class="col-xl-4 col-lg-5">
                @include('components.chart.change')
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->

@endsection
