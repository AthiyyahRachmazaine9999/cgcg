<table class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th colspan="3" class="text-center">Barang</th>
            <th colspan="3" class="text-center">Qty Kirim</th>
            <th colspan="3" class="text-center">SN Scan</th>
        </tr>
    </thead>
    <tbody>
        @php
        $i = 1;
        foreach ($main as $vals){
        @endphp
        <tr>
            <td colspan="3">
                {!!getProductDetail($vals->sku)->name!!}
                <p onclick="ScanBarcode(this)" data-dismiss="modal" data-toggle="modal" data-target="#m_modal2" data-qty="{{$vals->qty_kirim}}" data-id_outbound="{{$vals->id_outbound}}"
                    data-id_out_det="{{$vals->id}}" data-namebarang="{!!getProductDetail($vals->sku)->name!!}" data-no_wh_out="{{$no_wh_out}}" data-quo="{{$vals->quo}}" data-product="{{$vals->sku}}"><i class="icon-barcode2 icon-2x"></i></p>
            </td>
            <td colspan="3" class="text-center">
                <input name="update_qtykrm[]" type="text" id="update_DO" value="{{$vals->qty_kirim}}" class="form-control text-center" placeholder="qty_kirim" readonly>
            </td>
            <td colspan="3" class="text-center">
                {!!SNTotal($wh_out->id_quo,$vals->sku, $vals->id_outbound, $vals->id)!!}
            </td>
        </tr>
        @php } @endphp
    </tbody>
</table>
<br>