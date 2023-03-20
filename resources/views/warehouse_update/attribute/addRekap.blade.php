{!! Form::open(['action' => $action, 'method' => $method, 'class' => 'inirekap_pengiriman']) !!}
<br>
<br>
<span class="text-danger"><b>Ini Halaman Rekap</b></span>
<br><br>
{!! Form::hidden('id_quo',$request->id_quo,['id'=>'id_quo','class'=>'form-control'])
!!}
{!! Form::hidden('resi',$request->resi,['id'=>'resi','class'=>'form-control'])
!!}
{!! Form::hidden('id_wo',$request->id_wo,['id'=>'id_wh_out','class'=>'form-control'])
!!}
{!! Form::hidden('status_kirim',$status_kirim,['id'=>'id_wh_out','class'=>'form-control'])
!!}

<div class="form-group row">
    <label class='col-lg-3 col-form-label'>Tanggal Kirim*</label>
    <div class="col-lg-7">
        <input type="text" name="tgl_kirim" class="form-control tgl_kirim"
            value="{{\Carbon\Carbon::now()->format('d-m-Y')}}" placeholder="Masukkan Tanggal Kirim" required>
    </div>
</div>
<br>
<div class="form-group row">
    <label class='col-lg-3 col-form-label'>Nama Penerima*</label>
    <div class="col-lg-7">
        <input type="text" name="nama_penerima" class="form-control" placeholder="Masukkan Nama Penerima" required>
    </div>
</div>
<br>
<div class="form-group row">
    <label class='col-lg-3 col-form-label'>Alamat Kirim*</label>
    <div class="col-lg-7">
        {!! Form::select('address', $address, '',['id' => 'alamats', 'class' => 'form-control
        form-control-select2 alamats','required']) !!}
    </div>
</div>
<br>
<table class="table table-bordered table-striped table-hover">
    <tbody>
        <tr>
            <th>Barang</th>
            <th>Qty Kirim</th>
        </tr>
        @php
        $rek = $request->siapkirim;
        foreach ($wo_dtl as $req) {
        @endphp
        <tr>
            <td>
                @php
                if ($request->id_quo==0) { @endphp
                <input name="id_product[]" type="hidden" value="{{$req->sku}}" class="form-control">
                {!!getProductPo($req->sku)!!}
                @php } else {
                $check = $req->sku;
                @endphp
                <input name="id_product[]" type="hidden" value="{{$req->sku}}" class="form-control">
                {!!getProductDetail($check)->name!!}
                @php } @endphp
            </td>
            <td>
                <input name="qty_kirim[]" type="hidden" value="{{$req->count}}" class="form-control">
                {{$req->count}}
            </td>
        </tr>
        @php } @endphp
    </tbody>
</table>
<br>
<br>
<div class="text-right" style="padding-right:20px">
    <button type="submit" class="btn btn-primary btn-labeled btn-labeled-left legitRipple rekapKirim">
        <b><i class="fas fa-truck"></i></b> Cetak DO
    </button>
</div>
<br>
{!! Form::close() !!}
@section('script')
<script src="{{ asset('ctrl/warehouses/wh-detail.js') }}" type="text/javascript"></script>
@endsection