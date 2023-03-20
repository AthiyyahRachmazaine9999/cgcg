<table class="table table-bordered table-striped table-hover m_outbound">
    <thead>
        <tr>
            <th>Pilih</th>
            <th>Barang</th>
            <th class="text-center">Qty Asli</th>
            <th class="text-center">Qty Claim</th>
            <th class="text-center">Harga Satuan</th>
        </tr>
    </thead>
    <tbody>
        @php
        $i = 0;
        foreach ($product as $val => $values){
        @endphp
        <tr>
            <td class="text-center">
                <input type="hidden" name="id_quopro[]" value="{{$values->id}}" class="form-control">
                <input name="pilih[]" type="checkbox" value="{{$values->id}}" class="form-check-input-switchery" checked data-fouc>
            </td>
            <td>{!!getProductDetail($values->id_product)->name!!}</td>
            <td class="text-center">{{$values->det_quo_qty}}</td>
            <td class="text-center">
                <input name="qty_invoice[]" type="number" id="qty_kirim_{{$val}}" max="{{$values->det_quo_qty}}" class="form-control qty_kirim" placeholder="Qty Invoice">
            </td>
            <td class="text-right">{{number_format(($values->det_quo_harga_order),2, ',', '.')}}</td>

        </tr>
        @php } @endphp
        <tr>
            <td class="text-center">
                <input name="ongkos_semua" type="checkbox" value="{{$values->id}}" class="form-check-input-switchery" checked data-fouc>
            </td>
            <td>
                <select class="form-control form-control-select2" name="ongkir_type" id="ongkir_type">
                    <option value="ongkir_paket">Ongkir Paket</option>
                    <option value="ongkir_input">Custom Ongkir</option>
                </select>
            </td>
            <td class="text-center">1</td>
            <td class="text-center">
                <input name="ongkir" type="number" id="ongkir" max="1" class="form-control kirim krm" value="1">
            </td>
            <td class="text-right">
                <div class="form-control" id="ongkirdefault">{{number_format(($otherprice->ongkir_customer/1.11),2, ',', '.')}}</div>
                <input name="ongkir" id="ongkirinput" step="0.25" type="number" max="{{$otherprice->ongkir_customer/1.11}}" class="form-control" placeholder="Ongkir">

            </td>

        </tr>
    </tbody>
</table>
<script>
    var elems = Array.prototype.slice.call(document.querySelectorAll('.form-check-input-switchery'));
    elems.forEach(function(html) {
        var switchery = new Switchery(html);
    });
    
    var checkItem = document.querySelectorAll('.form-check-input-switchery');

    for (let i = 0; i < checkItem.length; i += 1) {
        
        $('#qty_kirim_'+[i]).prop('required',true);
        checkItem[i].onchange = () => {
            if (checkItem[i].checked === true) {
                $('#qty_kirim_'+[i]).prop('required',true);
                console.log('#qty_kirim_'+[i]);
            } else {
                $('#qty_kirim_'+[i]).prop('required',false);
            }
        }
    }

    $('#ongkirinput').hide();
    $("#ongkir_type").select2({
        allowClear: true,
        width: "100%",
    });
    $("#ongkir_type").on("change", function(e) {
        e.preventDefault();

        var option = $('option:selected', this).val();
        if (option == "ongkir_input") {
            $('#ongkirinput').show();
            $('#ongkirdefault').hide();

        } else {
            $('#ongkirinput').hide();
            $('#ongkirdefault').show();
        }
    });
</script>