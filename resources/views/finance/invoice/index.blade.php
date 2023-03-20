@extends('layouts.head')
@section('content')
<div class="content">
    <div class="row">
        <div class="card" style="width:98%; margin-left:8px">
            <div class="card-body" id="filter">
                <div class="card-header header-elements-inline">
                    <div class="row col-lg-12">
                        <div class="col-lg-4">
                            <div class="form-group Filter">
                                {!! Form::label('filter_so', 'Filter SO', ['class' => 'col-form-label load']) !!}
                                {!! Form::select('id_quo', $quo_id, null,['id' => 'ids_quo', 'data-column' =>'1',
                                'name' => 'id_quo', 'class' => 'form-control form-control-select2 quos','placeholder' =>
                                '*']) !!}
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group Filter">
                                {!! Form::label('date', 'Start Date', ['class' => 'col-form-label load']) !!}
                                <input type="text" id="start_date" class="form-control st_date" data-column="5"
                                    name="st_date" placeholder="Enter Start Date">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group Filter">
                                {!! Form::label('date', 'End Date', ['class' => 'col-form-label load']) !!}
                                <input type="text" id="end_date" class="form-control st_date" data-column="5"
                                    name="e_date" placeholder="Enter End Date">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group Filter">
                                {!! Form::label('ket_lunas', 'Keterangan Lunas', ['class' => 'col-form-label
                                ket_lunass']) !!}
                                {!! Form::select('ket_lunas', array('yes' => 'Lunas', 'no'=>'Belum Lunas'), '', ['id' =>
                                'filter_lunas', 'class' => 'form-control form-control-select2', 'placeholder' =>
                                'Pilih Keterangan']) !!}
                            </div>
                        </div>
                        <!-- <div class="col-lg-4">
                            <div class="form-group Filter">
                                <input type="checkbox" name="All" id="All" value=""> Export ALL
                            </div>
                        </div> -->
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <button type="button" name="filter" id="inv_filter" class="btn btn-info">Filter</button>
                        <button type="button" name="inv_ex" id="inv_ex" class="btn btn-primary">Export</button>
                        <button type="button" name="reset" id="inv_res_filter" class="btn btn-default">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card" style="overflow-x:auto;">
        <div class="card-body">
            @if(session()->has('success'))
            <div class="alert alert-success alert-styled-left alert-dismissible">
                <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                {{ session()->get('success') }}
            </div>
            @endif

            <table class="table m_datatable table-bordered table-striped table-hover">
                <thead>
                    <tr class="text-center">
                        <th>ID</th>
                        <th>No. SO</th>
                        <th>No. Invoice</th>
                        <th>Harga Invoice</th>
                        <th>Pembayaran</th>
                        <th>Sisa</th>
                        <th>Tanggal Invoice</th>
                        <th>Created By</th>
                        <th>Note</th>
                    </tr>
                </thead>
            </table>
            {{ method_field('DELETE') }}
            @csrf
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/finance/finance-list.js?v=').rand() }}" type="text/javascript"></script>
@endsection