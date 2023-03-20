@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->

    {!! Form::open(['action' => $action, 'method' => $method, 'id' => 'm_form']) !!}
    {!! Form::hidden('isedit','yes',['id'=>'isedit','class'=>'form-control']) !!}
    <div class="card">
        <div class="card-header alpha-primary text-success-800 border-bottom-success header-elements-inline">
            <h5 class="card-title">Edit Quotation</h5>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        {!! Form::label('id_customer', 'Customer *', ['class' => 'col-form-label']) !!}
                        <!-- <select class="form-control" name="id_customer" id="">
                        </select> -->
                        {!! Form::select('id_customer', $cust, $main->id_customer,['id' => 'id_customer', 'class' => 'form-control form-control-select2','placeholder' => '*','require']) !!}
                    </div>

                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        {!! Form::label('quo_type', 'Type *', ['class' => 'col-form-label']) !!}
                        {!! Form::select('quo_type', $quo_type, $is_type,['id' => 'quo_type', 'class' => 'form-control form-control-select2','placeholder' => '*','require']) !!}
                    </div>
                </div>
            </div>

            <div class="row" id="row_other">
                <div class="col-lg-6">
                    <div class="form-group">
                        {!! Form::label('quo_no', 'No Pengadaan / Paket', ['class' => 'col-form-label']) !!}
                        {!! Form::text('quo_no',$main->quo_no,['id'=>'quo_no','class'=>'form-control','placeholder'=>'Masukan Nomer Pengadaan / Paket Yang tertera , misal : PEP-***','require']) !!}
                    </div>

                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        {!! Form::label('quo_order_at', 'Tanggal Order / Pesanan', ['class' => 'col-form-label']) !!}
                        {!! Form::text('quo_order_at',$main->quo_order_at,['id'=>'quo_order_at','class'=>'form-control','placeholder'=>'dd/mm/yyyy','require']) !!}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        {!! Form::label('id_admin', 'Admin', ['class' => 'col-form-label']) !!}
                        {!! Form::select('id_admin', $id_admin, $is_admin,['id' => 'id_admin', 'class' => 'form-control form-control-select2','placeholder' => '*','require']) !!}
                    </div>

                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        {!! Form::label('id_sales', 'Sales', ['class' => 'col-form-label']) !!}
                        {!! Form::select('id_sales', $id_sales, $is_sales,['id' => 'id_sales', 'class' => 'form-control form-control-select2','placeholder' => '*','require']) !!}
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-lg-12">
                    {!! Form::label('quo_name', 'Nama Pengadaan / Paket', ['class' => 'col-form-label']) !!}
                    {!! Form::text('quo_name',$main->quo_name,['id'=>'quo_name','class'=>'form-control','placeholder'=>'Masukan Nama Pengadaan / Paket','require']) !!}

                </div>
            </div>
            <div class="row" id="row_nominal">
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! Form::label('quo_price', 'Nominal Total Pengadaan / Paket', ['class' => 'col-form-label']) !!}
                        {!! Form::number('quo_price',$main->quo_price,['id'=>'quo_price','class'=>'form-control','placeholder'=>'Total Keseluruhan Paket / Project, gunakan titik jika ada koma pada harga','require']) !!}
                    </div>

                </div>
            </div>
        </div>
        <div class="modal-footer">
            {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel', 'data-method' => 'sales/quotation', 'type' => 'button','onclick'=>'cancel(this)']) !!}
            <button type="submit" id="save_btn" class="btn btn-primary">Update Data<i class="far fa-save ml-2"></i></button>

        </div>
    </div>

    {!! Form::close() !!}
    <!-- /basic layout -->
    <div class="modal fade" id="m_modal" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modaltitle"><span class="flaticon-share"></span>&nbsp;&nbsp; Icons</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modalbody">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/sales/quotation-form.js?v=').rand() }}" type="text/javascript"></script>
@endsection