{!! Form::open(['action' => $action, 'method' => $method, 'id' => 'm_form']) !!}
{!!Form::hidden('id_emp',$idku,['id'=>'id_emp','class'=>'form-control']) !!}
{!!Form::hidden('month',$month,['id'=>'month','class'=>'form-control']) !!}
@php
$decode_basic_salary = explode('males', base64_decode($main->basic_salary))[0];
$decode_allowance    = explode('males', base64_decode($main->allowance))[0];
$decode_pension      = explode('males', base64_decode($main->pension))[0];
$decode_bpjs         = explode('males', base64_decode($main->bpjs))[0];

$basic_salary  = explode('males', base64_decode($getdata->basic_salary))[0];
$allowance     = explode('males', base64_decode($getdata->allowance))[0];
$overtime      = explode('males', base64_decode($getdata->overtime))[0];
$ded_other     = explode('males', base64_decode($getdata->ded_other))[0];
$ded_tax       = explode('males', base64_decode($getdata->ded_tax))[0];
$ded_loan      = explode('males', base64_decode($getdata->ded_loan))[0];
$ded_insurance = explode('males', base64_decode($getdata->ded_insurance))[0];
$ded_bpjs      = explode('males', base64_decode($getdata->ded_bpjs))[0];
$ded_pension   = explode('males', base64_decode($getdata->ded_pension))[0];

$use_bpjs    = $ded_bpjs  == $decode_bpjs ? $decode_bpjs : $ded_bpjs;
$use_pension = $ded_pension  == $decode_pension ? $decode_pension : $ded_pension;

$use_basic_salary = $basic_salary == $decode_basic_salary ? $decode_basic_salary : $basic_salary;
$use_allowance    = $allowance == $decode_allowance ? $decode_allowance : $allowance;

@endphp
<div class="form-group row">
    {!! Form::label('basic_salary', 'Basic Salary', ['class' => 'col-lg-3 col-form-label']) !!}
    <div class="col-lg-7">

        {!!
        Form::number('basic_salary',$use_basic_salary,['id'=>'basic_salary','class'=>'form-control',
        'placeholder'=>'Masukkan Basic Salary','required','onchange'=>"HitungBPJS();",'onkeyup'=>"HitungBPJS();"]) !!}

    </div>
</div>
<div class="form-group row">
    {!! Form::label('allowance', 'Allowance', ['class' => 'col-lg-3 col-form-label']) !!}
    <div class="col-lg-7">
        {!!
        Form::number('allowance',$use_allowance,['id'=>'allowance','class'=>'form-control',
        'placeholder'=>'Masukkan Allowance','required','onchange'=>"HitungTotal();",'onkeyup'=>"HitungTotal();"]) !!}

    </div>
</div>
<hr class="divider">
<div class="form-group row">
    {!! Form::label('add_overtime', 'Overtime', ['class' => 'col-lg-3 col-form-label']) !!}
    <div class="col-lg-7">
        {!!
        Form::number('overtime',$overtime,['id'=>'add_overtime','class'=>'form-control',
        'placeholder'=>'Masukkan Overtime Jika ada']) !!}

    </div>
</div>
<div class="form-group row">
    {!! Form::label('ded_other', 'Deduction Other', ['class' => 'col-lg-3 col-form-label']) !!}
    <div class="col-lg-7">
        {!!
        Form::number('ded_other',$ded_other,['id'=>'ded_other','class'=>'form-control',
        'placeholder'=>'Masukkan Deduction Other','onchange'=>"TotalDeduction();",'onkeyup'=>"TotalDeduction();"]) !!}

    </div>
</div>
<div class="form-group row">
    {!! Form::label('ded_bpjs', 'Deduction BPJS', ['class' => 'col-lg-3 col-form-label']) !!}
    <div class="col-lg-7">
        {!!
        Form::number('ded_bpjs',$use_bpjs,['id'=>'ded_bpjs','class'=>'form-control',
        'placeholder'=>'Masukkan Deduction BPJS','onchange'=>"TotalDeduction();",'onkeyup'=>"TotalDeduction();"]) !!}

    </div>
</div>
<div class="form-group row">
    {!! Form::label('ded_pension', 'Deduction Pension', ['class' => 'col-lg-3 col-form-label']) !!}
    <div class="col-lg-7">
        {!!
        Form::number('ded_pension',$use_pension,['id'=>'ded_pension','class'=>'form-control',
        'placeholder'=>'Masukkan Deduction Pension','onchange'=>"TotalDeduction();",'onkeyup'=>"TotalDeduction();"]) !!}

    </div>
</div>
<div class="form-group row">
    {!! Form::label('ded_tax', ' Deduction Tax', ['class' => 'col-lg-3 col-form-label']) !!}
    <div class="col-lg-7">
        {!!
        Form::number('ded_tax',$ded_tax,['id'=>'ded_tax','class'=>'form-control',
        'placeholder'=>'Masukkan Deduction Tax','onchange'=>"TotalDeduction();",'onkeyup'=>"TotalDeduction();"]) !!}

    </div>
</div>
<div class="form-group row">
    {!! Form::label('ded_insurance', 'Deduction Insurance', ['class' => 'col-lg-3 col-form-label']) !!}
    <div class="col-lg-7">
        {!!
        Form::number('ded_insurance',$ded_insurance,['id'=>'ded_insurance','class'=>'form-control',
        'placeholder'=>'Masukkan Deduction Insurance','onchange'=>"TotalDeduction();",'onkeyup'=>"TotalDeduction();"]) !!}

    </div>
</div>
<div class="form-group row">
    {!! Form::label('ded_loan', 'Deduction Loan', ['class' => 'col-lg-3 col-form-label']) !!}
    <div class="col-lg-7">
        {!!
        Form::number('ded_loan',$ded_loan,['id'=>'ded_loan','class'=>'form-control',
        'placeholder'=>'Masukkan Deduction Loan','onchange'=>"TotalDeduction();",'onkeyup'=>"TotalDeduction();"]) !!}

    </div>
</div>
<div class="form-group row">
    {!! Form::label('gross', 'Total Deduction', ['class' => 'col-lg-3 col-form-label text-danger font-weight-bold']) !!}
    <div class="col-lg-7">
        <div class="form-control text-danger font-weight-bold" id="dedgross">
            {{number_format($ded_other+$use_bpjs+$use_pension+$ded_loan+$ded_tax+(float)$ded_insurance)}}
        </div>

    </div>
</div>
<div class="text-right">
    {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel',
    'data-method' => 'hrm/payroll', 'type' => 'button','onclick'=>'cancel(this)'])
    !!}
    <button type="submit" class="btn btn-primary">Save<i class="far fa-save ml-2"></i>
    </button>
    <button type="button" class="btn btn-danger" id="allowdownload" data-id_emp="{{$idku}}" data-date_hr="{{$month}}" onclick="CetakSlipGaji(this)" ><i class="fas fa-file-pdf mr-2"></i>Download
    </button>
</div>
{!! Form::close() !!}