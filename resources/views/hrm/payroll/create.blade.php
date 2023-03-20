@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">Update Salary Data</h5>
        </div>
        <div class="card-body">
            {!! Form::open(['action' => $action, 'method' => $method, 'id' => 'm_form']) !!}
            {!!Form::hidden('id_emp',$getdata->idku,['id'=>'id_emp','class'=>'form-control']) !!}
            <div class="form-group row">
                <label class='col-lg-3 col-form-label'>Status Karyawan</label>
                <div class="col-lg-7">
                    <div class="form-control">
                        {{$getdata->emp_status}}
                    </div>
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('emp_name', 'Nama', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">
                        {{$getdata->emp_name}}
                    </div>
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('division_name', 'Division', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">
                        {{$getdata->division_name}}
                    </div>
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('position', 'Position', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">
                        {{$getdata->position}}
                    </div>
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('bank_acc', 'BCA Rek', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    <div class="form-control">
                        {{$getdata->bank_acc}}
                    </div>
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('basic_salary', 'Basic Salary', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!!
                    Form::number('basic_salary','',['id'=>'basic_salary','class'=>'form-control',
                    'placeholder'=>'Masukkan Basic Salary','required','onchange'=>"HitungBPJS();",'onkeyup'=>"HitungBPJS();"]) !!}

                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('allowance', 'Allowance', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!!
                    Form::number('allowance','',['id'=>'allowance','class'=>'form-control',
                    'placeholder'=>'Masukkan Allowance','required','onchange'=>"HitungBPJS();",'onkeyup'=>"HitungBPJS();"]) !!}

                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('bpjs', 'BPJS', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!!
                    Form::number('bpjs','',['id'=>'bpjs','class'=>'form-control',
                    'placeholder'=>'Masukkan BPJS','required']) !!}

                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('pension', 'Pension', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!!
                    Form::number('pension','',['id'=>'pension','class'=>'form-control',
                    'placeholder'=>'Masukkan Pension','required']) !!}

                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('tax', 'Tax', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!!
                    Form::number('tax','',['id'=>'tax','class'=>'form-control',
                    'placeholder'=>'Masukkan Tax','required']) !!}

                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('gross', 'Gross Salary', ['class' => 'col-lg-3 col-form-label text-danger font-weight-bold']) !!}
                <div class="col-lg-7">
                    <div class="form-control" id="gross">
                    </div>

                </div>
            </div>
            <div class="text-right">
                {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel',
                'data-method' => 'hrm/payroll', 'type' => 'button','onclick'=>'cancel(this)'])
                !!}
                <button type="submit" class="btn btn-primary">Save<i class="far fa-save ml-2"></i>
                </button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/hr/payroll-list.js?v=').rand() }}" type=" text/javascript"></script>
@endsection