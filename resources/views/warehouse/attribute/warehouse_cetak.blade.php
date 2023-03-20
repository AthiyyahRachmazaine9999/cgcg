{!! Form::open(['method' => $method,'action'=>$action,'id'=>'m_form']) !!}
@php
$vendor = getVendor($vendor);
@endphp
<div class="d-md-flex flex-md-wrap mb-3">
    <div class="col-lg-6 mb-6 mb-md-2">
        <label class="font-weight-bold">Pengiriman Ke:</label>
        {!! Form::select('address[]', $address, $cadress,['id' => 'address', 'class' => 'form-control form-control-select2 address','require']) !!}
    </div>
</div>
<table class="table table-striped table-hover m_datatable" id="ptable">
    <thead class="thead-colored bg-teal">
        <tr class="text-center">
            <th rowspan="2">Product</th>
            <th rowspan="2" style="width: 10%;">Qty</th>
        </tr>
    </thead>
    <tbody>
        @php
        foreach ($product as $val){ @endphp

        {!! Form::hidden('id_quo',$val->id_quo,['id'=>'id_quo','class'=>'form-control']) !!}
        {!! Form::hidden('idpro[]',$val->id,['id'=>'idpro','class'=>'form-control']) !!}
        <tr>
            <td>
                <p class="font-weight-bold">{!!getProductDetail($val->id_product)->name;!!}</p>
            </td>
            <td>
                <input id="p_qty_{{$i++}}" type="number" class="form-control" name="p_qty[]" value="{{$val->det_quo_qty}}" placeholder="Qty" onchange="HitungNew({{$l++}},{{$c}})" onkeyup="HitungNew({{$m++}},{{$c}})">
            </td>
        </tr>
        @php } @endphp
    </tbody>
</table>
<div class="modal-footer pt-3">
    <button type="button" class="btn btn-link legitRipple" data-dismiss="modal">Tutup</button>
    <button type="button" class="btn btn-danger btn-sm ml-3" onclick="PrintDraftPO()" ><i class="icon-printer mr-2"></i> Print</button>
</div>

{!! Form::close() !!}