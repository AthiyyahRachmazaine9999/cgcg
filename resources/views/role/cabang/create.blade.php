@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">Create Cabang</h5>
        </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>

        </div>
    @endif
    <form action="{{ route('cabang.store')}}" method="POST" >
        @csrf
        
            <div class="card-body">
            <div class="form-group row">
                 <label class= 'col-lg-3 col-form-label'>Nama Cabang</label>
                 <div class="col-lg-7">
                <input type="text" name="cabang_name" class="form-control" placeholder="Nama">
                </div>
            </div>
            <div class="form-group row">
                 <label class= 'col-lg-3 col-form-label'>Phone</label>
                 <div class="col-lg-7">
                <input type="text" name="cabang_phone" class="form-control" placeholder="Phone">
                </div>
            </div>
            <div class="form-group row">
                 <label class= 'col-lg-3 col-form-label'>Alamat Cabang</label>
                 <div class="col-lg-7">
                <input type="text" name="cabang_address" class="form-control" placeholder="Alamat">
                </div>
            </div>
            <div class="form-group row">
                <label class= 'col-lg-3 col-form-label'>Is Active</label>
               
                <input type="radio" name="is_active" value="Y" > YES
                
                <input type="radio" name="is_active" value="N"> NO
                </div>
            <!-- <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="customSwitch1" checked>
            <label class="custom-control-label" for="customSwitch1">Toggle this switch element</label>
            </div> -->
            <div class="text-right">
            {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel', 'data-method' => 'role/cabang', 'type' => 'button','onclick'=>'cancel(this)']) !!}
            <button type="submit" class="btn btn-primary">Create Cabang<i class="far fa-save ml-2"></i></button>
            </div>
        </div>
        </div>
        </div>
    </form>
@endsection
<!-- @section('script')
<script src="{{ asset('role/role_cabang-form.js') }}" type="text/javascript"></script>
@endsection -->