{!! Form::open(['method' => $method,'action'=>$action]) !!}
<div class="row">
    {!! Form::hidden('id',$main->id,['id'=>'id','class'=>'form-control']) !!}
    {!! Form::hidden('idinv',$part->id,['id'=>'idinv','class'=>'form-control']) !!}
    {!! Form::hidden('dbs',$dbs,['id'=>'dbs','class'=>'form-control']) !!}
    <div class="col-lg-6">
        <div class="form-group">
            <label>Tanggal</label>
            <input type="date" value="{{$part->tgl_invoice}}" id="end_date" class="form-control" data-column="5" name="date" placeholder="Enter Date" require>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group">
            <label>Tanggal Jatuh Tempo</label>
            <input type="date" value="{{$part->tgl_jatuhtempo}}" id="tempo" class="form-control" data-column="5" name="tempo" placeholder="Enter Date" require>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="form-group">
            <label>Sign By</label>
            <select class="form-control form-control-select2" name="user" id="user_edit" require>
                <option></option>
                @foreach ($user as $spv)
                <option value="{{ $spv->id }}" @php echo $part->sign_by == $spv->id ? 'selected="selected"' : '' ; @endphp >{{ $spv->emp_name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group">
            <label>Pembulatan (Masukan jumlah digit)</label>
            {!! Form::number('digit',$part->digit,['id'=>'pembulatan','class'=>'form-control','placeholder'=>'1-3 digit']) !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="form-group">
            <label>Tipe Invoice</label>
            <select class="form-control form-control-select2" name="jenis" id="type">
                <option value="qty" selected>Qty</option>
            </select>
        </div>
    </div>
</div>
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
        foreach ($product as $val => $values){
        $checkbarang = GetInvPartQty($part->id,$values->id_product);
        $attr = $checkbarang == null ? '' : "checked";
        $reqd = $checkbarang == null ? '' : "required";
        $qty  = $checkbarang == null ? '' : $checkbarang->qty;
        @endphp
        <tr>
            <td class="text-center">
                <input type="hidden" name="id_quopro[]" value="{{$values->id}}" class="form-control">
                <input name="pilih[]" type="checkbox" value="{{$values->id}}" class="form-check-input-switchery" {{$attr}} data-fouc>
            </td>
            <td>{!!getProductDetail($values->id_product)->name!!}</td>
            <td class="text-center">{{$values->det_quo_qty}}</td>
            <td class="text-center">
                <input name="qty_invoice[]" type="number" {{$reqd}} value="{{$qty}}" id="qty_kirim_{{$val}}" max="{{$values->det_quo_qty}}" class="form-control qty_kirim" placeholder="Qty Invoice">
            </td>
            <td class="text-right">{{number_format(($values->det_quo_harga_order),2, ',', '.')}}</td>
        </tr>
        @php } @endphp
        <tr>
            @php
            $checkbutton = $part->ongkir !== null ? 'checked':'' ;
            $checkhidden = $part->ongkir !== null ? 'selected="selected"':'' ;
           @endphp
            <td class="text-center">
                <input name="ongkos_semua" type="checkbox" value="{{$values->id}}" class="form-check-input-switchery" {{$checkbutton}} data-fouc>
            </td>
            <td>
                <select class="form-control form-control-select2" name="ongkir_type" id="ongkir_type">
                    <option value="ongkir_paket" {{$checkhidden}}>Ongkir Paket</option>
                    <option value="ongkir_input" {{$checkhidden}}>Custom Ongkir</option>
                </select>
            </td>
            <td class="text-center">1</td>
            <td class="text-center">
                <input name="ongkir" type="number" id="ongkir" max="1" class="form-control kirim krm" value="1">
            </td>
            <td class="text-right">
                <div class="form-control" id="ongkirdefault">{{number_format(($otherprice->ongkir_customer/1.11),2, ',', '.')}}</div>
                <input name="ongkir" value="{{$part->ongkir}}" id="ongkirinput" step="0.25" type="number" max="{{$otherprice->ongkir_customer/1.11}}" class="form-control" placeholder="Ongkir">

            </td>

        </tr>
    </tbody>
</table>

<div class="text-right mt-3">
    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
    <button type="submit" class="btn btn-primary" id="textinv">@if($kondisi=='partial') Generate @else Update @endif<i class="far fa-save ml-2"></i></button>
</div>

{!! Form::close() !!}

<script>
    var elems = Array.prototype.slice.call(document.querySelectorAll('.form-check-input-switchery'));
    elems.forEach(function(html) {
        var switchery = new Switchery(html);
    });
    var checkItem = document.querySelectorAll('.form-check-input-switchery');

    for (let i = 0; i < checkItem.length; i += 1) {

        checkItem[i].onchange = () => {
            if (checkItem[i].checked === true) {
                $('#qty_kirim_' + [i]).prop('required', true);
                console.log('#qty_kirim_' + [i]);
            } else {
                $('#qty_kirim_' + [i]).prop('required', false);
            }
        }
    }
    $("#ongkir_type").select2({
        allowClear: true,
        width: "100%",
    });
    if ($("#ongkir_type").val() == 'ongkir_input') {
        $('#ongkirinput').show();
        $('#ongkirdefault').hide();
    } else {
        $('#ongkirinput').hide();
        $('#ongkirdefault').show();
    }
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