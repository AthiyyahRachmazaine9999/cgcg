{!! Form::open(['method' => $method,'action'=>$action, 'files' => 'true']) !!}
<div class="form-group row">
    {!! Form::hidden('id_address',$check->id_address,['id'=>'alamat','class'=>'form-control']) !!}
    {!! Form::hidden('type',$type,['id'=>'type','class'=>'form-control']) !!}
    {!! Form::hidden('idwo',$idwo,['id'=>'type','class'=>'form-control']) !!}
    {!! Form::hidden('id_resi',$id_resi,['id'=>'typess','class'=>'form-control']) !!}
    <div class="col-lg-12">
        <input type="file" name="files" class="form-control">
    </div>
</div>
<br>
<div class="text-right">
    <button type="submit" class="btn btn-primary">Simpan<i class="far fa-save ml-2"></i></button>
</div>
{!! Form::close() !!}