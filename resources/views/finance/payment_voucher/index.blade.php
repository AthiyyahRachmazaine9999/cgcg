@extends('layouts.head')
@section('content')
<div class="content">
    <div class="row">
        <div class="card" style="width:98%; margin-left:12px">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group Filter">
                            {!! Form::label('type', 'Select Tab for Filter*', ['class' => 'col-form-label']) !!}
                            {!! Form::select('type', array('Normal' => 'Semua',
                            'biaya_lain' => 'Biaya Lain-Lain'), null,['id' => 'typess', 'name' => 'type',
                            'class' => 'form-control form-control-select2','placeholder' => '*']) !!}
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group Filter">
                            {!! Form::label('status', 'Filter Status', ['class' => 'col-form-label']) !!}
                            {!! Form::select('quo_type', array('Pending' => 'Pending', 'Approved' => 'Approved', 'Completed' => 'Approval Completed', 'Done Payment'=>'Done Payment'), null,['id'
                            => 'statusss', 'name' => 'status', 'class' => 'form-control
                            form-control-select2','placeholder' => 'Pilih Status']) !!}
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group Filter">
                            {!! Form::label('id_customer', 'Customer *', ['class' => 'col-form-label']) !!}
                            <select class="form-control" name="id_customer" id="id_customer">
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            {!! Form::label('vendor', 'Vendor *', ['class' => 'col-form-label']) !!}
                            <select class="form-control" id="vendor">
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group Filter">
                            {!! Form::label('date', 'Start Date', ['class' => 'col-form-label load']) !!}
                            <input type="date" id="start_date" class="form-control load" data-column="5" name="date"
                                placeholder="Enter Date">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group Filter">
                            {!! Form::label('date', 'End Date', ['class' => 'col-form-label load']) !!}
                            <input type="date" id="end_date" class="form-control load" data-column="5" name="date"
                                placeholder="Enter Date">
                        </div>
                    </div>
                </div>
                <div class="row pt-3">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <button type="button" name="filter" id="filter" class="btn btn-primary">Filter</button>
                            <button type="button" name="exports" id="exports" class="btn btn-info">Export</button>
                            <button type="button" name="reset" id="reset" class="btn btn-light">Reset</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="overflow-x:auto;">
            <div class="tab-pane fade show active" id="payment_voucher">
                <div class="card-header header-elements-inline">
                    <a href="{{ url('finance/payment_voucher/create') }}">
                        <button type="button" class="btn bg-primary" data-toggle="modal">
                            New Payment
                        </button>
                    </a>
                    <div class="header-elements">
                        <button class="btn btn-danger btn-sm ml-3" id="PrintPayment" data-id_pay=""><i
                                class="icon-printer mr-2 text-left"></i>Print</button><br>

                    </div>
                </div>
                <div class="card-body">
                    @if(session()->has('success'))
                    <div class="alert alert-success alert-styled-left alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                        {{ session()->get('success') }}
                    </div>
                    @elseif (session()->has('error'))
                    <div class="alert alert-danger alert-styled-left alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                        {{ session()->get('error') }}
                    </div>
                    @endif

                    <table class="table payment table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center bg-slate-800">
                                <th>Checked</th>
                                <th>Tujuan</th>
                                <th>Invoice / Kwitansi</th>
                                <th class="text-center">Nominal</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                    </table>
                    {{ method_field('DELETE') }}
                    @csrf
                </div>
            </div>
    </div>
</div>
@include('sales.quotation.attribute.modal')
@endsection
@section('script')
<script src="{{ asset('ctrl/finance/pay_voucher-list.js?v=').rand() }}" type="text/javascript"></script>
@endsection