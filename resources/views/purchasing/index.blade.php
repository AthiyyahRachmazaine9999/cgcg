@extends('layouts.head')
@section('content')
<div class="content">
    <div class="row">
        <div class="card" style="width:100%; margin-left:8px">
            <div class="card-body" id="filter">
                <div class="row col-lg-12">
                    <div class="col-lg-4">
                        <div class="form-group Filter">
                            {!! Form::label('id_vendor', 'Filter Vendor', ['class' => 'col-form-label load']) !!}
                            <select class="form-control" name="vendor" id="vendor">
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            {!! Form::label('id_product', 'Product *', ['class' => 'col-form-label']) !!}
                            <select class="form-control" id="id_product">
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group Filter">
                            {!! Form::label('status', 'Filter Status', ['class' => 'col-form-label load']) !!}
                            {!! Form::select('status', array('approve' => 'Approve', 'reject' => 'Reject',
                            'draft' => 'Draft'), '',['id' => 'status', 'data-column' =>'1',
                            'name' => 'status', 'class' => 'form-control
                            form-control-select2 load','placeholder' => '*','require']) !!}
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group Filter">
                            {!! Form::label('date', 'Start Date', ['class' => 'col-form-label date']) !!}
                            <input type="text" id="start_date" class="form-control date" name="date" placeholder="Enter Date">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group Filter">
                            {!! Form::label('date', 'End Date', ['class' => 'col-form-label date']) !!}
                            <input type="text" id="end_date" class="form-control date" name="date" placeholder="Enter Date">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <button type="button" name="filter" id="filter" class="btn btn-info">Filter</button>
                            <button type="button" name="reset" id="rst_fil" class="btn btn-default">Reset</button>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group Filter">
                                    {!! Form::select('export', array('normal' => 'Normal',
                                    'product' => 'With Product'), '',['id' => 'export', 'data-column' =>'1',
                                    'name' => 'status', 'class' => 'form-control
                                    form-control-select2 load','placeholder' => '*','require']) !!}
                                </div>
                            </div>
                            <div class="col-lg-6">
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
            <a href="{{ url('purchasing/order/create') }}">
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
                        <th class="text-center">No. PO</th>
                        <th class="text-center">No. SO</th>
                        <th class="text-center">Vendor</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">Tanggal Order</th>
                        <th class="text-center">Payment Status</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/purchasing/po-list.js?v=').rand() }}" type="text/javascript"></script>
@endsection