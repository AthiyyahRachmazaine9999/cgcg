@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
            <h5 class="card-title">Code Accounting</h5>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                </div>
            </div>
        </div>


        <div class="page-wrapper">
            <div class="card-body">
                <input type="hidden" name="id" value="{{$data->id}}" placeholder="Enter Code" class="form-control">
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Code</label>
                    <div class="col-lg-7">
                        <div class="form-control">{{$data->code}}</div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Type Name</label>
                    <div class="col-lg-7">
                        <div class="form-control">{{$data->type_name}}</div>
                    </div>
                </div>
                <div class="text-right">
                    {!! Form::button('Back', ['class' => 'btn btn-light btn-cancel',
                    'data-method' => 'finance/code_accounting', 'type' =>
                    'button','onclick'=>'cancel(this)'])
                    !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/finance/code-list.js?v=').rand() }}" type="text/javascript"></script>
@endsection