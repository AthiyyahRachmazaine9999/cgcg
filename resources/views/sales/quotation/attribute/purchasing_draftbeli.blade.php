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

        $time_inv = $invoice == null ? '0000-00-00':$invoice->tgl_invoice;
        $times = $main->quo_type == 1 ? $main->created_at:$main->quo_order_at;
        $checkppn = GetPPN($time_inv,$times);
        foreach ($product as $val){ @endphp

        {!! Form::hidden('id_quo',$val->id_quo,['id'=>'id_quo','class'=>'form-control']) !!}
        {!! Form::hidden('sonum','SO'.sprintf("%06d", $val->id_quo),['id'=>'sonum','class'=>'form-control']) !!}
        {!! Form::hidden('idpro[]',$val->id,['id'=>'idpro','class'=>'form-control']) !!}
        {!! Form::hidden('id_product[]',$val->id_product,['id'=>'id_product','class'=>'form-control']) !!}

        <tr>
            <td>
                <p class="font-weight-bold">{!!getProductDetail($val->id_product)->name;!!}</p>
                {{strtoupper($val->det_quo_status_vendor)}} <br>
                {{$val->det_quo_note_beli}}
                {!! Form::hidden('vendor[]',$val->id_vendor_beli,null , ['id'=>'vendor','class' => 'form-control form-control-select2 vendor','placeholder' => '*']) !!}
            </td>
            <td>
                <input id="p_qty_{{$i++}}" type="number" readonly class="form-control" name="p_qty[]" value="{{$val->det_quo_qty_beli}}" placeholder="Qty" onchange="HitungNew({{$l++}},{{$c}},{{$checkppn}})" data-ppn="{{$checkppn}}" onkeyup="HitungNew({{$m++}},{{$c}})">
            </td>
            <td>{{strtoupper($val->det_quo_type_beli)}}</td>
            <td class="text-right">
                <input id="p_harga_real_{{$j++}}" type="hidden" class="form-control" value="{{$val->det_quo_harga_final}}" readonly>
                <div>{{number_format($val->det_quo_harga_final)}}</div>
            </td>
            <td class="text-right">
                <input id="p_harga_hidden_{{$k++}}" type="hidden" class="form-control totalprice" value="{{$val->det_quo_qty_beli*$val->det_quo_harga_final}}" readonly>
                <div id="p_harga_show_{{$n++}}">{{number_format($val->det_quo_qty_beli*$val->det_quo_harga_final)}}</div>
            </td>
        </tr>
        @php

        $subtotal += $val->det_quo_qty_beli*$val->det_quo_harga_final;
        }
        $vat = $subtotal*($checkppn/100);
        $subtotal_include = $subtotal+$vat;
        @endphp
    </tbody>
    <tfoot>
        <tr>
            <td class="text-right" colspan="4"><strong>SUBTOTAL</strong></td>
            <td class="text-right">@php echo number_format($subtotal,2); @endphp</td>
        </tr>
        <tr>
            <td class="text-right" colspan="4"><strong>PPN {{$checkppn}} %</strong></td>
            <td class="text-right">{{number_format($vat,2)}}</td>
        </tr>
        <tr>
            <td class="text-right" colspan="4"><strong>TOTAL</strong></td>
            <td class="text-right">@php echo number_format($subtotal_include,2); @endphp</td>
        </tr>
        @if($lanjut=='no')
        <tr>
            <div class="alert bg-warning text-white alert-styled-left alert-dismissible">
                <span class="font-weight-semibold">Warning!</span> Margin kurang dan belum di approve. PO tidak bisa dibuat
            </div>
        </tr>
        @endif
        @if($document->doc_po == null)
        <tr>
            <div class="alert bg-warning text-white alert-styled-left alert-dismissible">
                <span class="font-weight-semibold">Warning!</span> Document PO customer belum diupload pembelian tidak dapat dilakukan
            </div>
        </tr>
        @endif
    </tfoot>
</table>
<div class="modal-footer pt-3">
    {!! Form::hidden('total',$subtotal_include,['id'=>'total','class'=>'form-control col-lg-6']) !!}
    <button type="button" class="btn btn-link legitRipple" data-dismiss="modal">Tutup</button>
    <button type="button" class="btn btn-danger btn-sm ml-3" onclick="PrintDraftPO()"><i class="icon-printer mr-2"></i> Print</button>
    @if($lanjut=='yes' && $document->doc_po !== null)
    <button type="save" class="btn bg-primary legitRipple">Ajukan PO</button>
    @endif
</div>

{!! Form::close() !!}