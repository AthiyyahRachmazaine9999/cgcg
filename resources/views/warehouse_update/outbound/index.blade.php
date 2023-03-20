@extends('layouts.head')
@section('content')
<div class="content">
    <div class="row">
        <div class="card" style="width:100%; margin-left:8px">
            <div class="card-body" id="filter">
                <div class="row col-lg-12">
                    <div class="col-lg-4">
                        <div class="form-group Filter">
                            {!! Form::label('id_customer', 'Customer *', ['class' => 'col-form-label']) !!}
                            <select class="form-control" name="id_customer" id="id_customer">
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group Filter">
                            {!! Form::label('start_date', 'Start Date', ['class' => 'col-form-label date']) !!}
                            <input type="date" id="start_date" class="form-control date" name="start_date" placeholder="Enter Date">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group Filter">
                            {!! Form::label('end_date', 'End Date', ['class' => 'col-form-label date']) !!}
                            <input type="date" id="end_date" class="form-control date" name="end_date" placeholder="Enter Date">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <button type="button" name="filter_outbound" id="filter_outbound" class="btn btn-info">Filter</button>
                            <button type="button" name="reset" id="rst_fil" class="btn btn-default">Reset</button>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group Filter">
                                    {!! Form::select('export', array('all' => 'All', 'normal' => 'Normal',
                                    'product' => 'Partial Only'), '',['id' => 'type_export', 'data-column' =>'1',
                                    'name' => 'status', 'class' => 'form-control
                                    form-control-select2 load','placeholder' => '*','require']) !!}
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <button type="button" name="ex_outbound" id="ex_outbound" class="btn btn-primary">Export</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="alert alert-info alert-styled-left alert-dismissible">
                    <span class="font-weight-semibold">Info</span> Filter tanggal hanya akan menghasilkan satu tanggal utama untuk pencetakan DO yang parsial, *) Fitur export masih dalam pengecekan lebih lanjut.
                </div>
            </div>
        </div>
    </div>
    <div class="card">

        <div class="card-body">
            @if(session()->has('success'))
            <div class="alert alert-success alert-styled-left alert-dismissible">
                <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                {{ session()->get('success') }}
            </div>
            @endif


            <table class="table table-bordered table-striped table-hover m_datatables_outbound">
                <thead>
                    <tr class="bg-slate-800">
                        <th class="text-center">ID</th>
                        <th class="text-center">No. WO</th>
                        <th class="text-center">No. SO</th>
                        <th class="text-center">Customer</th>
                        <th class="text-center">Jumlah Barang</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Tanggal DO</th>
                        <th class="text-center">Tanggal Resi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/warehouses/warehouse-list.js?v=').rand() }}" type="text/javascript"></script>
@endsection