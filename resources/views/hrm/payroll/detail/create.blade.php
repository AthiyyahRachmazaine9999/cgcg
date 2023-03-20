{!! Form::open(['action' => $action, 'method' => $method, 'id' => 'm_form']) !!}
{!!Form::hidden('id_emp',$idku,['id'=>'id_emp','class'=>'form-control']) !!}
{!!Form::hidden('month',$month,['id'=>'month','class'=>'form-control']) !!}
@php
$basic_salary   = explode('males', base64_decode($main->basic_salary))[0];
$allowance      = explode('males', base64_decode($main->allowance))[0];
$decode_pension = explode('males', base64_decode($main->pension))[0];
$decode_bpjs    = explode('males', base64_decode($main->bpjs))[0];
@endphp
<div class="form-group row">
    {!! Form::label('basic_salary', 'Basic Salary', ['class' => 'col-lg-3 col-form-label']) !!}
    <div class="col-lg-7">

        {!!
        Form::number('basic_salary',$basic_salary,['id'=>'basic_salary','class'=>'form-control',
        'placeholder'=>'Masukkan Basic Salary','required','onchange'=>"HitungBPJS();",'onkeyup'=>"HitungBPJS();"]) !!}

    </div>
</div>
<div class="form-group row">
    {!! Form::label('allowance', 'Allowance', ['class' => 'col-lg-3 col-form-label']) !!}
    <div class="col-lg-7">
        {!!
        Form::number('allowance',$allowance,['id'=>'allowance','class'=>'form-control',
        'placeholder'=>'Masukkan Allowance','required','onchange'=>"HitungTotal();",'onkeyup'=>"HitungTotal();"]) !!}

    </div>
</div>
<hr class="divider">
<div class="form-group row">
    {!! Form::label('add_overtime', 'Overtime', ['class' => 'col-lg-3 col-form-label']) !!}
    <div class="col-lg-7">
        {!!
        Form::number('add_overtime','',['id'=>'add_overtime','class'=>'form-control',
        'placeholder'=>'Masukkan Overtime Jika ada','onchange'=>"TotalDeduction();",'onkeyup'=>"TotalDeduction();"]) !!}

    </div>
</div>
<div class="form-group row">
    {!! Form::label('ded_other', 'Deduction Other', ['class' => 'col-lg-3 col-form-label']) !!}
    <div class="col-lg-7">
        {!!
        Form::number('ded_other','',['id'=>'ded_other','class'=>'form-control',
        'placeholder'=>'Masukkan Deduction Other','onchange'=>"TotalDeduction();",'onkeyup'=>"TotalDeduction();"]) !!}

    </div>
</div>
<div class="form-group row">
    {!! Form::label('ded_bpjs', 'Deduction BPJS', ['class' => 'col-lg-3 col-form-label']) !!}
    <div class="col-lg-7">
        {!!
        Form::number('ded_bpjs',$decode_bpjs,['id'=>'ded_bpjs','class'=>'form-control',
        'placeholder'=>'Masukkan Deduction BPJS','onchange'=>"TotalDeduction();",'onkeyup'=>"TotalDeduction();"]) !!}

    </div>
</div>
<div class="form-group row">
    {!! Form::label('ded_pension', 'Deduction Pension', ['class' => 'col-lg-3 col-form-label']) !!}
    <div class="col-lg-7">
        {!!
        Form::number('ded_pension',$decode_pension,['id'=>'ded_pension','class'=>'form-control',
        'placeholder'=>'Masukkan Deduction Pension','onchange'=>"TotalDeduction();",'onkeyup'=>"TotalDeduction();"]) !!}

    </div>
</div>
<div class="form-group row">
    {!! Form::label('ded_tax', ' Deduction Tax', ['class' => 'col-lg-3 col-form-label']) !!}
    <div class="col-lg-7">
        {!!
        Form::number('ded_tax','',['id'=>'ded_tax','class'=>'form-control',
        'placeholder'=>'Masukkan Deduction Tax','onchange'=>"TotalDeduction();",'onkeyup'=>"TotalDeduction();"]) !!}

    </div>
</div>
<div class="form-group row">
    {!! Form::label('ded_insurance', 'Deduction Insurance', ['class' => 'col-lg-3 col-form-label']) !!}
    <div class="col-lg-7">
        {!!
        Form::number('ded_insurance','',['id'=>'ded_insurance','class'=>'form-control',
        'placeholder'=>'Masukkan Deduction Insurance','onchange'=>"TotalDeduction();",'onkeyup'=>"TotalDeduction();"]) !!}

    </div>
</div>
<div class="form-group row">
    {!! Form::label('ded_loan', 'Deduction Loan', ['class' => 'col-lg-3 col-form-label']) !!}
    <div class="col-lg-7">
        {!!
        Form::number('ded_loan','',['id'=>'ded_loan','class'=>'form-control',
        'placeholder'=>'Masukkan Deduction Loan','onchange'=>"TotalDeduction();",'onkeyup'=>"TotalDeduction();"]) !!}

    </div>
</div>
<div class="form-group row">
    {!! Form::label('gross', 'Total Deduction', ['class' => 'col-lg-3 col-form-label text-danger font-weight-bold']) !!}
    <div class="col-lg-7">
        <div class="form-control text-danger font-weight-bold" id="dedgross">
            {{number_format($decode_bpjs+$decode_pension)}}
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