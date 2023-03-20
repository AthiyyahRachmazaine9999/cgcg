@extends('layouts.head')
@section('content')
<div class="content">

    <div class="card">
        <div class="card-body">
            <div class="col-lg-12">
                <div class="row col-lg-12">
                    <div class="col-lg-4">
                        <div class="form-group Filter">
                            {!! Form::label('id_vendor', 'Filter Vendor', ['class' => 'col-form-label load']) !!}
                            <select class="form-control" name="vendor" id="vendor">
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group Filter">
                            {!! Form::label('date', 'Start Date', ['class' => 'col-form-label date']) !!}
                            <input type="text" id="start_date" class="form-control date" name="date"
                                placeholder="Enter Date">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group Filter">
                            {!! Form::label('date', 'End Date', ['class' => 'col-form-label date']) !!}
                            <input type="text" id="end_date" class="form-control date" name="date"
                                placeholder="Enter Date">
                        </div>
                    </div>
                    <div class="col-lg-4" style="padding-top:20px;">
                        <div class="form-group">
                            <button type="button" name="ex_quo" id="ex_quo" class="btn btn-primary">Export</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header header-elements-inline">
            <a href="{{ url('sales/vendor/create') }}">
                <button type="button" class="btn bg-primary" data-toggle="modal">
                    Vendor Baru
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
                    <tr>
                        <th>Perusahaan / Dinas</th>
                        <th>Email Perusahaan</th>
                        <th>Alamat</th>
                        <th>Jumlah PO</th>
                        <th>Created At</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script src="{{ asset('ctrl/sales/vendor-list.js?v=').rand() }}" type="text/javascript"></script>
@endsection