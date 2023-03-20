{!! Form::open(['method' => $method,'action'=>$action]) !!}
<div class="table-responsive">
    <table class="table table-striped table-hover other_datatable" id="ptable">
        <thead class="thead-colored bg-teal">
            <tr class="text-center">
                <th rowspan="2">Barang</th>
                <th rowspan="2">Status Stock</th>
                <th rowspan="2">Qty</th>
                <th colspan="3">Harga Per Item</th>
                <th rowspan="2">Action</th>
            </tr>
            <tr class="text-center">
                <th>Live</th>
                <th>Order</th>
                <th>Modal</th>
            </tr>
        </thead>
        <tbody>
            @php
            $subtotal_modal = $subtotal_ongkir = $subtotal_order = 0;
            $margin = 0;
            foreach ($product as $val){
            $cname = $val->id_product == "new" ? "<b class='text-danger'>[NEW SKU]</b> ".getProductReq($val->id_product_request)->req_product : getProductDetail($val->id_product)->name;
            $cprice = $val->id_product == "new" ? getProductReq($val->id_product_request)->req_price : getProductDetail($val->id_product)->price;
            $oprice = $val->id_product == "new" ? $val->det_quo_harga_req : $val->det_quo_harga_order;
            $vendor = $val->id_vendor == "" || $val->id_vendor == null ? '': getVendor($val->id_vendor)->vendor_name;
            @endphp
            <tr>
                {!! Form::hidden('id_quo',$val->id_quo,['id'=>'id_quo','class'=>'form-control']) !!}
                {!! Form::hidden('idpro[]',$val->id,['id'=>'idpro','class'=>'form-control']) !!}

                <td onclick="ShowProduct({{$val->id}})">{!!$cname!!}</td>
                <td class="text-center">
                    @php
                    if($val->id_vendor==null) {
                    @endphp
                    {!! Form::select('vendor[]',$stock,null , ['id'=>'vendor','class' => 'form-control form-control-select2 vendor','placeholder' => '*']) !!}
                    @php
                    }else{
                    @endphp
                    <select class="form-control form-control-select2 vendor" name="vendor[]" id="vendor">
                        <option value="{{$val->id_vendor}}">{!!getVendor($val->id_vendor)->vendor_name!!}</option>
                    </select>
                    @php
                    }
                    @endphp

                    {!! Form::select('stock[]',$stock,$val->det_quo_status_vendor , ['id'=>'stock','class' => 'form-control form-control-select2 stock','placeholder' => '*']) !!}
                    {!! Form::text('note[]',$val->det_quo_note,['id'=>'note','class'=>'form-control','placeholder'=>'Keterangan tambahan']) !!}

                </td>
                <td class="text-center">@php echo $val->det_quo_qty; @endphp</td>
                <td class="text-right">{{number_format($cprice)}}</td>

                </td>
                <td class="text-right">
                    @php echo number_format($oprice); @endphp
                </td>
                <td class="text-right font-weight-bold">
                    {!! Form::number('p_price[]',$val->det_quo_harga_modal,['id'=>'p_price','class'=>'form-control','placeholder'=>'Harga Modal']) !!}
                </td>
                <td>
                    <div class="list-icons">
                        <div class="dropdown">
                            <a href="#" class="list-icons-item" data-toggle="dropdown" aria-expanded="true">
                                <i class="icon-menu9"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(-158px, 19px, 0px); top: 0px; left: 0px; will-change: transform;">
                                <a data-id="{{$val->id_product}}" data-idpro="{{$val->id}}" data-id_quo="{{$val->id_quo}}" onclick="ChangeProduct(this)" class="dropdown-item"><i class="icon-pencil5"></i> Ganti Product</a>
                                <a data-id="{{$val->id_product}}" data-idpro="{{$val->id}}" data-id_quo="{{$val->id_quo}}" onclick="DeleteProduct(this)" class="dropdown-item"> <i class="icon-trash"></i> Delete</a>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="7" id="change_equ_{{$val->id}}"></td>
            </tr>


            @php $id_quo = $val->id_quo; } @endphp
        </tbody>
    </table>
</div>
<div class="modal-footer pt-3">
    <button type="button" class="btn bg-danger" data-method='sales/quotation/{!!$id_quo!!}' onclick='cancel(this)'>Cancel</button>
    <button type="save" class="btn bg-primary legitRipple">Simpan</button>
</div>

{!! Form::close() !!}