@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">Update Overtime Request</h5>
        </div>
        <div class="card-body">
            {!! Form::open(['action' => $action, 'method' => $method, 'id' => 'm_form']) !!}
            <div class="form-group row">
                {!! Form::label('employee_id', 'Nama *', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!! Form::select('employee_id', $employee_id, $getdata->employee_id,['id' => 'employee_id', 'class'
                    => 'form-control form-control-select2', 'placeholder' => '*']) !!}
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('division_id', 'Division', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!! Form::select('division_id', $division_id, $getdata->division_id,['id' => 'division_id', 'class'
                    => 'form-control form-control-select2', 'placeholder' => '*']) !!}
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('purpose', 'Purpose', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!!
                    Form::text('purpose',$getdata->purpose,['id'=>'purpose','class'=>'form-control',
                    'placeholder'=>'Enter menu name','required']) !!}

                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('date', 'Date', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!! Form::text('date',$getdata->date,['id'=>'date','class'=>'form-control
                    date','placeholder'=>'Enter menu name','required']) !!}

                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('overtime_from', 'Overtime From', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!!
                    Form::time('overtime_from',$getdata->overtime_from,['id'=>'overtime_from','class'=>'form-control','placeholder'=>'Enter
                    menu name','required']) !!}

                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('overtime_finish', 'Overtime From', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!!
                    Form::time('overtime_finish',$getdata->overtime_finish,['id'=>'overtime_finish','class'=>'form-control','placeholder'=>'Enter
                    overtime finish','required']) !!}

                </div>
            </div>
            <div class="text-right">
                {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel', 'data-method' =>
                'hrm/request/overtime',
                'type' => 'button','onclick'=>'cancel(this)']) !!}
                <button type="submit" class="btn btn-primary">Update Request<i class="far fa-save ml-2"></i></button>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
    <!-- /basic layout -->

</div>
</div>
</div>
@endsection
@section('script')
<script src="{{ asset('ctrl/hr/req_overtime-form.js?v=').rand() }}" type="text/javascript"></script>
@endsection