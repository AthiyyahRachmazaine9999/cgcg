@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">Create Config</h5>
        </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>

        </div>
    @endif
    <form action="{{ route('config.store')}}" method="POST" >
        @csrf
        
            <div class="card-body">
            <div class="form-group row">
                 <label class= 'col-lg-3 col-form-label'>Config Name</label>
                 <div class="col-lg-7">
                <input type="text" name="config_name" class="form-control">
                </div>
            </div>
           
            <div class="form-group row">
                 <label class= 'col-lg-3 col-form-label'>Config value</label>
                 <div class="col-lg-7">
                <input type="text" name="config_value" class="form-control" >
                </div>
            </div>
            <div class="text-right">
            {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel', 'data-method' => 'setting/config', 'type' => 'button','onclick'=>'cancel(this)']) !!}
            <button type="submit" class="btn btn-primary">Create Config<i class="far fa-save ml-2"></i></button>
            </div>
        </div>
        </div>
</div>

    </form>
@endsection
