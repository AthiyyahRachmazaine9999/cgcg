@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header bg-light text-info-800 border-bottom-success header-elements-inline">
            <h5 class="card-title">Details</h5>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                </div>
            </div>
        </div>
        <br>
        <div class="card-body">
            <div class="form-group row">
                {!! Form::label('nama_emp', 'Date', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{\Carbon\Carbon::parse($dtl->date)->format('d F Y')}}</div>
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('nama_emp', 'Code', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{CodeType($dtl->code_id)->code." - ".$dtl->remarks}}</div>
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('nama_emp', 'Description', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <textarea class="form-control" readonly>{{$dtl->description}}</textarea>
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('year', 'PIC', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{$dtl->pic}}</div>
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('year', 'Nominal', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">{{number_format($dtl->nominal)}}</div>
                </div>
            </div>
            <br>
            <div class="text-right">
                {!! Form::button('Cancel', ['class' => 'btn btn-light btn-cancel', 'data-method'
                =>'finance/pettycash/'.$dtl->month.'-'.$dtl->year.'/show', 'type' =>
                'button','onclick'=>'cancel(this)']) !!}
            </div>
        </div>
    </div>
</div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/upload/upload_file.js?v=').rand() }}" type="text/javascript"></script>
@endsection