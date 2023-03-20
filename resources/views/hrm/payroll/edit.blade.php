@extends('layouts.head')
@section('content')
<div class="content">
    <ul class="nav nav-tabs nav-tabs-solid nav-justified rounded border-0">
        <li class="nav-item"><a href="#main" class="nav-link gaji-master active rounded-left " data-toggle="tab"><i class="fas fa-align-left mr-2"></i>Master Data</a></li>
        <li class="nav-item"><a href="#detail" class="nav-link gaji-detail" data-toggle="tab"><i class="far fa-address-card mr-2"></i>Detail</a></li>
    </ul>
    <!-- Basic layout-->
    <div class="card">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="main">
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
                            @php
                            $decode_salary = base64_decode($getdata->basic_salary);
                            list($basic_salary, $keys_basic_salary) = explode('males', $decode_salary);
                            @endphp

                            {!!
                            Form::number('basic_salary',$basic_salary,['id'=>'basic_salary','class'=>'form-control',
                            'placeholder'=>'Masukkan Basic Salary','required','onchange'=>"HitungBPJS();",'onkeyup'=>"HitungBPJS();"]) !!}

                        </div>
                    </div>
                    <div class="form-group row">
                        {!! Form::label('allowance', 'Allowance', ['class' => 'col-lg-3 col-form-label']) !!}
                        <div class="col-lg-7">

                            @php
                            $decode_allowance = base64_decode($getdata->allowance);
                            list($allowance, $key_allowance) = explode('males', $decode_allowance);
                            @endphp

                            {!!
                            Form::number('allowance',$allowance,['id'=>'allowance','class'=>'form-control',
                            'placeholder'=>'Masukkan Allowance','required','onchange'=>"HitungTotal();",'onkeyup'=>"HitungTotal();"]) !!}

                        </div>
                    </div>
                    <div class="form-group row">
                        {!! Form::label('bpjs', 'BPJS', ['class' => 'col-lg-3 col-form-label']) !!}
                        <div class="col-lg-7">

                            @php
                            $decode_bpjs = base64_decode($getdata->bpjs);
                            list($bpjs, $key_bpjs) = explode('males', $decode_bpjs);
                            @endphp

                            {!!
                            Form::number('bpjs',$bpjs,['id'=>'bpjs','class'=>'form-control',
                            'placeholder'=>'Masukkan BPJS','required','onchange'=>"HitungTotal();",'onkeyup'=>"HitungTotal();"]) !!}

                        </div>
                    </div>
                    <div class="form-group row">
                        {!! Form::label('pension', 'Pension', ['class' => 'col-lg-3 col-form-label']) !!}
                        <div class="col-lg-7">
                            @php
                            $decode_pension = base64_decode($getdata->pension);
                            list($pension, $key_pension) = explode('males', $decode_pension);
                            @endphp

                            {!!
                            Form::number('pension',$pension,['id'=>'pension','class'=>'form-control',
                            'placeholder'=>'Masukkan Pension','required','onchange'=>"HitungTotal();",'onkeyup'=>"HitungTotal();"]) !!}

                        </div>
                    </div>
                    <div class="form-group row">
                        {!! Form::label('gross', 'Gross Salary', ['class' => 'col-lg-3 col-form-label text-danger font-weight-bold']) !!}
                        <div class="col-lg-7">
                            <div class="form-control text-danger font-weight-bold" id="gross">
                                @php
                                echo number_format($basic_salary+$allowance+$bpjs+$pension);
                                @endphp
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
            <div class="tab-pane fade" id="detail">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">Monthly Detail</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info alert-styled-left alert-dismissible">
                        <span class="font-weight-semibold">Hi there!</span> tab ini untuk detail gaji bulanan, angka yang autofill merupakan database utama, silahkan ganti jika ada perubahaan pada bulan tersebut.
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Pilih Jenis</label>
                        <div class="col-lg-7">
                            <select class="form-control" name="type" id="type">
                                <option value="normal">Normal</option>
                                <option value="thr">THR</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-lg-3 col-form-label'>Pilih Periode Bulan</label>
                        <div class="col-lg-7">
                            <input type="date" id="date_edit" data-id_emp="{{$getdata->idku}}" onchange="CheckMonth(this)" class="form-control" data-column="5" name="start" placeholder="Enter Date" min="{{$getdata->tgl_bergabung}}" max="{!!\Carbon\Carbon::parse(\Carbon\Carbon::now())->endOfMonth()->toDateString()!!}">
                        </div>
                    </div>
                    <div id="resultcheck" class="pt-3"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/hr/payroll-list.js?v=').rand() }}" type=" text/javascript"></script>
@endsection