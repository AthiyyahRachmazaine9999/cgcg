<div id="m_form">
    <br>
    <div class="form-group row">
        @php $sisa = $product->qty_kirim - count($check);@endphp
        <div class="col-lg-10">
            <input type="hidden" value="{{$product->sku}}" name="sku">
            <input type="hidden" value="{{$id_quo}}" name="id_quo">
            <input type="hidden" value="{{$product->id_outbound}}" name="id_outbound">
            <input type="hidden" value="{{$product->id}}" name="id_out_det">
            <input type="hidden" value="{{count($check)}}" id="hasnumber">
            <input type="hidden" value="{{$sisa}}" id="ini_sisascan">
            <label class="font-weight-bold">Input SN</label>
            <input type="text" id="number" class="form-control"
                placeholder="Arahkan Mouse disini / Copy Serial Number disini">
        </div>
        <div class="col-lg-2">
            <label class="font-weight-bold">SN scan</label>
            <p class="text-danger font-weight-bold" id="sisascan">{{$sisa}}</p>
        </div>
    </div>
    <table id="sntable" class="table table-bordered table-striped table-hover getsn">
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th>SN Number</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
        <tfoot>
            @php foreach($checksn as $item){ @endphp
            <tr id="item_sn_{{$item->sn}}">
                <td style="width: 5%;">
                    @if($item->id_out!=null && $item->id_out_det!=null)
                    <i class="fas fa-check text-primary"></i>
                    @elseif($sisa>0)
                    <input type='checkbox' class="scan_checked" name="number[]" value="{{$item->sn}}" id="check">
                    @else
                    <input type="checkbox" class="scan_checked" name="number[]" value="{{$item->sn}}" id="check">
                    @endif
                </td>
                <td id="">{{$item->sn}}</td>
            </tr>
            @php } @endphp
        </tfoot>
    </table>
    <br>
    <div class="text-right">
        <button type="button" onclick="saveSN(this)" class="btn btn-primary do_cetaks btn-submit">Save SN<i
                class="far fa-save ml-2"></i></button>
    </div>
    </form>