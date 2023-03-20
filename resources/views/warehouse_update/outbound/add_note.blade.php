{!! Form::open(['method' => $method,'action'=>$action]) !!}
<div class="form-group row">
    <div class="col-lg-12">
        {!! Form::hidden('id',$id_out,['id'=>'id_in','class'=>'form-control']) !!}
        {!! Form::textarea('activity','',['id'=>'activity','class'=>'form-control',
        'placeholder'=>'Keterangan ....']) !!}
    </div>
</div>
<div class="text-right">
    <button type="submit" class="btn btn-primary">Save<i class="far fa-save ml-2"></i></button>
</div>
{!! Form::close() !!}