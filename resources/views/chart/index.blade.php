@extends('layouts.layout')
@section('title', 'Home')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <?php
                $chartData = $topThree;
                ?>
                @include('components.chart.compare')
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->

@endsection
