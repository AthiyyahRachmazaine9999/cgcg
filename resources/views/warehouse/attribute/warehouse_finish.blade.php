{!! Form::open(['method' => $method,'action'=>$action]) !!}
<div class="form-group row">
    {!! Form::hidden('id_address',$check->id_address,['id'=>'alamat','class'=>'form-control']) !!}
    {!! Form::hidden('type',$type,['id'=>'type','class'=>'form-control']) !!}
    <div class="col-lg-12">
        <label class="font-weight-bold"> Penerima</label>
        {!! Form::text('penerima',$check->penerima,['id'=>'name','class'=>'form-control','placeholder'=>'Masukan nama / perusahaan penerima']) !!}
    </div>
</div>
<div class="form-group row">
    <div class="col-lg-12">
        {!! Form::textarea('note',$check->note,['id'=>'note','class'=>'form-control','placeholder'=>'Isikan keterangan tambahan anda']) !!}
    </div>
</div>
<div class="text-right">
    <button type="submit" class="btn btn-primary">Selesai<i class="far fa-save ml-2"></i></button>
</div>

{!! Form::close() !!}