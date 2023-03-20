@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card" id="">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">Salary Details</h5>
        </div>
        <div class="card-body">
            <div class="form-group row col-md-12">
                <table class="table table-sm table-borderless mb-6">
                    <tr>
                        <td class="pl-8 w-60" scope="row"><strong>Employee Name</strong></td>
                        <td>:</td>
                        <td>{{$data->emp_name}} - {{$data->emp_nip}}</td>
                    </tr>
                    <tr>
                        <td class="pl-8 w-60" scope="row"><strong>Division</strong></td>
                        <td>:</td>
                        <td>{{$data->division_name}}</td>
                    </tr>
                    <tr>
                        <td class="pl-8 w-60" scope="row"><strong>Tanggal Bergabung</strong></td>
                        <td>:</td>
                        <td>{{$data->tgl_bergabung}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="card" id="">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">Download slip gaji</h5>
        </div>
        <div class="card-body">
            {!! Form::open(['method' => $method,'action'=>$action]) !!}
            <div class="form-group row">
                <label class='col-lg-3 col-form-label'>Periode</label>
                <div class="col-lg-8">
                    <input type="date" id="date" data-id_emp="{{$data->idku}}" onchange="Checkdetailgaji(this)" class="form-control" data-column="5"  placeholder="Enter Date" min="{{$data->tgl_bergabung}}" max="{!!\Carbon\Carbon::parse(\Carbon\Carbon::now())->endOfMonth()->toDateString()!!}">
                </div>
            </div>
            <div class="text-right">
                <button type="button" id="allowdownload" data-id_emp="{{$data->idku}}" onclick="CetakSlipGaji(this)" class="btn btn-primary">Generate<i class="far fa-save ml-2"></i></button>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/hr/payroll-list.js?v=').rand() }}" type="text/javascript"></script>
@endsection