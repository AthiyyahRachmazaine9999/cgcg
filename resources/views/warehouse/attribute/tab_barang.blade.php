<div class="tab-pane fade show active" id="info">
    <table class="table table-bordered table-striped table-hover m_outbound">
        <thead>
            <tr>
                <th>Siap Kirim</th>
                <th>Barang</th>
                <th>Qty</th>
                <th>Qty Kirim</th>
                <th>Note Kirim</th>
                <th>Alamat</th>
            </tr>
        </thead>
        <tbody>
            @php
            $i = 1;
            $k = 1; $o = 1; $p = 1;
            $l = 1; $m = 1; $n = 1;
            foreach ($product as $val => $values){
            $qty_sisa = ($sends[$val]->det_quo_qty - $sends[$val]->sums);
            @endphp
            <tr>
                <td class="text-center">
                    @php
                    $attr = $product[$val]->kirim_status == "yes" ? "checked" : "";
                    $dadd = $product[$val]->kirim_status == "yes" ? $product[$val]->kirim_addr : $cadress;
                    @endphp
                    <input name="siapkirim[]" type="checkbox" value="{{$product[$val]->id}}"
                        class="form-check-input-switchery" {!!$attr!!} data-fouc>
                </td>
                <td>
                    @php
                    if ($product[$val]->id_quo==0) { @endphp
                    <input name="id_product[]" type="hidden" value="{{$product[$val]->id}}" class="form-control">
                    {!!getProductPo($product[$val]->id_product)->name!!}
                    @php } else {
                    $check = $product[$val]->id_product;
                    @endphp
                    <input name="id_product[]" type="hidden" value="{{$product[$val]->id}}" class="form-control">
                    {!!getProductDetail($check)->name!!}
                    @php } @endphp
                </td>
                <td class="text-center">
                    <input name="qty[]" type="text" value="{{$product[$val]->det_quo_qty}}" class="form-control"
                        placeholder=" Qty" readonly>
                    @if($sends[$val]->qty_kirim!=null)
                    <p class="text-danger text-left p_sisa">
                        {{$qty_sisa==0 ? 'Semua Telah Terkirim' : 'Sisa Qty: '.$qty_sisa}}
                    </p>
                    <input name="qty_sisa[]" type="hidden"
                        value="{{$qty_sisa==0 ? 0 : $qty_sisa}}"
                        class="form-control text-danger qty_sisas" readonly>
                    @endif
                </td>
                <td class="text-center">
<!--                     <input type="hidden" value="{{$sends[$val]->id}}" class="form-control val_ids_{{$sends[$val]->id}}"> -->
                    <input name="qty_kirim[]" type="number" id="qty_kirim"
                        max="{{$qty_sisa==0 ? $product[$val]->det_quo_qty : $qty_sisa}}" class="form-control kirim krm_{{$sends[$val]->id}}"
                        placeholder="Qty Kirim" required>

                </td>
                <td class="text-center">
                    <input name="kirim_note[]" type="text" value="{{$main->kirim_note}}" class="form-control"
                        placeholder="Catatan tambahan pengiriman">
                </td>
                <td style="width: 25%;">
                    {!! Form::select('address[]', $address, $dadd,['id' => 'address', 'class' => 'form-control
                    form-control-select2 address','required']) !!}
                </td>
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
            <span class="font-weight-semibold">Maaf</span> Ada {{$kurang}} Jenis Barang yang belum diterima, hanya bisa
            kirim yang sudah diterima
        </div>
        @endif
    </div>
    <div class="modal-footer">
            <button type="submit" class="btn btn-primary btn-labeled btn-labeled-left legitRipple firstKirim">
                <b><i class="fas fa-truck"></i></b> @php echo $method=='put' ? 'Update Kirim dan Cetak DO':'Kirim Dan
                Cetak DO'; @endphp
            </button>
    </div>
</div>
</div>