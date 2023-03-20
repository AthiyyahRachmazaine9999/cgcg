{!! Form::open(['action' => $action, 'method' => $method, 'id' => 'm_out', 'class' => 'check_pengiriman']) !!}
<br>
<br>
{!! Form::hidden('id_quo',$request->id_quo,['id'=>'id_quo','class'=>'form-control'])
!!}
{!! Form::hidden('resi',$request->resi,['id'=>'resi','class'=>'form-control'])
!!}
{!! Form::hidden('id_wo',$request->id_wo,['id'=>'id_wh_out','class'=>'form-control'])
!!}
{!! Form::hidden('status_kirim',$status_kirim,['id'=>'id_wh_out','class'=>'form-control'])
!!}

<div class="form-group row">
    <label class='col-lg-3 col-form-label'>Type DO*</label>
    <div class="col-lg-7">
        {!! Form::select('type_do',array('rekap' => 'Rekap', 'lainnya' =>
        'Normal'), '',['id' => 'type_leave', 'class' => 'form-control form-control-select2 type_do',
        'placeholder' =>'*']) !!}
    </div>
</div>
<br>
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
        {!! Form::select('address', $address, $caddr,['id' => 'alamats', 'class' => 'form-control
        form-control-select2 alamats','required']) !!}
    </div>
</div>
<br>
<table class="table table-bordered table-striped table-hover">
    <tbody>
        <tr>
            <th>Barang</th>
            <th>Qty Kirim</th>
            <th>Note</th>
        </tr>
        @php
        $rek = $request->siapkirim;
        foreach ($rek as $req => $s) {
        $reqs = array_search($s, $request->id_product);
        @endphp
        <tr>
            <td>
                @php
                if ($request->id_quo==0) { @endphp
                <input name="id_product[]" type="hidden" value="{{$request->id_product[$reqs]}}" class="form-control">
                {!!getProductPo($request->id_product[$reqs])->name!!}
                @php } else {
                $check = $request->id_product[$reqs];
                @endphp
                <input name="id_product[]" type="hidden" value="{{$request->id_product[$reqs]}}" class="form-control">
                {!!getProductDetail($check)->name!!}
                @php } @endphp
            </td>
            <td>
                <input name="qty_kirim[]" type="hidden" value="{{$request->qty_kirim[$reqs]}}" class="form-control">
                {{$request->qty_kirim[$reqs]}}
            </td>
            <td>
                <input name="note[]" type="hidden" value="{{$request->kirim_note[$reqs]}}" class="form-control">
                {{$request->kirim_note[$reqs]}}
            </td>
        </tr>
        @php } @endphp
    </tbody>
</table>
<br>
<br>
<div class="text-right" style="padding-right:20px">
    <button type="submit" class="btn btn-primary btn-labeled btn-labeled-left legitRipple saveKirim">
        <b><i class="fas fa-truck"></i></b> Cetak DO
    </button>
</div>
<br>
{!! Form::close() !!}
@section('script')
<script src="{{ asset('ctrl/warehouses/wh-detail.js') }}" type="text/javascript"></script>
@endsection