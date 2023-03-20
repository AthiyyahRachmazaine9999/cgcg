@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">Update Brand</h5>
        </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>

        </div>
    @endif
    <form action="{{ route('brand.update', $brand->id) }}" method="POST" >
        @csrf
        @method('PUT')
                 {!! Form::hidden('id_live_brand',$brand->id_live_brand,['id'=>'id_live_brand','class'=>'form-control']) !!}
            <div class="card-body">
            <div class="form-group row">
                 <label class= 'col-lg-3 col-form-label'>Nama Brand</label>
                 <div class="col-lg-7">
                <input type="text" name="brand_name" value="{{ $brand->brand_name }}" class="form-control" >
                </div>
            </div>
            <div class="text-right">
            {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel', 'data-method' => 'product/content/brand', 'type' => 'button','onclick'=>'cancel(this)']) !!}
            <button type="submit" class="btn btn-primary">Update Brand<i class="far fa-save ml-2" onclick></i></button>
            </div>
        </div>
        </div>
    </div>

@endsection
<!-- @section('script')
<script src="{{ asset('ctrl/menu/form.js') }}" type="text/javascript"></script>
@endsection -->