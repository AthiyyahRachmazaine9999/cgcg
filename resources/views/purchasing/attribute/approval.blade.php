{!! Form::open(['method' => $method,'action'=>$action]) !!}
<div class="form-group row">
    <div class="col-lg-12">
        {!! Form::hidden('id_po',$idpo,['id'=>'id_po','class'=>'form-control']) !!}
        {!! Form::hidden('type',$type,['id'=>'type','class'=>'form-control']) !!}
        {!! Form::textarea('approval_note','',['id'=>'approval_note','class'=>'form-control','placeholder'=>'Isikan keterangan tambahan anda']) !!}
    </div>
</div>
<div class="text-right">
    @php $color = $type=='reject'? 'btn-danger' : 'btn-primary'; @endphp
    <button type="submit" class="btn {{$color}}">{{ucfirst($type)}}<i class="far fa-save ml-2"></i></button>
</div>

{!! Form::close() !!}