@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">Create Category</h5>
        </div>
        <div class="card-body">
            {!! Form::open(['action' => $action, 'method' => $method, 'id' => 'm_form']) !!}
           
            <div class="form-group row">
                {!! Form::label('parent_id', 'Level One', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!! Form::select('parent_id', $name, null,['id' => 'parent_id', 'class' => 'form-control form-control-select2', 'placeholder' => 'Parent category (None)']) !!}
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('child_id', 'Level Two', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!! Form::select('child_id', $child, null,['id' => 'child_id', 'class' => 'form-control form-control-select2', 'placeholder' => 'Middle category (None)']) !!}
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('cat_name', '+ Category Name *', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!! Form::text('cat_name','',['id'=>'cat_name','class'=>'form-control','placeholder'=>'Enter category name','required']) !!}
                </div>
            </div>
            <div class="text-right">
                {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel', 'data-method' => 'product/content/category', 'type' => 'button','onclick'=>'cancel(this)']) !!}
                <button type="submit" class="btn btn-primary">Create Category<i class="far fa-save ml-2"></i></button>
            </div>
            {!! Form::close() !!}
        </div>
        </div>
            </div>
        </div>
    </div>
</div>
@endsection
