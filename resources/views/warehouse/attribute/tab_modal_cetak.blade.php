{!! Form::open(['method' => $method,'class'=>'form-submits']) !!}
<h6>@if($req_no==null)
    WH/OUT/{{Carbon\Carbon::now()->format('y')}}/{{sprintf("%06d", $wh_out->id)}}
    @else
    WH/OUT/{{Carbon\Carbon::now()->format('y')}}/{{sprintf("%06d", $wh_out->id)}}/{{$req_no}}
    @endif
</h6>
<div class="form-group row">
    <div class="col-lg-12">
        <label class="font-weight-bold">Any Update ?* </label>
        {!! Form::select('type_cetak', array('ups' => 'Yes', 'noup' =>
        'No'), '', ['id' =>'type_cetak', 'class' => 'form-control form-control-select2', 'placeholder' =>
        '*','required']) !!}
    </div>
</div>
<br>
<div class="form-group row">
    <div class="col-lg-12">
        <label class="font-weight-bold">Tanggal Kirim</label>
        <input name="tgl" type="text" id="date" value="{{$tomain}}" class="form-control read_tgl"
            placeholder="Update Tanggal" readonly>
        <input name="update_tgl" type="date" id="date" value="{{$tomain}}" class="form-control update_tgl tanggal_up"
            placeholder="Update Tanggal">
    </div>
</div>
<br>
<div class="form-group row">
    <div class="col-lg-12">
        <label class="font-weight-bold">Nama Penerima</label>
        <input name="nama_penerima" type="text" id="penerima" value="{{$namapic}}" class="form-control read_name"
            placeholder="Update Nama Penerima" readonly>
        <input name="nama_penerima" type="text" id="penerima" value="" class="form-control update_name"
            placeholder="Update Nama Penerima">
    </div>
</div>
<br>
<div class="form-group row">
    <div class="col-lg-12">
        <label class="font-weight-bold">Alamat</label>
        <div class="read_add">{{$vaddress}}</div>
        {!! Form::hidden('db_address',$id_addr,['id'=>'db_address','class'=>'form-control']) !!}
        <div class="ups_addr">
            {!! Form::select('alamat_kirim', $address, $vaddress,['id' => 'add_wh', 'class' =>
            'form-control form-control-select2 up_add address_update', 'placeholder' => 'Pilih Alamat']) !!}
        </div>
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
    $check = getProductQuo($vals->id_product)->id_product
    @endphp
    <tbody>
        {!! Form::hidden('no_wh_out',$vals->no_wh_out,['id'=>'no_wh_out','class'=>'form-control']) !!}
        {!! Form::hidden('id_quo',$vals->id_quo,['id'=>'id_quos','class'=>'form-control']) !!}
        {!! Form::hidden('id_wh_out',$vals->id_wh_out,['id'=>'id_wh_out','class'=>'form-control']) !!}
        {!! Form::hidden('ids[]',$vals->id,['id'=>'ids','class'=>'form-control']) !!}
        {!! Form::hidden('kirim_addr[]',$vals->kirim_addr,['id'=>'kirim_addr','class'=>'form-control']) !!}
        <tr class="text-center">
            <td colspan="2" class="text-center">{!!getProductDetail($check)->name!!}
            </td>
            <td class="text-center">
                <input name="qty_noup[]" type="text" id="read_qty" value="{{$vals->qty_kirim}}"
                    class="form-control read_qty" placeholder="qty_kirim" readonly>
                <input name="qty_update[]" type="number" id="update_qty" value="{{$vals->qty_kirim}}"
                    class="form-control update_qty" placeholder="qty_kirim">
            </td>
            <td>
                <input name="keterangans[]" type="text" id="keterangan" value="{{$vals->keterangan}}"
                    class="form-control keterangans" placeholder="Note" readonly>
                <input name="keterangan[]" type="text" id="keterangan" value="{{$vals->keterangan}}"
                    class="form-control up_keterangans" placeholder="Note">
            </td>
        </tr>
    </tbody>
    @php } @endphp
</table>
<br>
<div class="text-right">
    <button type="submit" class="btn btn-primary do_cetaks btn-submit">Cetak DO<i class="far fa-save ml-2"></i></button>
</div>
{!! Form::close() !!}
@section('script')
<script src="{{ asset('ctrl/warehouse/wh-detail.js') }}" type="text/javascript"></script>
@endsection