@extends('layouts.head')
@section('content')
<div class="content mb-3">
    <div class="row">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <h3 class="mb-0 font-weight-semibold">Laporan Gaji Bulanan</h3>
                            <p id="month">Periode ......</p> 
                        </div>
                    </div>

                </div>
                <table class="table table-striped table-hover">
                    <tbody>
                        <tr>
                            <td>Gross Salary</td>
                            <td class="text-right" id="gross">{{number_format(0)}}</td>
                        </tr>
                        <tr>
                            <td>Deduction</td>
                            <td class="text-right" id="deduction">{{number_format(0)}}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Net Salary</td>
                            <td class="text-right text-danger font-weight-bold" id="net">{{number_format(0)}}</td>
                        </tr>

                        <!-- end total -->
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card">
                @if(session()->has('error'))
                <div class="alert alert-danger alert-styled-left alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                    {{ session()->get('error') }}
                </div>
                @endif
                {!! Form::open(['action' => $action, 'method' => $method, 'id' => 'm_form']) !!}
                <div class="card-body" id="filter">
                    <div class="row col-lg-12 mb-3">
                        {!! Form::label('start_date', 'Select Year', ['class' => 'col-form-label date']) !!}
                        <input type="date" id="time" class="form-control date" name="time" placeholder="Enter Date">
                    </div>
                    <div class="row col-lg-12">
                        <div class="form-group">
                            <button type="button" onclick="ViewReport(this)" class="btn btn-primary"><i class="fas fa-search"></i> View</button>
                            <button type="submit" class="btn btn-success"><i class="far fa-file-excel"></i> Download</button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/hr/payroll-list.js?v=').rand() }}" type="text/javascript"></script>
@endsection