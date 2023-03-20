@extends('layouts.head')
@section('content')
<div class="content">

    <div class="row">
        <div class="card" style="width:100%; margin-left:8px">
        <div class="card-body" id="filter">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group Filter">
                            {!! Form::label('quo_type', 'Filter Type', ['class' => 'col-form-label load']) !!}
                            {!! Form::select('quo_type', $quo_type, null,['id' => 'quo_type', 'data-column' =>'1',
                            'name' => 'quo_type', 'class' => 'form-control
                            form-control-select2 load','placeholder' => '*','require']) !!}
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group Filter">
                            {!! Form::label('status', 'Filter status', ['class' => 'col-form-label load']) !!}
                            {!! Form::select('status', $status, null,['id' => 'status','data-column' =>'6',
                            'class' => 'form-control form-control-select2 load','placeholder' => '*','require'])
                            !!}
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group Filter">
                            {!! Form::label('sales', 'Filter Sales', ['class' => 'col-form-label load']) !!}
                            {!! Form::select('sales', $sales, null,['id' => 'sales','data-column' =>'4',
                            'class' => 'form-control form-control-select2 load','placeholder' => '*','require'])
                            !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group Filter">
                            {!! Form::label('id_customer', 'Customer *', ['class' => 'col-form-label']) !!}
                            <select class="form-control" name="id_customer" id="id_customer">
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            {!! Form::label('id_product', 'Product *', ['class' => 'col-form-label']) !!}
                            <select class="form-control" id="id_product">
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group Filter">
                            {!! Form::label('date', 'Start Date', ['class' => 'col-form-label load']) !!}
                            <input type="date" id="start_date" class="form-control load" data-column="5" name="date" placeholder="Enter Date">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group Filter">
                            {!! Form::label('date', 'End Date', ['class' => 'col-form-label load']) !!}
                            <input type="date" id="end_date" class="form-control load" data-column="5" name="date" placeholder="Enter Date">
                        </div>
                    </div>
                </div>
                <div class="row pt-3">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <button type="button" name="filter" id="filter" class="btn btn-info">Filter</button>
                            <button type="button" name="reset" id="reset" class="btn btn-danger">Reset</button>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group Filter">
                                    <input type="checkbox" name="All" id="All" value="" class="form-check-input-styled"> Export ALL
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <button type="button" name="ex_quo" id="ex_quo" class="btn btn-primary">Export</button>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header header-elements-inline">
            <a href="{{ url('sales/quotation/create') }}">
                <button type="button" class="btn bg-primary" data-toggle="modal">
                    Order Baru
                </button>
            </a>
        </div>

        <div class="card-body">
            @if(session()->has('success'))
            <div class="alert alert-success alert-styled-left alert-dismissible">
                <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                {{ session()->get('success') }}
            </div>
            @endif


            <table class="table table-bordered table-striped table-hover m_datatable">
                <thead>
                    <tr class="bg-slate-800">
                        <th class="text-center">ID </th>
                        <th class="text-center">Nomer </th>
                        <th class="text-center">Nama Paket</th>
                        <th class="text-center">Customer</th>
                        <th class="text-center">Sales</th>
                        <th class="text-center">Tanggal Order</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Posisi</th>
                        <th class="text-center">Total</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/sales/quotation-list.js?v=').rand() }}" type="text/javascript"></script>
@endsection