@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">Create User Login</h5>
        </div>
        <div class="card-body">
            {!! Form::open(['action' => $action, 'method' => $method, 'id' => 'm_form']) !!}
            <div class="form-group row">
                {!! Form::label('user', 'Nama Karyawan *', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!! Form::select('id_emp',$getuser,null,['id'=>'id_emp','class'=>'form-control','placeholder'=>'Pilih Nama Karyawan','required']) !!}
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('password', 'Password *', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!! Form::text('password','',['id'=>'password','class'=>'form-control','placeholder'=>'Enter Password','required']) !!}
                </div>
            </div>
        </div>
        <div class="modal-footer">
            {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel', 'data-method' => 'setting/users', 'type' => 'button','onclick'=>'cancel(this)']) !!}
            <button type="submit" class="btn btn-primary">Create<i class="far fa-save ml-2"></i></button>
        </div>
        {!! Form::close() !!}
    </div>
</div>
<!-- /basic layout -->
@endsection
@section('script')
<script src="{{ asset('ctrl/role/user/form.js') }}" type="text/javascript"></script>
@endsection