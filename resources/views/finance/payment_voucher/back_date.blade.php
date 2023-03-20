{!! Form::open(['method' => $method,'action'=>$action]) !!}
{!! Form::hidden('id_pay',$id_pay,['id'=>'no_do','class'=>'form-control']) !!}
<br>
<div class="form-group row">
    <div class="col-lg-12">
        <label class="font-weight-bold">Tanggal Payment</label>
        <input name="tgl_cetak" type="text" id="tgl_pays"
            value="{{$tgl==null?\Carbon\Carbon::now()->format('Y/m/d') : $tgl}}" class="form-control"
            placeholder="Masukkan Tanggal">
    </div>
</div>
<br>
<div class="text-right">
    <button type="submit" class="btn btn-primary">Cetak Payment<i class="far fa-save ml-2"></i></button>
</div>
{!! Form::close() !!}
@section('script')
<script src="{{ asset('ctrl/warehouses/wh-detail.js') }}" type="text/javascript"></script>
@endsection