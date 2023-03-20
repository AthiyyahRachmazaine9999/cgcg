@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->

    {!! Form::open(['action' => $action, 'method' => $method, 'id' => 'm_form']) !!}
    <div class="card">
        <div class="card-header alpha-primary text-success-800 border-bottom-success header-elements-inline">
            <h5 class="card-title">Create Order</h5>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! Form::label('id_customer', 'Cabang *', ['class' => 'col-form-label']) !!}
                        <select class="form-control" name="id_customer" id="id_customer">
                        </select>
                    </div>

                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! Form::label('id_vendor', 'Nama Vendor', ['class' => 'col-form-label']) !!}
                        <select class="form-control" name="id_vendor" id="id_vendor">
                        </select>
                    </div>
                </div>
            </div>

            <div class="row" id="row_other">
                <div class="col-lg-12">
                    <div class="form-group">
                    {!! Form::label('po_name', 'Nama Pengadaan / Paket', ['class' => 'col-form-label']) !!}
                    {!! Form::text('po_name','',['id'=>'po_name','class'=>'form-control','placeholder'=>'Masukan Nama Pengadaan / Paket','require']) !!}
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header alpha-primary text-success-800 border-bottom-success header-elements-inline">
            <h5 class="card-title">Product</h5>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                </div>
            </div>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! Form::label('id_product', 'Product *', ['class' => 'col-form-label']) !!}
                        <select class="form-control" id="id_product">
                        </select>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group">
                        {!! Form::label('p_price', 'Harga Satuan', ['class' => 'col-form-label']) !!}
                        {!! Form::number('p_price','',['id'=>'p_price','class'=>'form-control','placeholder'=>'Harga Order','onchange'=>"HitungSub();",'onkeyup'=>"HitungSub();"]) !!}

                    </div>

                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        {!! Form::label('p_qty', 'Qty', ['class' => 'col-form-label']) !!}
                        {!! Form::number('p_qty','',['id'=>'p_qty','class'=>'form-control','placeholder'=>'Qty','onchange'=>"HitungSub();",'onkeyup'=>"HitungSub();"]) !!}

                    </div>

                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        {!! Form::label('p_sub', 'Subtotal', ['class' => 'col-form-label']) !!}
                        {!! Form::number('p_sub','',['id'=>'p_sub','class'=>'form-control','placeholder'=>'Subtotal','readonly']) !!}

                    </div>

                </div>
            </div>
            <div class="row text-right mb-3">
                <div class="col-md-12 tx-right mg-t-20-force mg-b-20-force">
                    <button type="button" class="btn bg-teal-400 btn-labeled btn-labeled-left rounded-round legitRipple" id="add-row"><b><i class="fas fa-plus"></i></b>Tambah</button>
                    <button type="button" class="btn bg-danger-400 btn-labeled btn-labeled-left rounded-round legitRipple" id="delete-row"><b><i class="fas fa-trash-alt"></i></b>Delete</button>
                </div>
            </div>
            <table class="table table-bordered table-striped table-hover">
                <thead class="bg-slate-800">
                    <tr>
                        <th class="text-center" rowspan="2"># </th>
                        <th class="text-center" rowspan="2">Nama Product</th>
                        <th class="text-center" rowspan="2">Harga Satuan</th>
                        <th class="text-center" rowspan="2">Qty</th>
                        <th class="text-center" rowspan="2">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel', 'data-method' => 'sales/quotation', 'type' => 'button','onclick'=>'cancel(this)']) !!}
            <button type="submit" id="save_btn" class="btn btn-primary">Save Quotation<i class="far fa-save ml-2"></i></button>

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
<script src="{{ asset('ctrl/purchasing/po-form.js') }}" type="text/javascript"></script>
@endsection

