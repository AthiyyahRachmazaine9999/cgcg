@extends('layouts.head')
@section('content')
<div class="content">

    <div class="card" style="overflow-x:auto;">
        <div class="card-header header-elements-inline">
            <div class="card-body">
                <div class="card-header header-elements-inline">
                    <div class="row col-lg-12">
                        <div class="col-lg-4">
                            <div class="form-group Filter">
                                {!! Form::label('status', 'Filter status', ['class' => 'col-form-label load']) !!}
                                {!! Form::select('status', array('Open Plan' => 'Open Plan', 'Introduction' =>
                                'Introduction'), null,['id' => 'status','data-column' =>'4',
                                'class' => 'form-control form-control-select2 load','placeholder' => '*','require'])
                                !!}
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group Filter">
                                @if(in_array($user->id,explode(',',getConfig('list_manage'))))
                                {!! Form::label('sales', 'Filter Sales', ['class' => 'col-form-label load']) !!}
                                {!! Form::select('sales', $sales, null,['id' => 'sales','data-column' =>'5',
                                'class' => 'form-control form-control-select2 load','placeholder' => '*','require'])
                                !!}
                                @else
                                <input type="text" id="" class="form-control" data-column="5" name="sales" readonlyp>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group Filter">
                                {!! Form::label('id_customer', 'Customer *', ['class' => 'col-form-label']) !!}
                                <select class="form-control" name="id_customer" data-column="7" id="customer">
                                </select>
                            </div>
                        </div>
                        <div class="row col-lg-12">
                            <div class="col-lg-6">
                                <div class="form-group Filter">
                                    {!! Form::label('date', 'Start Date', ['class' => 'col-form-label load']) !!}
                                    <input type="date" id="start_date" class="form-control load" data-column="6"
                                        name="date" placeholder="Enter Date">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group Filter">
                                    {!! Form::label('date', 'End Date', ['class' => 'col-form-label load']) !!}
                                    <input type="date" id="end_date" class="form-control load" data-column="6"
                                        name="date" placeholder="Enter Date">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="">
                    <div class="form-group">
                        <button type="button" name="filter" id="filter" class="btn btn-info">Filter</button>
                        <button type="button" name="export" id="ex_visit" class="btn btn-primary">Export</button>
                        <button type="button" name="reset" id="reset" class="btn btn-default">Reset</button>
                        <div class="text-right">
                            <a href="{{ route('visitplan.index');}}" class="btn btn-warning btn-sm ml-3"><i
                                    class="fa fa-backward"></i>
                                Back</a>
                        </div>
                    </div>
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


            <table class="table table-bordered table-striped table-hover m_datatable">
                <thead>
                    <tr class="bg-slate-800">
                        <th class="text-center">ID </th>
                        <th class="text-center">Aktivitas</th>
                        <th class="text-center">Tanggal & Meeting Point</th>
                        <th class="text-center">Customer </th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Created At </th>
                        <th class="text-center">Created By </th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/sales/visitplan_list.js?v=').rand() }}" type="text/javascript"></script>
@endsection