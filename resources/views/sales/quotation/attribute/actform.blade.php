{!! Form::open(['method' => $method,'action'=>$action]) !!}
<div class="form-group row">
    <div class="col-lg-12">
        {!! Form::hidden('id_quo',$quo,['id'=>'id_quo','class'=>'form-control']) !!}
        {!! Form::textarea('activity_name','',['id'=>'activity_name','class'=>'form-control','placeholder'=>'Isikan keterangan tambahan anda']) !!}
    </div>
</div>
<div class="text-right">
    <button type="submit" class="btn btn-primary">Tambah<i class="far fa-save ml-2"></i></button>
</div>

{!! Form::close() !!}