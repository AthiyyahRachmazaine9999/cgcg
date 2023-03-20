    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th colspan="3">Barang</th>
                <th colspan="3">Qty Kirim</th>
            </tr>
        </thead>
        <tbody>
            @php
            $i = 1;
            foreach ($main as $vals){
            $check = getProductQuo($vals->id_product)->id_product
            @endphp
            <tr class="text-center">
                <td colspan="3" class="text-center">{!!getProductDetail($check)->name!!}
                </td>
                <td colspan="3" class="text-center">
                    <input name="update_qtykrm[]" type="text" id="update_DO" value="{{$vals->qty_kirim}}"
                        class="form-control text-center" placeholder="qty_kirim" readonly>
                </td>
            </tr>
            @php } @endphp
        </tbody>
    </table>
    <br>
