{!! Form::open(['method' => $method,'action'=>$action]) !!}
<div class="table-responsive">
    <table class="table table-lg">
        <tbody>
            @php
            $subtotal_modal = $subtotal_ongkir = $subtotal_order = 0;
            $margin = 0;
            foreach ($product as $val){ @endphp
            <tr>
                <td class="text-left font-weight-bold">
                    {!! Form::hidden('id_quo',$val->id_quo,['id'=>'id_quo','class'=>'form-control']) !!}
                    {!! Form::hidden('idpro[]',$val->id,['id'=>'idpro','class'=>'form-control']) !!}
                    @php $cname  = $val->id_product == "new" ? "<b class='text-danger'>[NEW REQ]</b> ".getProductReq($val->id_product_request)->req_product : getProductDetail($val->id_product)->name;  @endphp 
                    {!!$cname!!}
                    {!! Form::select('vendor[]',$stock,null , ['id'=>'vendor','class' => 'form-control form-control-select2 vendor','placeholder' => '*']) !!}
                    {!! Form::text('note[]',$val->det_quo_note_kirim,['id'=>'note','class'=>'form-control','placeholder'=>'Keterangan pengiriman']) !!}

                </td>
                <td class="text-left font-weight-bold">@php echo $val->det_quo_qty." Unit"; @endphp</td>
                <td class="text-right font-weight-bold">
                    {!! Form::number('p_berat[]',$val->det_quo_berat,['id'=>'p_berat','class'=>'form-control','placeholder'=>'Berat per barang']) !!}
                </td>
                <td class="text-right font-weight-bold">
                    {!! Form::number('p_price[]',$val->det_quo_harga_ongkir,['id'=>'p_price','class'=>'form-control','placeholder'=>'Harga Ongkir']) !!}
                </td>
            </tr>

            @php } @endphp
        </tbody>
    </table>
</div>
<div class="modal-footer pt-3">
    <button type="button" class="btn btn-link legitRipple" data-dismiss="modal">Tutup</button>
    <button type="save" class="btn bg-primary legitRipple">Simpan</button>
</div>

{!! Form::close() !!}