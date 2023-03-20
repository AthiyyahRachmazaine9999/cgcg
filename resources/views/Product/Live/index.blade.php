@extends('layouts.head')
@section('content')
<div class="content">
    <div class="card" style="overflow-x:auto;">
        <div class="card-header header-elements-inline">
            <div class="card-body">
                <div class="card-header header-elements-inline">
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="form-group Filter">
                                {!! Form::label('brand', 'Filter Brand', ['class' => 'col-form-label']) !!}
                                {!! Form::select('brand', $brand, null,['id' => 'brand','data-column' =>'0',
                                'class' => 'form-control form-control-select2','placeholder' => '*','require'])
                                !!}
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="form-group Filter">
                                {!! Form::label('status', 'Filter Status', ['class' => 'col-form-label']) !!}
                                {!! Form::select('status', array('1' => 'Active', '0' => 'In Active'), null,['id' =>
                                'status','data-column' =>'3',
                                'class' => 'form-control form-control-select2','placeholder' => '*','required'])
                                !!}
                            </div>
                        </div>
                        <div class="col-lg-2 pt-4">
                            <div class="form-group Filter">
                                <input type="checkbox" name="All" id="All" value=""> Export ALL
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="form-group Filter">
                                {!! Form::label('date', 'Start Date', ['class' => 'col-form-label load']) !!}
                                <input type="date" id="start_date" class="form-control load" data-column="5" name="date"
                                    placeholder="Enter Date">
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="form-group Filter">
                                {!! Form::label('date', 'End Date', ['class' => 'col-form-label load']) !!}
                                <input type="date" id="end_date" class="form-control load" data-column="5" name="date"
                                    placeholder="Enter Date">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <button type="button" name="filter" id="filter" class="btn btn-info">Filter</button>
                        <button type="button" name="export" id="export_live" class="btn btn-primary">Export</button>
                        <button type="button" name="reset" id="reset" class="btn btn-default">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header header-elements-inline">
        </div>
        <div class="card-body">
            @if(session()->has('success'))
            <div class="alert alert-success alert-styled-left alert-dismissible">
                <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                {{ session()->get('success') }}
            </div>
            @endif
            <table class="table m_datatable table-bordered table-striped table-hover">
                <thead>
                    <!-- //tambahkan kode untuk tanda + show atau button show -->
                    <tr>
                        <th>Model</th>
                        <th>No.SKU</th>
                        <th>Product Name</th>
                        <th>Product Status</th>
                        <th class="text-center">Price</th>
                        <th class="">Date Added</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
            </table>
            {{ method_field('DELETE') }}
            @csrf
        </div>
    </div>
</div>
@endsection
@section ('script')
<script src="{{ asset('ctrl/product/live-list.js') }}" type="text/javascript"></script>
@endsection
<!--  -->