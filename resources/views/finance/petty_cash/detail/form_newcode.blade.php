{!! Form::open(['action' => $action, 'method' => $method, 'id' => 'm_form']) !!}
<div class="form-group row">
    {!! Form::label('codes', 'Code*', ['class' => 'col-lg-3 col-form-label']) !!}
    <div class="col-lg-7">
        {!! Form::text('code_id','',['id'=>'Code','class'=>'form-control','placeholder'=>'Enter Code']) !!}
    </div>
</div>

<div class="form-group row">
    {!! Form::label('phone', 'Type Name / Note', ['class' => 'col-lg-3 col-form-label']) !!}
    <div class="col-lg-7">
        {!! Form::text('type_name','',['id'=>'type_name','class'=>'form-control',
        'placeholder'=>'Enter Type Name']) !!}
    </div>
</div>


<div class="text-right">
    {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel', 'data-method' => 'setting/menu', 'type' =>
    'button','onclick'=>'cancel(this)']) !!}
    <button type="submit" class="btn btn-primary">Create<i class="far fa-save ml-2"></i></button>
</div>

{!! Form::close() !!}
<!-- /basic layout -->