@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">Create Role Menu</h5>
        </div>
        <div class="card-body">
            {!! Form::open(['action' => $action, 'method' => $method, 'id' => 'm_form']) !!}
            <div class="form-group row">
                {!! Form::label('title', 'User *', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!! Form::select('user',$getuser,null,['id'=>'user_id','class'=>'form-control','placeholder'=>'Enter menu name','required']) !!}
                </div>
            </div>
            <div class="form-group row">
                <button type="button" id="checkall" class="btn btn-outline bg-success-400 text-success-400 border-success-400 legitRipple"><i class="far fa-check-circle mr-2"></i> Check All</button>
                <button type="button" id="uncheck" class="btn btn-outline ml-1 bg-danger-400 text-danger-400 border-danger-400 legitRipple"><i class="far fa-window-close mr-2"></i> Uncheck All</button>
            </div>
            {!! Form::hidden('datamenu','',['id'=>'getdata','class'=>'form-control','placeholder'=>'Enter menu name','required']) !!}
            <div class="tree-hie card card-body border-left-danger border-left-2 shadow-0 rounded-left-0">
            @php echo $gettree; @endphp
            </div>

            <div class="text-right">
                {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel', 'data-method' => 'setting/menu', 'type' => 'button','onclick'=>'cancel(this)']) !!}
                <button type="submit" class="btn btn-primary">Create Menu<i class="far fa-save ml-2"></i></button>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>
<!-- /basic layout -->
@endsection
@section('script')
<script src="{{ asset('ctrl/role/menu/form.js') }}" type="text/javascript"></script>
@endsection