@extends('layouts.head')
@section('content')
<div class="content">
    <!-- Basic layout-->
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">Update Travel Request</h5>
        </div>
        <div class="card-body">
            {!! Form::open(['action' => $action, 'method' => $method, 'id' => 'm_form']) !!}
            {!! Form::hidden('id',"Edit",['id'=>'form_edit','class'=>'form-control']) !!}
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
                    {!! Form::select('division_id', $division_id, $getdata->division_id,['id' => 'div', 'class'
                    => 'form-control form-control-select2', 'placeholder' => '*']) !!}
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('destination', 'Destination', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-3">
                    {!! Form::select('des_provinsi', $province, $getdata->des_provinsi,['id' => 'province', 'class' =>
                    'form-control form-control-select2','required']) !!}
                </div>

                <div class="col-lg-3">
                    {!! Form::select('des_kota', $city, $getdata->des_kota,['id' => 'city', 'class' => 'form-control
                    form-control-select2', 'placeholder' => 'Pilih Kota','required'])
                    !!}
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('purpose', 'Purpose', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!!
                    Form::text('purpose',$getdata->purpose,['id'=>'purpose','class'=>'form-control','placeholder'=>'Enter
                    menu name','required']) !!}
                </div>
            </div>

            <div class="form-group row">
                <label class='col-lg-3 col-form-label'>Departure Transport</label>
                <div class="col-lg-7">
                    {!! Form::select('departure_transport',
                    array('Flight' => 'Flight', 'Train' => 'Train', 'Own Car' => 'Own Car', 'Others' => 'Others'),
                    $getdata->departure_transport,
                    ['id' => 'transport', 'class' => 'form-control form-control-select2', 'placeholder' => '*']) !!}
                </div>
            </div>

            <div class="form-group row">
                <label class='col-lg-3 col-form-label'>Return Transport</label>
                <div class="col-lg-7">
                    {!! Form::select('return_transport',
                    array('Flight' => 'Flight', 'Train' => 'Train', 'Own Car' => 'Own Car', 'Others' => 'Others'),
                    $getdata->return_transport,
                    ['id' => 'Rtransport', 'class' => 'form-control form-control-select2', 'placeholder' => '*']) !!}
                </div>
            </div>
            <div class="form-group row">
                <label class='col-lg-3 col-form-label'>Accomodation</label>
                <div class="col-lg-7">
                    {!! Form::select('akomodasi',
                    array('Hotel' => 'Hotel', 'Apartment' => 'Apartment', 'Mess' => 'Mess', 'Relatives Home' =>
                    'Relatives Home', 'Others' => 'Others'),
                    $getdata->akomodasi,['id' => 'akomodasi', 'class' => 'form-control form-control-select2',
                    'placeholder' => '*']) !!}
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('keterangan', 'Note', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!!
                    Form::text('keterangan',$getdata->keterangan,['id'=>'keterangan','class'=>'form-control',
                    'placeholder'=>'Enter Your Note','']) !!}
                </div>
            </div>


            <legend class="text-uppercase font-size-sm font-weight-bold">Date, Time, and Cost</legend>

            <div class="form-group row">
                {!! Form::label('date_departure', 'Date Departure', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!!
                    Form::date('date_departure',$getdata->date_departure,['id'=>'date_departure','class'=>'form-control','placeholder'=>'Enter
                    departure','required']) !!}

                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('time_departure', 'Time Departure', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!! Form::select('time_departure',array('Early' => 'Early', 'Noon' => 'Noon', 'Last' => 'Last'),
                    $getdata->time_departure,
                    ['id' => 'Dtime', 'class' => 'form-control form-control-select2']) !!}
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('date_return', 'Date Return', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!!
                    Form::date('date_return',$getdata->date_return,['id'=>'date_return','class'=>'form-control','placeholder'=>'Enter
                    departure','required']) !!}
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('est_biaya', 'Estimasi Biaya', ['class' => 'col-lg-3 col-form-label']) !!}
                <div class="col-lg-7">
                    {!!Form::number('est_biaya',$getdata->est_biaya,['id'=>'est_biaya','class'=>'form-control','placeholder'=>'','required'])
                    !!}

                </div>
            </div>
            <div class="text-right">
                {!! Form::button('Cancel', ['class' => 'btn btn-danger btn-cancel', 'data-method' =>
                'hrm/request/leave', 'type'
                => 'button','onclick'=>'cancel(this)']) !!}
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
<script src="{{ asset('ctrl/hr/req_travel-form.js?v=').rand() }}" type="text/javascript"></script>
@endsection