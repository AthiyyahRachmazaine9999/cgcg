@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">Update Config</h5>
        </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>

        </div>
    @endif
    <form action="{{ route('config.update', $config->id) }}" method="POST" >
        @csrf
        @method('PUT')
            <div class="card-body">
            <div class="form-group row">
                 <label class= 'col-lg-3 col-form-label'>Config Name</label>
                 <div class="col-lg-7">
                <input type="text" name="config_name" value="{{ $config->config_name }}" class="form-control" >
                </div>
            </div>
            <div class="card-body">
            <div class="form-group row">
                 <label class= 'col-lg-3 col-form-label'>Config value</label>
                 <div class="col-lg-7">
                <input type="text" name="config_value" value="{{ $config->config_value }}" class="form-control" >
                </div>
            </div>
            <div class="text-right">
            {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel', 'data-method' => 'setting/config', 'type' => 'button','onclick'=>'cancel(this)']) !!}
            <button type="submit" class="btn btn-primary">Update Config<i class="far fa-save ml-2" onclick></i></button>
            </div>
        </div>
        </div>
    </div>
    </div>
@endsection
<!-- @section('script')
<script src="{{ asset('ctrl/menu/form.js') }}" type="text/javascript"></script>
@endsection -->