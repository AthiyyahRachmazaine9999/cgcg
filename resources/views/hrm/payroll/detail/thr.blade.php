{!!Form::open(['action' => $action, 'method' => $method, 'id' => 'm_form']) !!}
{!!Form::hidden('id_emp',$idku,['id'=>'id_emp','class'=>'form-control']) !!}
{!!Form::hidden('month',$month,['id'=>'month','class'=>'form-control']) !!}
{!!Form::hidden('type',$type,['id'=>'type','class'=>'form-control']) !!}
@php

$basic_salary = explode('males', base64_decode($main->basic_salary))[0];
$ded_tax      = $getdata == null ? '': explode('males', base64_decode($getdata->ded_tax))[0];
$datathr      = $method== 'post' ? $basic_salary : explode('males', base64_decode($getdata->basic_salary))[0] ;
@endphp
<div class="form-group row">
    {!! Form::label('basic_salary', 'Nominal THR', ['class' => 'col-lg-3 col-form-label']) !!}
    <div class="col-lg-7">

        {!!
        Form::number('basic_salary',$datathr,['id'=>'basic_salary','class'=>'form-control',
        'placeholder'=>'Masukkan Nominal THR','required']) !!}

    </div>
</div>
<div class="form-group row">
    {!! Form::label('ded_tax', ' Deduction Tax', ['class' => 'col-lg-3 col-form-label']) !!}
    <div class="col-lg-7">
        {!!
        Form::number('ded_tax',$ded_tax,['id'=>'ded_tax','class'=>'form-control',
        'placeholder'=>'Masukkan Deduction Tax']) !!}

    </div>
</div>

<hr class="divider">
<div class="text-right">
    {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel',
    'data-method' => 'hrm/payroll', 'type' => 'button','onclick'=>'cancel(this)'])
    !!}
    <button type="submit" class="btn btn-primary">Save<i class="far fa-save ml-2"></i>
    </button>
    <button type="button" class="btn btn-danger" id="allowdownload" data-id_emp="{{$idku}}" data-date_hr="{{$month}}" onclick="CetakSlipGaji(this)"><i class="fas fa-file-pdf mr-2"></i>Download
    </button>
</div>
{!! Form::close() !!}