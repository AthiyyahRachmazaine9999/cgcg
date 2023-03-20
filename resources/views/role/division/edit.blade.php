@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">Update Division</h5>
        </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>

        </div>
    @endif
    <form action="{{ route('division.update', $division->id) }}" method="POST" >
        @csrf
        @method('PUT')
            <div class="card-body">
            <div class="form-group row">
                 <label class= 'col-lg-3 col-form-label'>Nama Divisi</label>
                 <div class="col-lg-7">
                <input type="text" name="div_name" value="{{ $division->div_name }}" class="form-control" >
                </div>
            </div>
            <div class="text-right">
            {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel', 'data-method' => 'role/division', 'type' => 'button','onclick'=>'cancel(this)']) !!}
            <button type="submit" class="btn btn-primary">Update Cabang<i class="far fa-save ml-2" onclick></i></button>
            </div>
        </div>
        </div>
    </div>

@endsection
<!-- @section('script')
<script src="{{ asset('ctrl/menu/form.js') }}" type="text/javascript"></script>
@endsection -->