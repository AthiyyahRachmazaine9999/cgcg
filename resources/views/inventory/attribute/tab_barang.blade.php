<div class="tab-pane fade show active" id="info">
    <table class="table table-bordered table-striped table-hover m_outbound">
        <thead>
            <tr>
                <th>Siap Kirim</th>
                <th>Barang</th>
                <th>Qty</th>
                <th>Note Kirim</th>
                <th>Alamat</th>
            </tr>
        </thead>
        <tbody>
            @php
            $i = 1;
            foreach ($product as $val){
            @endphp
            <tr>
                <td class="text-center">
                    @php
                    $attr = $val->kirim_status == "yes" ? "checked" : "";
                    $dadd = $val->kirim_status == "yes" ? $val->kirim_addr : $cadress;
                    @endphp
                    <input name="siapkirim[]" type="checkbox" value="{{$val->id}}" class="form-check-input-switchery" {!!$attr!!} data-fouc>
                </td>
                <td>
                    @php
                    if ($val->id_quo==0) { @endphp
                    <input name="id_product[]" type="hidden" value="{{$val->id}}" class="form-control">
                    {!!getProductPo($val->id_product)->name!!}
                    @php } else {
                    $check = $val->id_product;
                    @endphp
                    <input name="id_product[]" type="hidden" value="{{$val->id}}" class="form-control">
                    {!!getProductDetail($check)->name!!}
                    @php } @endphp
                </td>
                <td class="text-center">
                    <input name="qty[]" type="number" value="{{$val->det_quo_qty}}" class="form-control" placeholder="Qty">
                </td>
                <td class="text-center">
                    <input name="kirim_note[]" type="text" value="{{$main->kirim_note}}" class="form-control" placeholder="Catatan tambahan pengiriman">
                </td>
                <td style="width: 25%;">
                    {!! Form::select('address[]', $address, $dadd,['id' => 'address', 'class' => 'form-control form-control-select2 address','require']) !!}
                </td>
            </tr>
            @php } @endphp
        </tbody>
    </table>

    <div class="card-body">
        @if ($main->status == 'partial')

        @php $kurang = count($purchase) - count($product); @endphp
        <div class="alert alert-danger alert-styled-left alert-dismissible">
            <span class="font-weight-semibold">Maaf</span> Ada {{$kurang}} Jenis Barang yang belum diterima, hanya bisa kirim yang sudah diterima
        </div>
        @endif
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary btn-labeled btn-labeled-left legitRipple">
            <b><i class="fas fa-truck"></i></b> @php echo $method=='put' ? 'Update Kirim':'Kirim'; @endphp
        </button>

    </div>
</div>