{!! Form::open(['method' => $method,'action'=>$action]) !!}
<div class="form-group row">
    <div class="col-lg-12">
        {!! Form::hidden('id',$visit->id,['id'=>'id','class'=>'form-control']) !!}
        {!! Form::textarea('advice','',['id'=>'advice','class'=>'form-control',
        'placeholder'=>'Enter Advice/Suggestion..... ']) !!}
    </div>
</div>
<div class="text-right">
    <button type="submit" class="btn btn-primary">Save<i class="far fa-save ml-2"></i></button>
</div>

{!! Form::close() !!}