<div class="tab-pane fade show active" id="info">
    <table class="table table-bordered table-striped table-hover m_outbound">
        <thead>
            <tr>
                <th>Siap Kirim</th>
                <th>Barang</th>
                <th>Qty</th>
                <th>Qty Terima</th>
                <th>Qty Kirim</th>
                <th>Note Kirim</th>
            </tr>
        </thead>
        <tbody>
            @php
            $i = 1;
            $k = 1; $o = 1; $p = 1;
            $l = 1; $m = 1; $n = 1;
            foreach ($product as $val => $values){
            $checked = getCheckList($product[$val]->id_quo,$product[$val]->id_product,$product[$val]->det_quo_qty);
            $qty_sisa = ($product[$val]->det_quo_qty-$checked['qty_kirim']);
            @endphp
            <tr>
                <td class="text-center">
                    @php
                    $attr = getWhOutDetail('id_outbound',$product[$val]->id, $product[$val]->id_product)==null ? '' :
                    "checked";
                    $dadd = $product[$val]->kirim_status == "yes" ? $product[$val]->kirim_addr : $cadress;
                    @endphp

                    @if($checked['blue']=="oke")
                    <a class="dropdown-item"><i class="fas fa-check-double text-primary"></i></a>
                    @endif
                    @if($checked['close']=="show" && $checked['blue']!="oke")
                    <a class="dropdown-item"><i class="icon-cancel-square2 text-danger"></i></a>
                    @endif
                    @if($checked['green']=="yes" && $checked['blue']!="oke")
                    <input name="siapkirim[]" type="checkbox" value="{{$product[$val]->id_product}}" 
                    class="form-check-input-switchery" {!!$attr!!} data-fouc>
                    @endif
                </td>
                <td>
                    @php
                    if ($product[$val]->id_quo==0) { @endphp
                    <input name="id_product[]" type="hidden" value="{{$product[$val]->id_product}}"
                        class="form-control">
                    {!!getProductPo($product[$val]->id_product)->name!!}
                    @php } else {
                    $check = $product[$val]->id_product;
                    @endphp
                    <input name="id_product[]" type="hidden" value="{{$product[$val]->id_product}}"
                        class="form-control">
                    {!!getProductDetail($check)->name!!}
                    @php } @endphp
                </td>
                <td class="text-center">
                    <input name="qty[]" type="text" value="{{$product[$val]->det_quo_qty}}" class="form-control"
                        placeholder="Qty" readonly>
                    @if($qty_sisa!=0 && $checked['qty_kirim']!=0 && $checked['qty_terima']!="Belum Diterima" && count($head)!=0)
                    <br>
                    <p class="text-danger text-left">
                        {{"Qty Sisa = ".$qty_sisa}}
                    </p>
                    @endif
                </td>
                <td class="text-center">
                    <input name="qty_terima[]" type="text"
                        value="{{$checked['qty_terima']}}" id="qty_terima"
                        class="form-control terima" placeholder="Qty terima" readonly>

                </td>
                <td class="text-center">
                    @if($checked['read']=="readonly")
                    <input name="qty_kirim[]" type="text" id="qty_kirim" value="{{$checked['qty_kirim']}}"
                        class="form-control" placeholder="Qty Kirim" readonly>
                    @else
                    <input name="qty_kirim[]" type="number" id="qty_kirim" value="{{$checked['qty_kirim']}}"
                        class="form-control" placeholder="Qty Kirim" required>
                    @endif
                </td>
                <td class="text-center">
                    <input name="kirim_note[]" type="text" value="{{$main->kirim_note}}" class="form-control"
                        placeholder="Catatan tambahan pengiriman">
                </td>
                <!-- <td style="width: 25%;">
                    {!! Form::select('address[]', $address, $dadd,['id' => 'address', 'class' => 'form-control
                    form-control-select2 address','required']) !!}
                </td> -->
            </tr>
            @php } @endphp
        </tbody>
    </table>
    <br>
    <div class="tbl_penerima">

        <div class="card-body">
            @if ($main->status == 'partial')

            @php $kurang = count($purchase) - count($product); @endphp
            <div class="alert alert-danger alert-styled-left alert-dismissible">
                <span class="font-weight-semibold">Maaf</span> Ada {{$kurang}} Jenis Barang yang belum diterima, hanya
                bisa kirim yang sudah diterima
            </div>
            @endif
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary btn-labeled btn-labeled-left legitRipple firstKirim"
                data-toggle="modal" data-target="#m_modal">
                <b><i class="fas fa-truck"></i></b> Kirim
            </button>
        </div>
    </div>
</div>