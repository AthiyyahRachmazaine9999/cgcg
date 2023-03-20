{!! Form::open(['method' => $method,'action'=>$action]) !!}
<div class="table-responsive">
    <table class="table table-striped table-hover other_datatable" id="ptable">
        <thead class="thead-colored bg-teal">
            <tr class="text-center">
                <th rowspan="2">Barang</th>
                <th rowspan="2">Qty</th>
                <th colspan="2">Stock</th>
                <th colspan="4">Harga Per Item</th>
            </tr>
            <tr class="text-center">
                <th>Product</th>
                <th>Purchasing</th>
                <th>Live</th>
                <th>Order</th>
                <th>Modal Product</th>
                <th>Modal Purchasing</th>
            </tr>
        </thead>
        <tbody>
            @php
            $subtotal_modal = $subtotal_final = $subtotal_order = 0;
            $margin = 0;
            foreach ($product as $val){
            $cname = $val->id_product == "new" ? "<b class='text-danger'>[NEW SKU]</b> ".getProductReq($val->id_product_request)->req_product : getProductDetail($val->id_product)->name;
            $cprice = $val->id_product == "new" ? getProductReq($val->id_product_request)->req_price : getProductDetail($val->id_product)->price;
            $oprice = $val->id_product == "new" ? $val->det_quo_harga_req : $val->det_quo_harga_order;
            $vendor = $val->id_vendor == "" || $val->id_vendor == null ? '': getVendor($val->id_vendor)->vendor_name;
            $vendor_purc = $val->id_vendor_beli == "" || $val->id_vendor_beli == null ? '': getVendor($val->id_vendor_beli)->vendor_name;
            @endphp
            <tr>
                {!! Form::hidden('id_quo',$val->id_quo,['id'=>'id_quo','class'=>'form-control']) !!}
                {!! Form::hidden('idpro[]',$val->id,['id'=>'idpro','class'=>'form-control']) !!}

                <td onclick="ShowProduct({{$val->id}})">{!!$cname!!}</td>
                <td class="text-center">@php echo $val->det_quo_qty; @endphp</td>
                <td class="text-center">
                    <p class="font-weight-bold">{{$vendor}}</p>
                    @php echo ucfirst($val->det_quo_status_vendor);@endphp
                    <p class="text-danger font-italic">{{$val->det_quo_note}} </p>
                </td>
                <td class="text-center">
                    @php
                    if($val->id_vendor_beli==null) {
                    @endphp
                    {!! Form::select('vendor[]',$stock,null , ['id'=>'vendor','class' => 'form-control form-control-select2 vendor','placeholder' => '*']) !!}
                    @php
                    }else{
                    @endphp
                    <select class="form-control form-control-select2 vendor" name="vendor[]" id="vendor">
                        <option value="{{$val->id_vendor_beli}}">{!!getVendor($val->id_vendor_beli)->vendor_name!!}</option>
                    </select>
                    @php
                    }
                    @endphp
                    {!! Form::select('stock[]',$stock,$val->det_quo_status_beli , ['id'=>'stock','class' => 'form-control form-control-select2 stock','placeholder' => '*']) !!}
                    {!! Form::text('note[]',$val->det_quo_note_beli,['id'=>'note','class'=>'form-control','placeholder'=>'Keterangan tambahan']) !!}
                </td>
                <td class="text-right">{{number_format($cprice)}}</td>
                <td class="text-right">
                    @php echo number_format($oprice); @endphp
                </td>
                <td class="text-right">@php echo number_format($val->det_quo_harga_modal); @endphp</td>
                <td class="text-right">
                    {!! Form::select('bayar[]',$bayar,$val->det_quo_type_beli , ['id'=>'bayar','class' => 'form-control form-control-select2 stock','placeholder' => '*']) !!}
                    {!! Form::number('p_price[]',$val->det_quo_harga_final,['id'=>'p_price','class'=>'form-control','placeholder'=>'Harga Beli','step'=>'any']) !!}
                </td>
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
    <button type="button" class="btn bg-danger" data-method = 'sales/quotation/{!!$id_quo!!}' onclick='cancel(this)'>Cancel</button>
    <button type="save" class="btn bg-primary legitRipple">Simpan</button>
</div>

{!! Form::close() !!}