{!! Form::hidden('id_quo',$wh_out->id_quo,['id'=>'quoss','class'=>'form-control']) !!}
<div class="form_update">
    <div class="form-group row">
        <div class="col-lg-12">
            <label class="font-weight-bold">Tanggal Kirim</label>
            <input name="update_tgl" type="text" id="date" value="{{$first->tgl_kirim}}"
                class="form-control update_tgl tanggal_up" placeholder="Update Tanggal">
        </div>
    </div>
    <br>
    <div class="form-group row">
        <div class="col-lg-12">
            <label class="font-weight-bold">Nama Penerima</label>
            <input name="up_nama_penerima" type="text" id="penerima" value="{{$first->name}}"
                class="form-control update_name" placeholder="Update Nama Penerima">
        </div>
    </div>
    <br>
    <div class="form-group row">
        <div class="col-lg-12">
            <label class="font-weight-bold">Alamat</label>
            {!! Form::hidden('db_address',$first->id_alamat,['id'=>'db_address','class'=>'form-control']) !!}
            <div class="ups_addr">
                {!! Form::select('alamat_kirim', $address, $add,['id' => 'add_wh', 'class' =>
                'form-control form-control-select2 up_add address_update']) !!}
            </div>
        </div>
    </div>
    <br>
    <table class="table table-bordered table-striped table-hover m_outbound" id="tables_product">
        <thead>
            <tr>
                <th colspan="2">Barang</th>
                <th>Qty Kirim</th>
                <th>Note</th>
            </tr>
        </thead>
        @php
        $i = 1;
        $j =1;
        foreach ($main as $vals){
        @endphp
        <tbody>
            <input name="id_detail[]" type="hidden" id="" value="{{$vals->id}}" class="form-control" readonly>
            <tr class="text-center" id="tbl_row_{{$vals->id}}">
                <td colspan="2" class="text-center">{!!getProductDetail($vals->sku)->name!!}
                    <input name="id_product[]" type="hidden" id="" value="{{$vals->sku}}" class="form-control"
                        placeholder="qty_kirim" readonly>
                </td>
                <td class="text-center">
                    <input name="qty_update[]" type="number" id="update_qty" value="{{$vals->qty_kirim}}"
                        class="form-control update_qty" placeholder="qty_kirim">
                </td>
                <td>
                    <input name="keterangan[]" type="text" id="keterangan" value="{{$vals->note}}"
                        class="form-control up_keterangans" placeholder="Note">
                </td>
                <td>
                    <button type="button" id="btn_tambahbarang" onclick="add_barang(this)" data-id_out="{{$wh_out->id}}"
                        data-id="{{$vals->id}}" data-id_quo="{{$wh_out->id_quo}}" data-type="hapus_row_data"
                        data-equ="{{$vals->id}}" data-no_do="{{$no_do}}"
                        class="btn bg-danger-400 btn-icon rounded-round legitRipple"><i
                            class="fas fa-trash"></i></button>
                </td>
            </tr>
        </tbody>
        @php } @endphp
        <tr class="row_tabel"></tr>
    </table>
</div>
<br>
<div class="text-left">
    <button type="button" id="btn_tambahbarang" onclick="add_barang(this)" data-id_out="{{$wh_out->id}}"
        data-id_quo="{{$wh_out->id_quo}}" data-no_do="{{$no_do}}"
        class="btn bg-primary-400 btn-icon rounded-round legitRipple"><i class="fas fa-plus"></i></button>
</div>
<br><br>
@section('script')
<script src="{{ asset('ctrl/warehouses/wh-detail.js?v=').rand()}}" type="text/javascript"></script>
@endsection