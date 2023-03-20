{!! Form::open(['method' => $method,'action'=>$action]) !!}
<!-- other price section  -->
<legend class="text-uppercase font-size-sm font-weight-bold text-danger  ml-3 mr-3">Biaya Lain - Lain</legend>
<div class="row  ml-3 mr-3">
    <div class="col-lg-6">
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label>NEGO ONGKIR</label>
                    {!! Form::number('ongkir_customer',$price->ongkir_customer,['id'=>'ongkir_customer','class'=>'form-control','placeholder'=>'Nego Ongkir']) !!}
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group">
                    <label>LAIN LAIN</label>
                    {!! Form::number('price_other',$price->price_other,['id'=>'price_other','class'=>'form-control','placeholder'=>'Lain Lain']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="row">
            <div class="col-lg-4">
                <div class="form-group">
                    <label>IF TYPE</label>
                    {!! Form::select('price_if_type',$if_type,$price->price_if_type , ['id'=>'iftype','class' => 'form-control form-control-select2']) !!}

                </div>
            </div>
            <div class="col-lg-8" id="normalif">
                <div class="form-group">
                    <label>IF</label>
                    {!! Form::number('price_if_normal',$price->price_if,['id'=>'price_if_normal','class'=>'form-control','placeholder'=>'IF']) !!}
                </div>
            </div>
            <div class="col-lg-8" id="persenif">
                <div class="form-group">
                    <label>% (HANYA ANGKA)</label>
                    {!! Form::number('price_if_percen',$price->price_if,['id'=>'price_if_percen','class'=>'form-control','placeholder'=>'IF']) !!}
                </div>
            </div>
        </div>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped table-hover other_datatable" id="ptable">
        <thead class="thead-colored bg-teal">
            <tr class="text-center">
                <th>Barang</th>
                <th>Qty</th>
                <th>Harga Per Item</th>
                <th>Ongkir</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @php
            $subtotal_modal = $subtotal_ongkir = $subtotal_order = 0;
            $margin = 0;
            foreach ($product as $val){

            $cname = $val->id_product == "new" ? "<b class='text-danger'>[NEW REQ]</b> ".getProductReq($val->id_product_request)->req_product : getProductDetail($val->id_product)->name;
            $berat = $val->id_product == "new" ? "<b class='text-danger'>-</b>" : number_format(getProductDetail($val->id_product)->weight,2);
            $oprice = $val->id_product == "new" ? $val->det_quo_harga_req : $val->det_quo_harga_order;
            @endphp
            <tr>
                {!! Form::hidden('id_quo',$val->id_quo,['id'=>'id_quo','class'=>'form-control']) !!}
                {!! Form::hidden('idpro[]',$val->id,['id'=>'idpro','class'=>'form-control']) !!}
                {!! Form::hidden('skupro[]',$val->id_product,['id'=>'skupro','class'=>'form-control']) !!}
                <td>{!!$cname!!}</td>
                <td>
                    {!! Form::number('p_qty[]',$val->det_quo_qty,['id'=>'p_qty','class'=>'form-control','placeholder'=>'Harga Modal']) !!}
                </td>
                <td>
                    {!! Form::number('p_price[]',$oprice,['id'=>'p_price','class'=>'form-control','placeholder'=>'Harga Modal','step'=>'any']) !!}
                </td>
                <td>
                    {!! Form::number('p_ongkir[]',$val->det_quo_harga_ongkir,['id'=>'p_ongkir','class'=>'form-control','placeholder'=>'Harga Ongkir']) !!}
                </td>
                <td>
                    <div class="row">
                        <div class="col-lg-6">
                            <button type="button" data-id="{{$val->id_product}}" data-idpro="{{$val->id}}" data-id_quo="{{$val->id_quo}}" onclick="ChangeProduct(this)" class="btn btn-success btn-labeled btn-labeled-left btn-sm legitRipple"><b>
                                    <i class="icon-pencil5"></i></b> Change
                            </button>
                        </div>
                        <div class="col-lg-6">
                            <button type="button" data-id="{{$val->id_product}}" data-idpro="{{$val->id}}" data-id_quo="{{$val->id_quo}}" onclick="DeleteProduct(this)" class="btn btn-danger btn-labeled btn-labeled-left btn-sm legitRipple"><b>
                                    <i class="icon-trash"></i></b> Delete
                            </button>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="5" id="change_equ_{{$val->id}}"></td>
            </tr>
            @php 
            $id_quo = $val->id_quo; } @endphp
        </tbody>
    </table>
</div>


<div id="separate_equ"></div>
<button type="button" data-id_quo="{{$val->id_quo}}" onClick="clone_product(this)" class="ml-3 btn btn-danger rounded-round legitRipple pull-right mb-3">
    <i class="icon-plus-circle2 mr-2"></i>Tambah
</button>
<div class="modal-footer pt-3">
    <button type="button" class="btn bg-danger" data-method='sales/quotation/{!!$id_quo!!}' onclick='cancel(this)'>Cancel</button>
    <button type="save" class="btn bg-primary legitRipple">Simpan</button>
</div>

{!! Form::close() !!}