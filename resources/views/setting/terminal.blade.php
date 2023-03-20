@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">Terminal Console</h5>
        </div>
        <div class="card-body">
            @if(session()->has('success'))
            <div class="alert alert-success alert-styled-left alert-dismissible mb-3">
                <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                {{ session()->get('success') }}
            </div>
            @endif
            Example command like <code>route:cache</code> and other artisan command. Please be carefull for this stuff
            {!! Form::open(['action' => $action, 'method' => $method, 'id' => 'm_form']) !!}
            <div class="form-group row mt-3">
                {!! Form::label('terminal', 'Terminal Command *', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!! Form::text('terminal','',['id'=>'terminal','class'=>'form-control','placeholder'=>'Enter terminal command','required']) !!}
                </div>
            </div>

            <div class="text-right">
                {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel', 'data-method' => 'setting/menu', 'type' => 'button','onclick'=>'cancel(this)']) !!}
                <button type="submit" class="btn btn-primary">Create Menu<i class="far fa-save ml-2"></i></button>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
    <!-- /basic layout -->
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/setting/form.js') }}" type="text/javascript"></script>
@endsection