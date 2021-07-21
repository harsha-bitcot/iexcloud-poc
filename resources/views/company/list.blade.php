@extends('layouts.layout')
@section('title', 'Companies')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                {{--                        <h6 class="m-0 font-weight-bold text-primary">List of companies <span class="close"> <a--}}
                {{--                                    href="/">Add a new company</a></span></h6>--}}
                <h6 class="m-0 font-weight-bold text-primary">List of companies <span class="close"> <form
                            action="/companies/add" method="POST">
                                    @csrf
                                    <input type="text" name="ticker" placeholder="Add Company">
                                    <button type="submit" class="btn-secondary">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </form> </span>
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Symbol</th>
                            <th style="max-width: 10vw;">Exchange</th>
                            <th style="max-width: 5vw;">Status</th>
                            <th style="max-width: 8vw;">Actions</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>Name</th>
                            <th>Symbol</th>
                            <th>Exchange</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($companies as $company)
                            <tr>
                                <td>{{$company->companyName}}</td>
                                <td>{{$company->symbol}}</td>
                                <td>{{$company->exchange}}</td>
                                <td>
                                    <?php echo $company->status ? '<span style="color: green">Active</span>' : '<span style="color: red">In-active</span>'; ?>
                                    {{--                                                <small><a href="/companies/status/toggle/{{$company->symbol}}">Toggle</a></small>--}}
                                </td>
                                <td>
                                    <small>
                                        <a href="/companies/status/toggle/{{$company->symbol}}">Toggle Status</a> |
                                        <a href="/companies/view/{{ $company->symbol }}">View Details</a>
{{--                                        View Data |--}}
{{--                                        View Graphs--}}
                                    </small>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

@endsection
