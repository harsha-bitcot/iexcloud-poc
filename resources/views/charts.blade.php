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
                </div>

                <div class="col-xl-4 col-lg-5">
                    @include('components.chart.change')
                </div>
            </div>

        </div>
        <!-- /.container-fluid -->


@endsection
