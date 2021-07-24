@extends('layouts.layout')
@section('title', $company->companyName)

@section('quickNav')
    <a href="/companies/view/{{ $company->symbol }}">View Details</a>
@endsection

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <?php
                $chartCompany = $company->symbol;
                ?>
                @include('components.chart.ohlcv')
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                @include('components.chart.allIndicators')
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->

@endsection
