<form id="m_form_in">
    <br><br>
    <div class="form-group row">
        @php $sisa = $product->qty_terima - count($checksn);@endphp
        <div class="col-lg-10">
            <input type="hidden" value="{{$product->sku}}" name="sku">
            <input type="hidden" value="{{$id_quo}}" name="id_quo">
            <input type="hidden" value="{{count($checksn)}}" id="hasnumber">
            <label class="font-weight-bold">Input SN</label>
            <input type="text" id="number_in" class="form-control"
                placeholder="Arahkan Mouse disini / Copy Serial Number disini">
        </div>
        <div class="col-lg-2">
            <label class="font-weight-bold">Kurang Scan</label>
            <p class="text-danger font-weight-bold" id="sisascan">{{$sisa}}</p>
        </div>
        <button type="button" class="btn bg-danger-400 btn-labeled btn-labeled-left rounded-round legitRipple mt-3"
            id="delete-row"><b><i class="fas fa-trash-alt"></i></b>Delete</button>
    </div>
    <table id="sntable" class="table table-bordered table-striped table-hover getsn">
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th>SN Number</th>
            </tr>
        </thead>
        <tbody>
            @php foreach($checksn as $item) { @endphp
            <tr>
                <td style="width: 5%;"><a class="dropdown-item" onclick="DeleteSN(this)" data-id="{{$item->id}}"
                        data-type="inbound"><i class="icon-cancel-square2 text-danger"></i></a></td>
                <td>{{$item->sn}}</td>
            </tr>
            @php } @endphp
        </tbody>
    </table>
    <br>
    <div class="text-right">
        <button type="button" onclick="saveSNInbound(this)" class="btn btn-primary do_cetaks btn-submit">Save SN<i
                class="far fa-save ml-2"></i></button>
    </div>
</form>