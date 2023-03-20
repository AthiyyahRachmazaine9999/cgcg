<div class="form-group row">
    <div class="col-lg-12">
        <label class="font-weight-bold">Tanggal Kirim</label>
        <input name="tgl" type="text" id="date" value="{{$first->tgl_kirim}}" class="form-control read_tgl"
            placeholder="Update Tanggal" readonly>
    </div>
</div>
<br>
<div class="form-group row">
    <div class="col-lg-12">
        <label class="font-weight-bold">Nama Penerima</label>
        <input name="nama_penerima" type="text" id="penerima" value="{{$first->name}}" class="form-control read_name"
            placeholder="Update Nama Penerima" readonly>
    </div>
</div>
<br>
<div class="form-group row">
    <div class="col-lg-12">
        <label class="font-weight-bold">Alamat</label>
        <div class="read_add">{{$add}}</div>
        {!! Form::hidden('db_address',$first->id_alamat,['id'=>'db_address','class'=>'form-control']) !!}
    </div>
</div>
<br>
<table class="table table-bordered table-striped table-hover m_outbound">
    <thead>
        <tr>
            <th colspan="2">Barang</th>
            <th>Qty Kirim</th>
            <th>Note</th>
        </tr>
    </thead>
    @php
    $i = 1;
    foreach ($main as $vals){
    @endphp
    <tbody>
        <input name="id_detail[]" type="hidden" id="" value="{{$vals->id}}" class="form-control" readonly>
        <tr class="text-center">
            <td colspan="2" class="text-center">{!!getProductDetail($vals->sku)->name!!}
                <input name="id_product[]" type="hidden" id="" value="{{$vals->sku}}" class="form-control"
                    placeholder="qty_kirim" readonly>
            </td>
            <td class="text-center">
                <input name="qty_noup[]" type="text" id="read_qty" value="{{$vals->qty_kirim}}"
                    class="form-control read_qty" placeholder="qty_kirim" readonly>
            </td>
            <td>
                <input name="keterangans[]" type="text" id="keterangan" value="{{$vals->note}}"
                    class="form-control keterangans" placeholder="Note" readonly>
            </td>
        </tr>
    </tbody>
    @php } @endphp
</table>