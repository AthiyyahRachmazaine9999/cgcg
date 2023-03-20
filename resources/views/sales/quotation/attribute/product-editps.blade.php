{!! Form::open(['method' => $method,'action'=>$action]) !!}
<div class="table-responsive">
    <table class="table table-striped table-hover editps_datatable table-bordered">
        <thead class="thead-colored bg-teal">
            <tr class="text-center">
                <th rowspan="2">Barang</th>
                <th colspan="2">Qty</th>
                <th rowspan="2">Stock</th>
                <th colspan="3">Harga Per Item</th>
                <th rowspan="2">Action</th>
            </tr>
            <tr class="text-center">
                <th>Cust</th>
                <th>Purchase</th>
                <th>Order</th>
                <th>Modal Product</th>
                <th>Modal Purchasing</th>
            </tr>
        </thead>
        <tbody>
            @php
            $subtotal_modal = $subtotal_final = $subtotal_order = 0;
            $margin = 0;
            foreach ($purchase as $val){
            $cname = $val->id_product == "new" ? "<b class='text-danger'>[NEW SKU]</b> ".getProductReq($val->id_product_request)->req_product : getProductDetail($val->id_product)->name;
            $cprice = $val->id_product == "new" ? getProductReq($val->id_product_request)->req_price : getProductDetail($val->id_product)->price;
            $oprice = $val->id_product == "new" ? $val->det_quo_harga_req : $val->det_quo_harga_order;
            $vendor = $val->id_vendor == "" || $val->id_vendor == null ? '': getVendor($val->id_vendor)->vendor_name;
            $vendor_purc = $val->id_vendor_beli == "" || $val->id_vendor_beli == null ? '': getVendor($val->id_vendor_beli)->vendor_name;
            $getIdpo = getIdPO($val->id_quo, $val->id_vendor);
            @endphp
            <tr>
                {!! Form::hidden('id_quo',$val->id_quo,['id'=>'id_quo','class'=>'form-control']) !!}
                {!! Form::hidden('idpro[]',$val->id,['id'=>'idpro','class'=>'form-control']) !!}
                <td onclick="ShowProduct({{$val->id}})">
                    {!! Form::hidden('sku[]',$val->id_product,['id'=>'sku','class'=>'form-control'])!!}
                    {!!$cname!!}
                    <br>
                    <p class="font-weight-bold mt-3">Team Product</p>
                    <p class="font-weight-bold">{{$vendor}}</p>
                    @php echo ucfirst($val->det_quo_status_vendor);@endphp
                    <p class="text-danger font-italic">{{$val->det_quo_note}} </p>
                </td>
                <td class="text-center"> {{$val->det_quo_qty}}</td>
                <td class="text-center">
                    <input type="number" name="p_qty_beli[]" value="{{$val->det_quo_qty_beli}}" id='p_qty_{{$val->id}}' class="form-control" placeholder='Qty PO' readonly>
                </td>
                <td class="text-center">
                    @php
                    if($val->id_vendor_beli==null) {
                    @endphp
                    {!! Form::select('vendor[]',$stock,null , ['id'=>'vendor','class' => 'form-control form-control-select2 vendor','placeholder' => '*']) !!}
                    @php
                    }else{
                    $vendorisi = [$val->id_vendor_beli=>getVendor($val->id_vendor_beli)->vendor_name];   
                    @endphp
                    {!! Form::select('vendor[]',$vendorisi,$val->id_vendor_beli , ['id'=>'vendor','class' => 'form-control form-control-select2 vendor','placeholder' => '*']) !!}
                    @php
                    }
                    @endphp
                    {!! Form::select('stock[]',$stock,$val->det_quo_status_beli , ['id'=>'stock','class' => 'form-control form-control-select2 stock','placeholder' => '*']) !!}
                    {!! Form::text('note[]',$val->det_quo_note_beli,['id'=>'note','class'=>'form-control','placeholder'=>'Keterangan tambahan']) !!}
                </td>
                <td class="text-right">
                    @php echo number_format($oprice); @endphp
                </td>
                <td class="text-right">@php echo number_format($val->det_quo_harga_modal); @endphp</td>
                <td class="text-right">
                    {!! Form::select('bayar[]',$bayar,$val->det_quo_type_beli , ['id'=>'bayar','class' => 'form-control form-control-select2 stock','placeholder' => '*']) !!}
                    {!! Form::number('p_price[]',$val->det_quo_harga_final,['id'=>'p_price','class'=>'form-control','placeholder'=>'Harga Beli','step'=>'any']) !!}
                </td>
                <td>
                    <button type="button" data-id="{{$val->id_product}}" data-idpro="{{$val->id}}" data-id_quo="{{$val->id_quo}}" onclick="splitPO(this)" class="btn bg-teal-400 btn-icon rounded-round legitRipple"><i class="fas fa-clone"></i></button>
                    @php if($val->id<>$val->id_quo_pro){ @endphp
                        <button type="button" data-id="{{$val->id_product}}" data-idpro="{{$val->id}}" data-id_quo="{{$val->id_quo}}" onclick="DeleteSplit(this)" class="btn bg-pink-400 btn-icon rounded-round legitRipple"><i class="icon-trash-alt"></i></button>
                        @php } @endphp
                </td>
            </tr>
            <tr>
                <td colspan="8" id="split_equ_{{$val->id}}"></td>
            </tr>
            @php
            $subtotal_order += $oprice*$val->det_quo_qty;
            $subtotal_modal += $val->det_quo_harga_modal*$val->det_quo_qty;
            $subtotal_final += $val->det_quo_harga_final*$val->det_quo_qty;
            $id_quo = $val->id_quo;
            }
            @endphp
        </tbody>
    </table>
</div>
<div class="modal-footer pt-3">
    <button type="button" class="btn bg-danger" data-method='sales/quotation/{!!$id_quo!!}' onclick='cancel(this)'>Cancel</button>
    <button type="save" class="btn bg-primary legitRipple">Simpan</button>
</div>

{!! Form::close() !!}