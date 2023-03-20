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
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {!! Form::open(['action' => $action, 'method' => $method, 'id' => 'm_form', 'files' => true ]) !!}
            <div class="card-body">
                @csrf
                <input type="hidden" name="id" value="{{$data->id}}" placeholder="Enter Code" class="form-control">
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Code</label>
                    <div class="col-lg-7">
                        @if(count($petty)===0)
                        <input type="text" name="code" value="{{$data->code}}" placeholder="Enter Code"
                            class="form-control">
                        @else
                        <input type="text" name="code" value="{{$data->code}}" placeholder="Enter Code"
                            class="form-control" readonly>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Type Name</label>
                    <div class="col-lg-7">
                        <textarea type="text" name="type" class="form-control"
                            placeholder="Enter Type Name">{{$data->type_name}}</textarea>
                    </div>
                </div>
                <div class="text-right">
                    {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel',
                    'data-method' => 'finance/code_accounting', 'type' =>
                    'button','onclick'=>'cancel(this)'])
                    !!}
                    <button type="submit" class="btn btn-primary">Update<i class="far fa-save ml-2"></i>
                    </button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/finance/code-list.js?v=').rand() }}" type="text/javascript"></script>
@endsection