{!! Form::open(['method' => $method,'action'=>$action,'id'=>'m_form']) !!}
@php
$vendor = getVendor($vendor);
@endphp
<div class="d-md-flex flex-md-wrap mb-3">
    <div class="col-lg-6 mb-6 mb-md-2">
        <span class="text-muted">Pembelian Ke:</span>
        <ul class="list list-unstyled mb-0">
            <li>
                <h5 class="my-2">{!!$vendor->vendor_name!!}</h5>
            </li>
            <li><span class="font-weight-semibold">{!!$vendor->address!!}</span></li>
            <li>
                @php
                if($vendor->email == null || $vendor->email == "N"){ @endphp
                <a class="text-danger" href="#">Email tidak ada, Click To Add</a>
                @php }else{ @endphp
                {!!$vendor->email!!}
                @php }@endphp
            </li>
        </ul>
    </div>
</div>
<table class="table table-striped table-hover m_datatable" id="ptable">
    <thead class="thead-colored bg-teal">
        <tr class="text-center">
            <th rowspan="2">Product</th>
            <th rowspan="2" style="width: 10%;">Qty</th>
            <th rowspan="2">Tipe PO</th>
            <th colspan="3">Harga</th>
        </tr>
        <tr>
            <th>Type</th>
            <th>Satuan</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @php
        $subtotal = 0;
        $i = $j = $k = $l = $m = $n = 1 ;
        $c = count($product);
        foreach ($product as $val){ @endphp

        {!! Form::hidden('id_quo',$val->id_quo,['id'=>'id_quo','class'=>'form-control']) !!}
        {!! Form::hidden('sonum','SO'.sprintf("%06d", $val->id_quo),['id'=>'sonum','class'=>'form-control']) !!}
        {!! Form::hidden('idpro[]',$val->id,['id'=>'idpro','class'=>'form-control']) !!}
        <tr>
            <td>
                <p class="font-weight-bold">{!!getProductDetail($val->id_product)->name;!!}</p>
                {{strtoupper($val->det_quo_status_vendor)}} <br>
                {{$val->det_quo_note_beli}}
                {!! Form::hidden('vendor[]',$val->id_vendor_beli,null , ['id'=>'vendor','class' => 'form-control form-control-select2 vendor','placeholder' => '*']) !!}
            </td>
            <td>
                <input id="p_qty_{{$i++}}" type="number" class="form-control" name="p_qty[]" value="{{$val->det_quo_qty}}" placeholder="Qty" onchange="HitungNew({{$l++}},{{$c}})" onkeyup="HitungNew({{$m++}},{{$c}})">
            </td>
            <td>
                <div class="row">
                    <div class="col-lg-8">
                        {!! Form::select('if_type[]',$if_type,null , ['id'=>'if_type','class' => 'form-control form-control-select2 if_type']) !!}
                    </div>
                    {!! Form::text('p_ref[]','',['id'=>'p_ref','class'=>'form-control col-lg-4','placeholder'=>'Nomer PO Ref']) !!}
                </div>
            </td>
            <td>{{strtoupper($val->det_quo_type_beli)}}</td>
            <td class="text-right">
                <input id="p_harga_real_{{$j++}}" type="hidden" class="form-control" value="{{$val->det_quo_harga_final}}" readonly>
                <div>{{number_format($val->det_quo_harga_final)}}</div>
            </td>
            <td class="text-right">
                <input id="p_harga_hidden_{{$k++}}" type="hidden" class="form-control totalprice" value="{{$val->det_quo_qty*$val->det_quo_harga_final}}" readonly>
                <div id="p_harga_show_{{$n++}}">{{number_format($val->det_quo_qty*$val->det_quo_harga_final)}}</div>
            </td>
        </tr>
        @php

        $subtotal += $val->det_quo_qty*$val->det_quo_harga_final;
        }
        $vat = $subtotal/10;
        $subtotal_include = $subtotal+$vat;
        @endphp
    </tbody>
    <tfoot>
        <tr>
            <td class="text-right" colspan="5"><strong>SUBTOTAL</strong></td>
            <td class="text-right" id="subtotal">@php echo number_format($subtotal); @endphp</td>
        </tr>
        <tr>
            <td class="text-right" colspan="5"><strong>PPN</strong></td>
            <td class="text-right" id="vat">@php echo number_format($vat); @endphp</td>
        </tr>
        <tr>
            <td class="text-right" colspan="5"><strong>TOTAL</strong></td>
            <td class="text-right" id="include">@php echo number_format($subtotal_include); @endphp</td>
        </tr>
    </tfoot>
</table>
<div class="modal-footer pt-3">
    {!! Form::hidden('total',$subtotal_include,['id'=>'total','class'=>'form-control col-lg-6']) !!}
    <button type="button" class="btn btn-link legitRipple" data-dismiss="modal">Tutup</button>
    <button type="button" class="btn btn-danger btn-sm ml-3" onclick="PrintDraftPO()" ><i class="icon-printer mr-2"></i> Print</button>
    <button type="save" class="btn bg-primary legitRipple">Ajukan PO</button>
</div>

{!! Form::close() !!}